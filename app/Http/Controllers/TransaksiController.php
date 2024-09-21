<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\category_harga;
use App\Models\plus_service;
use App\Models\promosi;
use App\Models\status;
use App\Models\transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('transaksi.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $dataPromosi = transaksi::select("uuid", "nama_customer", "tanggal_transaksi", "tracking_number", "total_harga", "status")->get();
            // dd($dataPromosi);
            return DataTables::of($dataPromosi)
                ->addIndexColumn()
                ->make(true);
        }
        return response()->json(['message' => 'Method not allowed'], 405);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = category::all();
        $plus_services = plus_service::all();
        return view('transaksi.create', compact('categories', 'plus_services'));
    }

    public function validatePromosi(Request $request)
    {
        $kode = $request->query('kode');
        $promosi = Promosi::where('kode', $kode)->first();

        if ($promosi && $promosi->isActive()) {
            return response()->json([
                'success' => true,
                'nama_promosi' => $promosi->nama_promosi,
                'discount' => $promosi->discount // Discount should be a decimal (e.g., 0.10 for 10%)
            ]);
        } elseif ($promosi && $promosi->isUpcoming()) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promosi Masih Upcoming Rilis.'
            ]);
        } elseif ($promosi && $promosi->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promosi Sudah Expired.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kode promosi tidak valid.'
            ]);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'nama_customer' => 'required|string|max:255',
                'email_customer' => 'required|email|max:255',
                'notelp_customer' => 'required|string|max:15',
                'alamat_customer' => 'required|string|max:255',
                'plus_services.*' => 'exists:plus_services,id', // Pastikan ID service valid
                'promosi_kode' => 'nullable|string|exists:promosis,kode', // Promosi kode opsional
            ],
            [
                'nama_customer.required' => 'Nama customer wajib diisi.',
                'email_customer.required' => 'Email customer wajib diisi.',
                'email_customer.email' => 'Format email tidak valid.',
                'notelp_customer.required' => 'Nomor telepon wajib diisi.',
                'alamat_customer.required' => 'Alamat wajib diisi.',
                'status.required' => 'Status pembayaran harus dipilih.',
                'plus_services.*.exists' => 'Layanan tambahan tidak valid.',
                'promosi_kode.exists' => 'Kode promosi tidak ditemukan.',
            ]
        );
        DB::beginTransaction(); // Mulai transaksi database

        try {
            // Inisialisasi total harga
            $totalHarga = 0;

            // Ambil dan hitung total harga dari kategori
            foreach ($request->category_hargas as $category_harga) {
                // Ambil data category berdasarkan ID
                $category = category::find($category_harga['id']);

                if ($category) {
                    // Hitung total harga menggunakan qty dari request
                    $totalHarga += $category->price * $category_harga['qty'];
                }
            }

            // Hitung total harga dari plus services
            if ($request->has('plus_services')) {
                foreach ($request->plus_services as $plus_service_id) {
                    $plusService = plus_service::find($plus_service_id);
                    if ($plusService) {
                        $totalHarga += $plusService->price;
                    }
                }
            }

            // Cek apakah ada kode promosi yang dimasukkan
            if ($request->promosi_kode) {
                $promosi = Promosi::where('kode', $request->promosi_kode)->first();

                if (!$promosi) {
                    return redirect()->route('transaksi.index')->with('error', 'Kode promosi tidak valid.');
                }

                // Cek status promosi
                if ($promosi->status === 'upcoming') {
                    return redirect()->route('transaksi.index')->with('error', 'Kode belum bisa digunakan untuk tanggal sekarang.');
                } elseif ($promosi->status === 'expired' || now()->greaterThan($promosi->end_date)) {
                    return redirect()->route('transaksi.index')->with('error', 'Kode promosi sudah expired.');
                } elseif ($promosi->isActive()) {
                    // Terapkan diskon jika promosi aktif
                    $totalHarga -= ($totalHarga * ($promosi->discount)); // Terapkan diskon
                }
            }

            // Hitung remaining_payment jika statusnya adalah downpayment
            $remainingPayment = 0;
            if ($request->status === 'downpayment') {
                $remainingPayment = $totalHarga - $request->downpayment_amount; // Sisa pembayaran
            }

            $downpaymentAmount = round($request->downpayment_amount, 0); // Hapus desimal
            $remainingPayment = round($remainingPayment, 0); // Hapus desimal
            // Simpan transaksi utama
            $transaksi = Transaksi::create([
                'nama_customer' => $request->nama_customer,
                'email_customer' => $request->email_customer,
                'notelp_customer' => $request->notelp_customer,
                'alamat_customer' => $request->alamat_customer,
                'status' => $request->status, // downpayment, paid
                'promosi_id' => $promosi->id ?? null, // Simpan ID promosi jika ada
                'user_id' => auth()->id(), // ID pegawai yang login
                'total_harga' => $totalHarga, // Total harga hasil perhitungan
                'downpayment_amount' => $downpaymentAmount ?? 0, // Jumlah DP (jika ada)
                'remaining_payment' => $remainingPayment ?? 0, // Sisa pembayaran otomatis dihitung
                'tracking_number' => $this->generateTrackingNumber(),
                'tanggal_transaksi' => Carbon::now()->toDateString(), // Tanggal saat ini
                'jam_transaksi' => Carbon::now()->toTimeString(), // Jam saat ini
            ]);
            // dd($request->category_hargas);
            // Simpan kategori harga yang dipilih dalam transaksi
            foreach ($request->category_hargas as $category_harga) {
                $transaksi->categoryHargas()->attach($category_harga['id'], [
                    'uuid' => (string) Str::uuid(),
                    'qty' => $category_harga['qty']
                ]);
            }

            // Simpan plus services yang dipilih
            if ($request->has('plus_services')) {
                foreach ($request->plus_services as $plus_service_id) {
                    $transaksi->plusServices()->attach($plus_service_id, [
                        'uuid' => (string) Str::uuid()
                    ]);
                }
            }

            // Cek apakah status "Pending" sudah ada
            $status = Status::firstOrCreate([
                'name' => 'Pending'
            ]);

            // Simpan status tracking yang pertama kali
            $transaksi->trackingStatuses()->create([
                'status_id' => $status->id,
                'description' => 'Sudah diterima, belum diproses', // Deskripsi default
                'tanggal_status'=> Carbon::now()->toDateString(),
                'jam_status' => Carbon::now()->toTimeString()
            ]);

            DB::commit(); // Commit transaksi jika semuanya sukses

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika ada error

            // Ganti response JSON dengan redirect dan pesan error
            return redirect()->route('transaksi.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    private function generateTrackingNumber()
    {
        do {
            // Daftar karakter pemisah yang diizinkan
            $separators = ['*', '#', '/', '|', '^', '$', '&', '?', '~', '@', '!', '%', '(', ')', '{', '}', '[', ']', ':', ';', ',', '.', '/'];

            // Pilih satu pemisah secara acak
            $separator = $separators[array_rand($separators)];

            // Hasilkan nomor tracking dengan pemisah acak
            $trackingNumber = 'TRX-' . bin2hex(random_bytes(5)) . $separator . bin2hex(random_bytes(2));

            // Periksa di database apakah nomor ini sudah ada
        } while ($this->trackingNumberExists($trackingNumber));

        return $trackingNumber;
    }

    // Fungsi untuk memeriksa apakah nomor tracking sudah ada
    private function trackingNumberExists($trackingNumber)
    {
        return transaksi::where('tracking_number', $trackingNumber)->exists();
    }
    /**
     * Display the specified resource.
     */
    public function show($uuid)
    {
        try {
            // Cari transaksi berdasarkan UUID, sertakan relasi promosi
            $transaksi = Transaksi::with(['categoryHargas', 'plusServices', 'trackingStatuses.status', 'promosi'])
                ->where('uuid', $uuid)
                ->firstOrFail();

            // Kirim data transaksi ke view
            return view('transaksi.show', compact('transaksi'));
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, arahkan kembali dengan pesan error
            return redirect()->route('transaksi.index')->with('error', 'Transaksi tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

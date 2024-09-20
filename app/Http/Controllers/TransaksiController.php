<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\plus_service;
use App\Models\promosi;
use App\Models\transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kode promosi tidak valid atau sudah expired.'
            ]);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction(); // Mulai transaksi database

        try {
            // Inisialisasi total harga
            $totalHarga = 0;

            // Ambil dan hitung total harga dari kategori
            foreach ($request->category_hargas as $category_harga) {
                $category = category::find($category_harga['id']);
                if ($category) {
                    $totalHarga += $category->price * $category_harga['qty']; // harga * qty
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
                    return response()->json([
                        'message' => 'Kode promosi tidak valid.'
                    ], 400);
                }

                // Cek status promosi
                if ($promosi->status === 'upcoming') {
                    return response()->json([
                        'message' => 'Kode belum bisa digunakan untuk tanggal sekarang.'
                    ], 400);
                } elseif ($promosi->status === 'expired' || now()->greaterThan($promosi->end_date)) {
                    return response()->json([
                        'message' => 'Kode promosi sudah expired.'
                    ], 400);
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
                'downpayment_amount' => $request->downpayment_amount ?? null, // Jumlah DP (jika ada)
                'remaining_payment' => $remainingPayment, // Sisa pembayaran otomatis dihitung
                'tracking_number' => $this->generateTrackingNumber(),
            ]);

            // Simpan kategori harga yang dipilih dalam transaksi
            foreach ($request->category_hargas as $category_harga) {
                $transaksi->categoryHargas()->attach($category_harga['id'], [
                    'qty' => $category_harga['qty']
                ]);
            }

            // Simpan plus services yang dipilih
            if ($request->has('plus_services')) {
                foreach ($request->plus_services as $plus_service_id) {
                    $transaksi->plusServices()->attach($plus_service_id, []);
                }
            }

            DB::commit(); // Commit transaksi jika semuanya sukses

            return response()->json([
                'message' => 'Transaksi berhasil disimpan',
                'transaksi' => $transaksi
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika ada error

            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }



    private function generateTrackingNumber()
    {
        return 'TRX-' . strtoupper(uniqid());
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\category_harga;
use App\Models\Members;
use App\Models\MembersTrack;
use App\Models\plus_service;
use App\Models\promosi;
use App\Models\status;
use App\Models\transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\TransaksiRequest;
use App\Http\Requests\ValidatePromosiRequest;
use App\Http\Requests\ValidateMembershipRequest;
use App\Http\Requests\PelunasanRequest;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Mail\TransaksiEmail;
use App\Models\category_layanan;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\qrcodes;
use Illuminate\Support\Facades\Storage;
use App\Models\CategorySepatu;

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
        $categories =
            category_layanan::with(['category' => function ($query) {
                $query->where('status_kategori', 'active');
            }])
            ->get();
        $plus_services = plus_service::where('status_plus_service', 'active')->get();
        $kategori_sepatu = CategorySepatu::all();
        return view('transaksi.create', compact('categories', 'plus_services', 'kategori_sepatu'));
    }

    public function validatePromosi(ValidatePromosiRequest $request)
    {
        $kode = $request->query('kode');
        $promosi = Promosi::where('kode', $kode)->first();
        // dd($promosi->isActive());

        if ($promosi && $promosi->isActive()) {
            return response()->json([
                'success' => true,
                'nama_promosi' => $promosi->nama_promosi,
                'discount' => $promosi->discount, // Discount should be a decimal (e.g., 0.10 for 10%)
                'minimum_payment' => $promosi->minimum_payment
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

    public function destroy(string $uuid)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'You must be logged in to perform this action.'], 403);
        }

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::where('uuid', $uuid)->firstOrFail();
            $transaksi->delete();

            DB::commit();

            return back();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Transaksi tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus transaksi.'], 500);
        }
    }



    public function validateMembership(ValidateMembershipRequest $request)
    {
        DB::beginTransaction();

        $validatedData = $request->validated();
        $membership = Members::where('kode', $validatedData['kode'])->first();
        if (!$membership) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Kode membership tidak valid.'
            ]);
        }

        $membersTrack = MembersTrack::where('membership_id', $membership->id)
            ->orderBy('created_at', 'desc')->first();
        // dd($membersTrack);

        if (!$membersTrack) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada track untuk anggota ini.'
            ]);
        }

        $statusLast = $membersTrack->status;
        if ($statusLast == 'active') {
            DB::commit();
            return response()->json([
                'success' => true,
                'nama_membership' => $membership->nama_membership,
                'email_membership' => $membership->email_membership,
                'phone_membership' => $membership->phone_membership,
                'alamat_membership' => $membership->alamat_membership,
                'discount' => $membersTrack->discount, // Diskon dari membership track
                'kelas_membership' => $membersTrack->kelas_membership,
                'membership_status' => $membersTrack->status
            ]);
        }
        if ($statusLast == 'waiting') {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Status anda masih Waiting, tidak bisa melakukan Transaksi menggunakan member.'
            ], 400);
        } elseif ($statusLast == 'expired') {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Status anda sudah expired, tidak bisa melakukan Transaksi Menggunakan Member.'
            ], 400);
        } else {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Kode membership tidak valid.'
            ], 400);
        }
    }

    // public function setPromosi(Request $request)
    // {
    //     session(['promosi' => $request->all()]);
    //     return response()->json(['success' => true]);
    // }

    // public function setMembership(Request $request)
    // {
    //     session(['membership' => $request->all()]);
    //     return response()->json(['success' => true]);
    // }


    public function pelunasan(PelunasanRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            // Ambil transaksi berdasarkan ID
            $transaksi = Transaksi::findOrFail($id);

            // Pastikan transaksi masih memiliki sisa pembayaran
            if ($transaksi->remaining_payment <= 0) {
                return redirect()->back()->withErrors(['error' => 'Transaksi sudah lunas.']);
            }

            // Jumlah pelunasan
            $pelunasanAmount = round($request->pelunasan_amount, 0);

            // Validasi apakah pelunasan sesuai dengan sisa pembayaran
            if ($pelunasanAmount != $transaksi->remaining_payment) {
                return redirect()->back()->withErrors(['error' => 'Jumlah pelunasan harus sesuai dengan sisa pembayaran: Rp ' . number_format($transaksi->remaining_payment, 0, ',', '.')]);
            }

            // Jika pelunasan sesuai, update status transaksi menjadi paid
            $transaksi->update([
                'remaining_payment' => 0, // Sisa pembayaran jadi 0
                'status_downpayment' => 'paid', // Downpayment lunas
                'status' => 'paid', // Transaksi lunas
                'pelunasan_amount' => $pelunasanAmount,
                'tanggal_pelunasan' => Carbon::now()->toDateString(), // Tanggal saat ini
                'jam_pelunasan' => Carbon::now()->toTimeString(), // Jam saat ini
            ]);

            DB::commit(); // Commit transaksi jika semua berhasil

            return redirect()->route('transaksi.show', $transaksi->uuid)->with('success', 'Pelunasan berhasil disimpan. Transaksi sudah lunas.');
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika ada error

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(TransaksiRequest $request)
    {
        // Debug awal untuk memeriksa input
        // dd($request->all());

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            $totalHarga = 0;

            // Ambil dan filter `category_sepatu` untuk hanya menyimpan elemen `plus_services` yang memiliki 'id'
            $categorySepatu = $request->input('category_sepatu', []);

            foreach ($categorySepatu as &$sepatuData) {
                if (isset($sepatuData['plus_services'])) {
                    // Filter `plus_services` agar hanya menyimpan yang memiliki 'id'
                    $sepatuData['plus_services'] = array_filter($sepatuData['plus_services'], function ($service) {
                        return isset($service['id']);
                    });
                }
            }

            // Hitung total harga berdasarkan `category_hargas` dan `plus_services`
            foreach ($categorySepatu as $sepatu) {
                foreach ($sepatu['category_hargas'] as $category_harga) {
                    $category = Category::find($category_harga['id']);
                    if ($category) {
                        $totalHarga += $category->price * $category_harga['qty'];
                    }
                }

                // Hitung harga dari `plus_services` yang sudah difilter
                foreach ($sepatu['plus_services'] ?? [] as $plus_service) {
                    $plusService = Plus_Service::find($plus_service['id']);
                    if ($plusService) {
                        $totalHarga += $plusService->price;
                    }
                }
            }

            // Penanganan promosi
            if ($request->promosi_kode) {
                $promosi = Promosi::where('kode', $request->promosi_kode)->first();
                if (!$promosi) {
                    return redirect()->route('transaksi.index')->with('error', 'Kode promosi tidak valid.');
                }

                if ($promosi->status === 'upcoming') {
                    return redirect()->route('transaksi.index')->with('error', 'Kode belum berlaku.');
                } elseif ($promosi->status === 'expired') {
                    return redirect()->route('transaksi.index')->with('error', 'Kode promosi sudah expired.');
                } elseif ($promosi->isActive()) {
                    if (!$request->membership_kode && $totalHarga < $promosi->minimum_payment) {
                        return redirect()->route('transaksi.index')->with('error', 'Total harga tidak memenuhi minimum promosi.');
                    }
                    $totalHarga -= $totalHarga * $promosi->discount;
                }
            }

            // Penanganan membership
            if ($request->membership_kode) {
                $member = Members::where('kode', $request->membership_kode)->first();
                if (!$member) {
                    return redirect()->route('transaksi.create')->with('error', 'Kode membership tidak valid.');
                }

                $membersTrack = MembersTrack::where('membership_id', $member->id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if (!$membersTrack || $membersTrack->status !== 'active') {
                    return redirect()->route('transaksi.create')->with('error', 'Kode membership tidak aktif.');
                }

                $totalHarga -= $totalHarga * $membersTrack->discount;
            }

            // Validasi dan penghitungan downpayment
            $minimalDownpayment = $totalHarga * 0.40;
            $downpaymentAmount = round($request->downpayment_amount, 0);
            $remainingPayment = ($request->status === 'downpayment' && $downpaymentAmount < $minimalDownpayment)
                ? redirect()->route('transaksi.create')->with('error', 'Minimal downpayment adalah 40% dari total harga.')
                : $totalHarga - $downpaymentAmount;

            // Simpan data transaksi
            $transaksi = Transaksi::create([
                'nama_customer' => $request->nama_customer,
                'email_customer' => $request->email_customer,
                'notelp_customer' => $request->notelp_customer,
                'alamat_customer' => $request->alamat_customer,
                'status' => $request->status,
                'promosi_id' => $promosi->id ?? null,
                'user_id' => auth()->id(),
                'total_harga' => $totalHarga,
                'downpayment_amount' => $downpaymentAmount ?? 0,
                'remaining_payment' => $remainingPayment ?? 0,
                'membership_id' => $member->id ?? null,
                'tracking_number' => $this->generateTrackingNumber(),
                'tanggal_transaksi' => Carbon::now()->toDateString(),
                'jam_transaksi' => Carbon::now()->toTimeString(),
            ]);

            // Simpan kategori sepatu dan harga
            foreach ($categorySepatu as $category_sepatu) {
                if (isset($category_sepatu['category_hargas'])) {
                    foreach ($category_sepatu['category_hargas'] as $category_harga) {
                        if (isset($category_harga['qty']) && $category_harga['qty'] > 0) {
                            $transaksi->categoryHargas()->attach($category_harga['id'], [
                                'uuid' => (string) Str::uuid(),
                                'qty' => $category_harga['qty'],
                                'category_sepatu_id' => $category_sepatu['id'],
                            ]);
                        } else {
                            return redirect()->route('transaksi.create')->with('error', 'Qty tidak valid untuk salah satu kategori.');
                        }
                    }
                }

                // Simpan layanan tambahan plus_services untuk setiap category_sepatu
                if (isset($category_sepatu['plus_services'])) {
                    foreach ($category_sepatu['plus_services'] as $plus_service) {
                        $transaksi->plusServices()->attach($plus_service['id'], [
                            'uuid' => (string) Str::uuid(),
                            'category_sepatu_id' => $plus_service['category_sepatu_id'],
                        ]);
                    }
                }

                // Generate QR code, save as PNG, and store the file
                $qrCodeData = 'Transaksi ID: ' . $transaksi->id . ', Kategori: ' . $category_sepatu['id'];
                $fileName = 'qrcode_' . time() . '_' . $category_sepatu['id'] . '.png';
                $filePath = 'qrcodes/' . $fileName;
                // dd($qrCodeData, $fileName, $filePath);
                // Simpan QR code sebagai file di storage
                Storage::disk('public')->put($filePath, QrCode::format('png')->size(200)->generate($qrCodeData));

                $Qrcode = qrcodes::create([
                    'transaksi_id' => $transaksi->id,
                    'category_sepatu' => $category_sepatu['id'],
                    'role' => 'karyawan',
                    'qrcode' => $filePath,
                    'code' => $qrCodeData,
                    'create_code_date' => Carbon::now()->toDateString(),
                    'create_code_time' => Carbon::now()->toTimeString(),
                ]);
                // dd($Qrcode);
            }

            // Simpan status pertama kali
            $status = Status::firstOrCreate(['name' => 'Pending']);
            $transaksi->trackingStatuses()->create([
                'status_id' => $status->id,
                'description' => 'Sudah diterima, belum diproses',
                'tanggal_status' => Carbon::now()->toDateString(),
                'jam_status' => Carbon::now()->toTimeString(),
            ]);

            DB::commit();

            // Mail::to($transaksi->email_customer)->send(new TransaksiEmail($transaksi));
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('transaksi.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }





    public function updateStatusPickup(Request $request, $id)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Validasi bahwa transaksi ada
            $transaksi = Transaksi::findOrFail($id);

            // Pastikan transaksi tidak dalam status "downpayment"
            if ($transaksi->status === 'downpayment') {
                return redirect()->route('transaksi.show', $transaksi->uuid)->with('error', 'Transaksi harus berstatus "Paid" untuk dapat diproses.');
            }

            // Dapatkan status tracking terakhir
            $lastTrackingStatus = $transaksi->trackingStatuses()->latest()->first();

            // Pastikan status terakhir adalah "Finish"
            if ($lastTrackingStatus && $lastTrackingStatus->status->name !== 'Finish') {
                return redirect()->route('transaksi.show', $transaksi->uuid)->with('error', 'Transaksi harus berstatus "Finish" untuk dapat diproses.');
            }

            // Update status pickup menjadi 'picked_up'
            $transaksi->update([
                'status_pickup' => 'picked_up', // Hardcode nilai 'picked_up' tanpa input pengguna
                'tanggal_pickup' => Carbon::now()->toDateString(), // Tanggal saat ini
                'jam_pickup' => Carbon::now()->toTimeString(), // Jam saat ini
            ]);

            // Commit transaksi
            DB::commit();

            // Redirect kembali ke halaman detail transaksi dengan pesan sukses
            return redirect()->route('transaksi.show', $transaksi->uuid)->with('success', 'Status pickup berhasil diubah menjadi picked up.');
        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::rollBack();
            return redirect()->route('transaksi.show', $transaksi->uuid)->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

    public function proses(Request $request, string $uuid)
    {
        DB::beginTransaction();

        try {
            // Cari transaksi berdasarkan UUID
            $transaksi = Transaksi::where('uuid', $uuid)->first();
            if (!$transaksi) {
                return redirect()->route('transaksi.index')->with('error', 'Transaksi tidak ditemukan.');
            }

            // Cek apakah status terakhir transaksi adalah "Pending"
            $lastTrackingStatus = $transaksi->trackingStatuses()->latest()->first();
            if ($lastTrackingStatus->status->name !== 'Pending') {
                return redirect()->route('transaksi.index')->with('error', 'Transaksi harus berstatus "Pending" untuk dapat diproses.');
            }

            // Hardcode nilai deskripsi dan status
            $status = Status::firstOrCreate(['name' => 'Proses']);
            $validDescription = 'Sudah diterima, sepatu sedang diproses';

            // Simpan status tracking baru tanpa melibatkan input user
            $transaksi->trackingStatuses()->create([
                'status_id' => $status->id,
                'description' => $validDescription, // Hardcoded di server
                'tanggal_status' => Carbon::now()->toDateString(),
                'jam_status' => Carbon::now()->toTimeString()
            ]);

            DB::commit();
            return redirect()->route('transaksi.show', $uuid)->with('success', 'Status transaksi berhasil diperbarui ke "Proses".');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('transaksi.show', $uuid)->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }




    public function finish(Request $request, string $uuid)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Cari transaksi berdasarkan UUID
            $transaksi = Transaksi::where('uuid', $uuid)->first();

            if (!$transaksi) {
                return redirect()->route('transaksi.index')->with('error', 'Transaksi tidak ditemukan.');
            }

            // Cek apakah status terakhir transaksi adalah "Proses"
            $lastTrackingStatus = $transaksi->trackingStatuses()->latest()->first();

            if ($lastTrackingStatus->status->name !== 'Proses') {
                return redirect()->route('transaksi.index')->with('error', 'Transaksi harus berstatus "Proses" untuk dapat diselesaikan.');
            }

            // Validasi status name harus 'Finish'
            $validStatusName = 'Finish';
            $status = Status::firstOrCreate(['name' => $validStatusName]);

            if ($status->name !== $validStatusName) {
                DB::rollBack(); // Batalkan transaksi
                return redirect()->route('transaksi.index')->with('error', 'Status harus bernama "Finish".');
            }

            // Hardcode description: deskripsi yang valid harus 'Sepatu sudah selesai pencucian'
            $validDescription = 'Sepatu sudah selesai pencucian';

            // Simpan status tracking yang baru tanpa mengambil input dari pengguna
            $transaksi->trackingStatuses()->create([
                'status_id' => $status->id,
                'description' => $validDescription, // Deskripsi yang sudah di-hardcode
                'tanggal_status' => Carbon::now()->toDateString(),
                'jam_status' => Carbon::now()->toTimeString()
            ]);

            // Commit transaksi
            DB::commit();

            return redirect()->route('transaksi.show', $uuid)->with('success', 'Status transaksi berhasil diperbarui ke "Finish".');
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan jika terjadi error

            return redirect()->route('transaksi.show', $uuid)->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    public function revisi(Request $request, string $uuid)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Cari transaksi berdasarkan UUID
            $transaksi = Transaksi::where('uuid', $uuid)->first();

            if (!$transaksi) {
                return redirect()->route('transaksi.index')->with('error', 'Transaksi tidak ditemukan.');
            }

            // Ambil status terakhir dan status sebelum terakhir
            $lastTrackingStatus = $transaksi->trackingStatuses()->latest()->first();
            $previousTrackingStatus = $transaksi->trackingStatuses()->latest()->skip(1)->first();

            // Jika tidak ada status sebelumnya, batalkan revisi
            if (!$previousTrackingStatus) {
                return redirect()->route('transaksi.index')->with('error', 'Tidak ada status sebelumnya untuk direvisi.');
            }

            // Hapus status terakhir
            $lastTrackingStatus->delete();

            // Commit transaksi
            DB::commit();

            return redirect()->route('transaksi.show', $uuid)->with('success', 'Status terakhir berhasil dihapus dan status dikembalikan ke: ' . $previousTrackingStatus->status->name);
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan jika terjadi error

            return redirect()->route('transaksi.show', $uuid)->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function cetak_pdf($uuid)
    {
        // Cari transaksi berdasarkan UUID
        $transaksi = Transaksi::with(['categoryHargas', 'plusServices', 'trackingStatuses.status', 'promosi'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        // Ambil semua kategori dari database untuk diakses sebagai kategori induk dan sub-kategori
        $categories = Category::all();

        // Logika Diskon dari MembersTrack
        if ($transaksi->member) {
            // Jika ada member, ambil track terbaru dan diskon
            $memberTrack = $transaksi->member->tracks->first(); // Asumsi mengambil track terbaru
            $memberDiscount = $memberTrack ? $memberTrack->discount : 0;
        } else {
            // Jika tidak ada member, diskon 0
            $memberDiscount = 0;
        }

        // Sanitize the tracking number to remove invalid characters
        $safeTrackingNumber = preg_replace('/[\/\\\]/', '_', $transaksi->tracking_number);

        // Load view untuk PDF, dan kirimkan data transaksi serta kategori ke view
        $pdf = PDF::loadView('transaksi.cetak_pdf', compact('transaksi', 'categories', 'memberDiscount'));

        // Set orientasi landscape dan ukuran kertas jika perlu
        return $pdf->setPaper('b4', 'portrait')->stream('transaksi_' . $safeTrackingNumber . '.pdf');
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
            // Cari transaksi berdasarkan UUID, sertakan relasi promosi dan member
            $transaksi = Transaksi::with(['categoryHargas', 'plusServices', 'trackingStatuses.status', 'promosi', 'member.tracks'])
                ->where('uuid', $uuid)
                ->firstOrFail();
            // Ambil semua kategori dari database
            $categories = Category::all();

            // Logika Diskon dari MembersTrack
            if ($transaksi->member) {
                // Jika ada member, ambil track terbaru dan diskon
                $memberTrack = $transaksi->member->tracks->first(); // Asumsi mengambil track terbaru
                $memberDiscount = $memberTrack ? $memberTrack->discount : 0;
            } else {
                // Jika tidak ada member, diskon 0
                $memberDiscount = 0;
            }

            // Kirim data transaksi, kategori, dan diskon ke view
            return view('transaksi.show', compact('transaksi', 'categories', 'memberDiscount'));
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, arahkan kembali dengan pesan error
            return redirect()->route('transaksi.index')->with('error', 'Transaksi tidak ditemukan.');
        }
    }
}

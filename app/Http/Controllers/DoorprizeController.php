<?php

namespace App\Http\Controllers;

use App\Models\DoorprizeWinner;
use App\Models\Hadiah;
use App\Models\transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoorprizeController extends Controller
{

    public function getHadiahData()
    {
        // Ambil semua hadiah yang jumlahnya lebih dari 0
        $hadiah = Hadiah::where('jumlah', '>', 0)->get();

        // Misalnya, jumlah putaran ditentukan oleh total jumlah hadiah yang tersedia
        $totalHadiah = $hadiah->sum('jumlah');
        $spins = $totalHadiah > 10 ? 8 : ($totalHadiah > 5 ? 6 : 4); // Tentukan jumlah putaran sesuai logika yang diinginkan

        // Kembalikan data hadiah dalam bentuk JSON, sertakan jumlah putaran
        return response()->json([
            'success' => true,
            'hadiah' => $hadiah,
            'spins' => $spins // Kirim jumlah putaran ke frontend
        ]);
    }

    public function getHadiah()
    {
        $hadiahdata = Hadiah::select("nama_hadiah", "jumlah")->get();
        return response()->json([
            'success' => true,
            'hadiahData' => $hadiahdata,
        ]);
    }

    // Fungsi untuk memilih pemenang berdasarkan ID hadiah
    // public function pickDoorprizeWinner(Request $request)
    // {
    //     DB::beginTransaction();

    //     try {
    //         // Ambil ID hadiah dari request
    //         $hadiahId = $request->input('hadiah_id');

    //         // Ambil hadiah yang terpilih
    //         $hadiah = Hadiah::find($hadiahId);

    //         // Validasi apakah hadiah valid dan masih tersedia
    //         if (!$hadiah || $hadiah->jumlah <= 0) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Hadiah tidak valid atau sudah habis.'
    //             ]);
    //         }

    //         // Ambil daftar hadiah sebelum melakukan decrement
    //         $hadiahItems = Hadiah::where('jumlah', '>', 0)->get();

    //         // Ambil transaksi berdasarkan periode hadiah
    //         $trackingNumbers = Transaksi::whereBetween('tanggal_transaksi', [$hadiah->tanggal_awal, $hadiah->tanggal_akhir])
    //             ->pluck('tracking_number');

    //         // Cek apakah ada transaksi yang sesuai
    //         if ($trackingNumbers->isEmpty()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Tidak ada transaksi yang sesuai dengan periode hadiah ini. Roda tetap bisa diputar namun tidak ada pemenang.'
    //             ]);
    //         }

    //         // Pilih pemenang secara acak dari tracking numbers
    //         $winnerTrackingNumber = $trackingNumbers->random();
    //         $winner = Transaksi::where('tracking_number', $winnerTrackingNumber)->first();

    //         // Kurangi jumlah hadiah
    //         $hadiah->decrement('jumlah');

    //         // Simpan pemenang ke dalam tabel doorprize_winners
    //         DoorprizeWinner::create([
    //             'transaksi_id' => $winner->id,
    //             'hadiah_id' => $hadiah->id,
    //         ]);

    //         // Dapatkan index dari hadiah terpilih
    //         $winningItemIndex = $hadiahItems->search(function ($item) use ($hadiah) {
    //             return $item->id === $hadiah->id;
    //         });

    //         // Cek apakah winningItemIndex ditemukan
    //         if ($winningItemIndex === false) {
    //             DB::rollBack(); // Batalkan transaksi jika hadiah tidak ditemukan
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Hadiah tidak ditemukan dalam daftar.',
    //             ]);
    //         }

    //         // Jika semua operasi berhasil, commit transaksi
    //         DB::commit();

    //         // Kembalikan informasi pemenang dan winningItemIndex
    //         return response()->json([
    //             'success' => true,
    //             'winningItemIndex' => $winningItemIndex, // Index dari hadiah terpilih
    //             'winners' => [
    //                 [
    //                     'nama_customer' => $winner->nama_customer,
    //                     'email_customer' => $winner->email_customer,
    //                     'tracking_number' => $winner->tracking_number,
    //                     'hadiah' => $hadiah->nama_hadiah
    //                 ]
    //             ]
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Terjadi kesalahan saat memproses undian: ' . $e->getMessage(),
    //         ]);
    //     }
    // }

    // Fungsi untuk memilih pemenang berdasarkan ID hadiah
    public function pickDoorprizeWinner(Request $request)
    {
        DB::beginTransaction();

        try {
            // Ambil ID hadiah dari request
            $hadiahId = $request->input('hadiah_id');

            // Ambil hadiah yang terpilih
            $hadiah = Hadiah::find($hadiahId);

            // Validasi apakah hadiah valid dan masih tersedia
            if (!$hadiah || $hadiah->jumlah <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hadiah tidak valid atau sudah habis.'
                ]);
            }

            // Ambil daftar hadiah sebelum melakukan decrement
            $hadiahItems = Hadiah::where('jumlah', '>', 0)->get();

            // Ambil transaksi berdasarkan periode hadiah, dan pastikan tracking number belum pernah menang
            // Ambil tracking number dari transaksi yang ada dalam periode hadiah
            $trackingNumbers = Transaksi::whereBetween('tanggal_transaksi', [$hadiah->tanggal_awal, $hadiah->tanggal_akhir])
                ->whereNotIn('tracking_number', function ($query) {
                    $query->select('transaksis.tracking_number')
                        ->from('transaksis') // Pastikan nama tabel benar
                        ->join(
                            'doorprize_winners',
                            'transaksis.id',
                            '=',
                            'doorprize_winners.transaksi_id'
                        ); // Pastikan nama kolom dan join benar
                })
                ->pluck('tracking_number');

            // Debug hasil query untuk memastikan tidak ada masalah
            // dd($trackingNumbers);


            // Cek apakah ada transaksi yang sesuai
            if ($trackingNumbers->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada transaksi yang sesuai dengan periode hadiah ini'
                ]);
            }

            // Pilih pemenang secara acak dari tracking numbers yang tersisa
            $winnerTrackingNumber = $trackingNumbers->random();
            $winner = Transaksi::where('tracking_number', $winnerTrackingNumber)->first();

            // Kurangi jumlah hadiah
            $hadiah->decrement('jumlah');

            // Simpan pemenang ke dalam tabel doorprize_winners
            DoorprizeWinner::create([
                'transaksi_id' => $winner->id,
                'hadiah_id' => $hadiah->id,
            ]);

            // Dapatkan index dari hadiah terpilih
            $winningItemIndex = $hadiahItems->search(function ($item) use ($hadiah) {
                return $item->id === $hadiah->id;
            });

            // Cek apakah winningItemIndex ditemukan
            if ($winningItemIndex === false) {
                DB::rollBack(); // Batalkan transaksi jika hadiah tidak ditemukan
                return response()->json([
                    'success' => false,
                    'message' => 'Hadiah tidak ditemukan dalam daftar.',
                ]);
            }

            // Jika semua operasi berhasil, commit transaksi
            DB::commit();

            // Kembalikan informasi pemenang dan winningItemIndex
            return response()->json([
                'success' => true,
                'winningItemIndex' => $winningItemIndex, // Index dari hadiah terpilih
                'winners' => [
                    [
                        'nama_customer' => $winner->nama_customer,
                        'email_customer' => $winner->email_customer,
                        'tracking_number' => $winner->tracking_number,
                        'hadiah' => $hadiah->nama_hadiah
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses undian: ' . $e->getMessage(),
            ]);
        }
    }












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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

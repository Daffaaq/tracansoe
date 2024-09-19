<?php

namespace App\Http\Controllers;

use App\Models\promosi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PromosiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Promosi.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $dataPromosi = promosi::select("uuid", "nama_promosi", "start_date", "end_date", "status", "kode")->get();
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
        return view('Promosi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_promosi' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'kode' => 'required|string',
            'discount' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
        ]);

        // Cek apakah ada promosi lain dengan rentang tanggal yang persis sama
        $existingPromosiExact = promosi::where('start_date', $request->start_date)
            ->where('end_date', $request->end_date)
            ->first();

        if ($existingPromosiExact) {
            return redirect()->back()->withErrors(['error' => 'Promosi dengan rentang tanggal tersebut sudah ada.']);
        }

        $cekkode = promosi::where('kode', $request->kode)->first();

        if ($cekkode) {
            return redirect()->back()->withErrors(['error' => 'Promosi dengan kode tersebut sudah ada.']);
        }

        $existingPromosi = promosi::where(function ($query) use ($request) {
            $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                ->orWhere(function ($query) use ($request) {
                    $query->where('start_date', '<=', $request->start_date)
                        ->where('end_date', '>=', $request->end_date);
                });
        })->first();

        if ($existingPromosi) {
            return redirect()->back()->withErrors(['error' => 'Rentang tanggal promosi sudah ada, harap memilih rentang tanggal yang berbeda.']);
        }

        // Jika ada file gambar yang diunggah
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/promosi'), $imageName);
        } else {
            $imageName = null; // Jika tidak ada gambar, biarkan null
        }
        // Konversi diskon dari persentase ke desimal
        $discountInDecimal = $request->discount / 100;

        // Tentukan status promosi berdasarkan tanggal mulai dan berakhir
        $today = now();
        if ($request->start_date > $today) {
            $status = 'upcoming';
        } elseif ($request->start_date <= $today && $request->end_date >= $today) {
            $status = 'active';
        } else {
            $status = 'expired';
        }
        promosi::create([
            'nama_promosi' => $request->nama_promosi,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'kode' => $request->kode,
            'status' => $status,
            'discount' => $discountInDecimal,
            'image' => $imageName,
            'description' => $request->description,
        ]);

        return redirect()->route('promosi.index')->with('success', 'Promosi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $promosi = promosi::where('uuid', $uuid)->firstOrFail();
        return view('Promosi.show', compact('promosi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $promosi = promosi::where('uuid', $uuid)->firstOrFail();
        // dd($promosi);
        return view('Promosi.edit', compact('promosi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $request->validate([
            'nama_promosi' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'kode' => 'required|string',
            'discount' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
        ]);

        $promosi = promosi::where('uuid', $uuid)->firstOrFail();

        $existingPromosiExact = promosi::where('start_date', $request->start_date)
            ->where('end_date', $request->end_date)
            ->where('id', '!=', $promosi->id) // Mengecualikan promosi yang sedang di-update
            ->first();

        if ($existingPromosiExact) {
            return redirect()->back()->withErrors(['error' => 'Promosi dengan rentang tanggal tersebut sudah ada.']);
        }

        $cekkode = promosi::where('kode', $request->kode)->where('id', '!=', $promosi->id)->first();

        if ($cekkode) {
            return redirect()->back()->withErrors(['error' => 'Promosi dengan kode tersebut sudah ada.']);
        }

        // Cek apakah ada promosi lain dengan rentang tanggal yang tumpang tindih, kecuali promosi ini sendiri
        $existingPromosi = promosi::where(function ($query) use ($request) {
            $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                ->orWhere(function ($query) use ($request) {
                    $query->where('start_date', '<=', $request->start_date)
                        ->where('end_date', '>=', $request->end_date);
                });
        })
            ->where('id', '!=', $promosi->id) // Mengecualikan promosi yang sedang di-update
            ->first();

        if ($existingPromosi) {
            return redirect()->back()->withErrors(['error' => 'Rentang tanggal promosi sudah ada, harap memilih rentang tanggal yang berbeda.']);
        }

        // Jika ada file gambar baru yang diunggah
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($promosi->image && file_exists(public_path('images/promosi/' . $promosi->image))) {
                unlink(public_path('images/promosi/' . $promosi->image));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/promosi'), $imageName);
        } else {
            $imageName = $promosi->image; // Jika tidak ada gambar baru, simpan gambar lama
        }
        $discountInDecimal = $request->discount / 100;

        // Tentukan status promosi berdasarkan tanggal mulai dan berakhir
        $today = now();
        if ($request->start_date > $today) {
            $status = 'upcoming';
        } elseif ($request->start_date <= $today && $request->end_date >= $today) {
            $status = 'active';
        } else {
            $status = 'expired';
        }
        // dd($status);
        $promosi->update([
            'nama_promosi' => $request->nama_promosi,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'kode' => $request->kode,
            'discount' => $discountInDecimal,
            'status' => $status,
            'image' => $imageName,
            'description' => $request->description,
        ]);

        return redirect()->route('promosi.index')->with('success', 'Promosi berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $promosi = promosi::where('uuid', $uuid)->firstOrFail();

        // Hapus gambar dari folder jika ada
        if ($promosi->image && file_exists(public_path('images/promosi/' . $promosi->image))) {
            unlink(public_path('images/promosi/' . $promosi->image));
        }

        // Hapus data promosi
        $promosi->delete();

        return redirect()->route('promosi.index')->with('success', 'Promosi berhasil dihapus.');
    }
}

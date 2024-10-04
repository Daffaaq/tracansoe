<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Hadiah;

class HadiahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('hadiah.index');
    }


    public function list(Request $request)
    {
        if ($request->ajax()) {
            $dataPlusService = Hadiah::select("uuid", "nama_hadiah", "jumlah", "tanggal_awal", "tanggal_akhir")->get();
            return DataTables::of($dataPlusService)
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
        return view('hadiah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_hadiah' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jumlah' => 'required|integer|min:0',
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_awal',
        ]);

        Hadiah::create($request->all());
        return redirect()->route('hadiah.index')->with('success', 'Hadiah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $hadiah = Hadiah::where('uuid', $uuid)->firstOrFail();
        return view('hadiah.show', compact('hadiah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $hadiah = Hadiah::where('uuid', $uuid)->firstOrFail();
        return view('hadiah.edit', compact('hadiah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $request->validate([
            'nama_hadiah' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jumlah' => 'required|integer|min:0',
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_awal',
        ]);

        $hadiah = Hadiah::where('uuid', $uuid)->firstOrFail();
        $hadiah->update($request->all());
        return redirect()->route('hadiah.index')->with('success', 'Hadiah berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $hadiah = Hadiah::where('uuid', $uuid)->firstOrFail();
        $hadiah->delete();
        return redirect()->route('hadiah.index')->with('success', 'Hadiah berhasil dihapus.');
    }
}

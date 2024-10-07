<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\HadiahRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
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
    public function store(HadiahRequest $request)
    {
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
    public function update(HadiahRequest $request, string $uuid)
    {
        $hadiah = Hadiah::where('uuid', $uuid)->firstOrFail();
        $hadiah->update($request->all());
        return redirect()->route('hadiah.index')->with('success', 'Hadiah berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'You must be logged in to perform this action.'], 403);
        }

        DB::beginTransaction();

        try {
            $hadiah = Hadiah::where('uuid', $uuid)->firstOrFail();
            $hadiah->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Hadiah berhasil dihapus.'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Hadiah tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus hadiah.'], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use App\Models\plus_service;

class PlusServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('plus_services.index');
    }


    public function list(Request $request)
    {
        if ($request->ajax()) {
            $dataPlusService = plus_service::select("uuid", "name", "price")->get();
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
        if (auth()->user()->role != 'superadmin') {
            return redirect()->route('plus-service.index')->with('error', 'Anda tidak memiliki akses untuk membuat plus service.');
        }
        return view('plus_services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role != 'superadmin') {
            return redirect()->route('plus-service.index')->with('error', 'Anda tidak memiliki akses untuk menyimpan plus service.');
        }
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        plus_service::create([
            'uuid' => Str::uuid(),
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return redirect()->route('plus-service.index')->with('success', 'Plus service berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $plusService = plus_service::where('uuid', $uuid)->firstOrFail();
        return view('plus_services.show', compact('plusService'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        if (auth()->user()->role != 'superadmin') {
            return redirect()->route('plus-service.index')->with('error', 'Anda tidak memiliki akses untuk mengedit plus service.');
        }
        $plusService = plus_service::where('uuid', $uuid)->firstOrFail();
        return view('plus_services.edit', compact('plusService'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        if (auth()->user()->role != 'superadmin') {
            return redirect()->route('plus-service.index')->with('error', 'Anda tidak memiliki akses untuk memperbarui plus service.');
        }
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $plusService = plus_service::where('uuid', $uuid)->firstOrFail();

        $plusService->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return redirect()->route('plus-service.index')->with('success', 'Plus service berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        if (auth()->user()->role != 'superadmin') {
            return redirect()->route('plus-service.index')->with('error', 'Anda tidak memiliki akses untuk menghapus plus service.');
        }
        $plusService = plus_service::where('uuid', $uuid)->firstOrFail();

        // Hapus data plus service
        $plusService->delete();

        return redirect()->route('plus-service.index')->with('success', 'Plus service berhasil dihapus.');
    }
}

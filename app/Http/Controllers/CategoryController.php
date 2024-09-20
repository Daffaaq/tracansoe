<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use App\Models\category;

class CategoryController extends Controller
{
    public function index()
    {
        return view('categories.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $dataCategory = category::select("uuid", "nama_kategori", "price", "estimation")->get();
            return DataTables::of($dataCategory)
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
            return redirect()->route('kategori.index')->with('error', 'Anda tidak memiliki akses untuk menambah kategori.');
        }
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role != 'superadmin') {
            return redirect()->route('kategori.index')->with('error', 'Anda tidak memiliki akses untuk menyimpan kategori.');
        }
        $request->validate([
            'nama_kategori' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'estimation' => 'required|integer',
        ]);

        category::create([
            'uuid' => Str::uuid(),
            'nama_kategori' => $request->nama_kategori,
            'price' => $request->price,
            'description' => $request->description,
            'estimation' => $request->estimation,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $category = category::where('uuid', $uuid)->firstOrFail();
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        if (auth()->user()->role != 'superadmin') {
            return redirect()->route('kategori.index')->with('error', 'Anda tidak memiliki akses untuk mengedit kategori.');
        }
        $category = category::where('uuid', $uuid)->firstOrFail();
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        if (auth()->user()->role != 'superadmin') {
            return redirect()->route('kategori.index')->with('error', 'Anda tidak memiliki akses untuk memperbarui kategori.');
        }
        $request->validate([
            'nama_kategori' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'estimation' => 'required|integer',
        ]);

        $category = category::where('uuid', $uuid)->firstOrFail();

        $category->update([
            'nama_kategori' => $request->nama_kategori,
            'price' => $request->price,
            'description' => $request->description,
            'estimation' => $request->estimation,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        if (auth()->user()->role != 'superadmin') {
            return redirect()->route('kategori.index')->with('error', 'Anda tidak memiliki akses untuk menghapus kategori.');
        }
        $category = category::where('uuid', $uuid)->firstOrFail();

        // Hapus data kategori
        $category->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}

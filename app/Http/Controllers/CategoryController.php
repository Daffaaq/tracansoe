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
        $data = category::all();
        // dd($data);
        return view('categories.index', compact('data'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $dataCategory = category::select("id", "uuid", "nama_kategori")->whereNull('parent_id')->get();
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
        // Cek apakah user adalah superadmin
        if (auth()->user()->role != 'superadmin') {
            return redirect()->route('kategori.index')->with('error', 'Anda tidak memiliki akses untuk menyimpan kategori.');
        }

        // Validasi data input
        $request->validate([
            'nama_kategori' => 'required|string',
            'description' => 'required|string',
        ]);

        // Simpan Kategori Induk (parent_id = null)
        category::create([
            'uuid' => Str::uuid(),
            'nama_kategori' => $request->nama_kategori,
            'price' => null, // Price is null for category induk
            'description' => $request->description,
            'estimation' => null,
            'parent_id' => null, // Kategori induk
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori Induk berhasil ditambahkan.');
    }

    public function tambahSubKategori($uuid)
    {

        if (auth()->user()->role != 'superadmin') {
            return redirect()->route('kategori.index')->with('error', 'Anda tidak memiliki akses untuk mengedit kategori.');
        }
        $category = category::where('uuid', $uuid)->firstOrFail();
        return view('categories.create-subcategory', compact('category'));
    }
    public function storeSubCategory(Request $request, $uuid)
    {
        // Cek apakah user adalah superadmin
        if (auth()->user()->role != 'superadmin') {
            return redirect()->route('kategori.index')->with('error', 'Anda tidak memiliki akses untuk menyimpan sub-kategori.');
        }

        // Cari kategori induk berdasarkan UUID
        $category = category::where('uuid', $uuid)->firstOrFail();

        // Validasi data input tanpa memerlukan validasi parent_id
        $request->validate([
            'nama_kategori' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'estimation' => 'required|integer',
            'parent_id' => 'exists:categories,id',
        ]);

        // Simpan Sub-Kategori (parent_id mengacu ke Kategori Induk)
        $subCategory = category::create([
            'nama_kategori' => $request->nama_kategori,
            'price' => $request->price, // Harga untuk sub-kategori
            'description' => $request->description,
            'estimation' => $request->estimation,
            'parent_id' => $category->id, // Mengacu ke Kategori Induk
        ]);

        return redirect()->route('kategori.index')->with('success', 'Sub-Kategori berhasil ditambahkan.');
    }


    public function showSubCategory($uuid)
    {

        $category = category::where('uuid', $uuid)->with('subKriteria')->firstOrFail();
        return view('categories.show-subcategory', compact('category'));
    }

    public function deleteSubCategory(string $uuid)
    {
        if (auth()->user()->role != 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus sub-kategori.'
            ]);
        }

        $category = category::where('uuid', $uuid)->with('subKriteria')->firstOrFail();
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sub-Kategori berhasil dihapus.'
        ]);
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

        $category = category::where('uuid', $uuid)->firstOrFail();

        // Update Kategori Induk
        if ($category->parent_id === null) {
            $request->validate([
                'nama_kategori' => 'required|string',
                'description' => 'required|string',
            ]);

            $category->update([
                'nama_kategori' => $request->nama_kategori,
                'description' => $request->description,
            ]);
        }

        // Update Sub-Kategori
        if ($request->has('subKriteria')) {
            foreach ($request->subKriteria as $subKategoriData) {
                // Pastikan bahwa data id ada
                if (isset($subKategoriData['id'])) {
                    $subKategori = category::findOrFail($subKategoriData['id']);
                    $subKategori->update([
                        'nama_kategori' => $subKategoriData['nama_kategori'],
                        'price' => $subKategoriData['price'],
                        'estimation' => $subKategoriData['estimation'],
                    ]);
                }
            }
        }

        return redirect()->route('kategori.index')->with('success', 'Kategori dan Sub-Kategori berhasil diperbarui.');
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

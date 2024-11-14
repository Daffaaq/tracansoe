<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\StoreMainCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\DB;
use App\Models\category;
use App\Models\category_layanan;
use Illuminate\Support\Facades\Log;

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
            $dataCategory = category_layanan::select("id", "uuid", "treatment_type")->get();
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
    public function store(StoreMainCategoryRequest $request)
    {
        // Cek apakah user adalah superadmin
        if (auth()->user()->role != 'superadmin') {
            return redirect()->route('kategori.index')->with('error', 'Anda tidak memiliki akses untuk menyimpan kategori.');
        }

        category_layanan::create([
            'uuid' => Str::uuid(),
            'description' => $request->description,
            'treatment_type' => $request->treatment_type,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori Induk berhasil ditambahkan.');
    }

    public function tambahSubKategori($uuid)
    {

        if (auth()->user()->role != 'superadmin') {
            return redirect()->route('kategori.index')->with('error', 'Anda tidak memiliki akses untuk mengedit kategori.');
        }
        $category = category_layanan::where('uuid', $uuid)->firstOrFail();
        return view('categories.create-subcategory', compact('category'));
    }
    public function storeSubCategory(StoreCategoryRequest $request, $uuid)
    {
        // Cek apakah user adalah superadmin
        if (auth()->user()->role != 'superadmin') {
            return redirect()->route('kategori.index')->with('error', 'Anda tidak memiliki akses untuk menyimpan sub-kategori.');
        }

        // Cari kategori induk berdasarkan UUID
        $category = category_layanan::where('uuid', $uuid)->firstOrFail();

        // Simpan Sub-Kategori (parent_id mengacu ke Kategori Induk)
        $subCategory = category::create([
            'nama_kategori' => $request->nama_kategori,
            'price' => $request->price, // Harga untuk sub-kategori
            'description' => $request->description,
            'estimation' => $request->estimation,
            'layanan_kategori_id' => $category->id, // Mengacu ke Kategori Induk
        ]);

        return redirect()->route('kategori.index')->with('success', 'Sub-Kategori berhasil ditambahkan.');
    }


    public function showSubCategory($uuid)
    {

        $category1 = category_layanan::where('uuid', $uuid)->firstOrFail();
        $category = category::where('layanan_kategori_id', $category1->id)->get();
        return view('categories.show-subcategory', compact('category', 'category1'));
    }

    public function deleteSubCategory(string $uuid)
    {
        if (auth()->user()->role != 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus sub-kategori.'
            ], 403);
        }

        DB::beginTransaction();

        try {
            $category = category::where('uuid', $uuid)->firstOrFail();

            $nama_kategori = $category->nama_kategori; // Simpan nama kategori untuk respon

            // Hapus data sub-kategori
            $category->delete();

            // Commit transaksi setelah penghapusan berhasil
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Sub-Kategori '{$nama_kategori}' berhasil dihapus.",
                'nama_kategori' => $nama_kategori
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Rollback transaksi jika data tidak ditemukan
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Sub-Kategori tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan lain
            DB::rollBack();
            Log::error('Error deleting sub-category: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus sub-kategori. Error: ' . $e->getMessage()
            ], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $category = category_layanan::where('uuid', $uuid)->firstOrFail();
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
        $category = category_layanan::where('uuid', $uuid)->firstOrFail();
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $uuid)
    {
        $category = category_layanan::where('uuid', $uuid)->firstOrFail();

        if ($category) {
            $category->update([
                'treatment_type' => $request->treatment_type,
                'description' => $request->description,
            ]);
        }

        if ($request->has('category')) {
            foreach ($request->category as $subKategoriData) {
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
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses untuk menghapus kategori.'], 403);
        }

        DB::beginTransaction();

        try {
            $category1 = category_layanan::where('uuid', $uuid)->firstOrFail();

            $category = category::where('layanan_kategori_id', $category1->id)->first();

            // Cek apakah kategori ini merupakan kategori utama dengan sub-kategori
            if ($category != null) {
                $hasChildCategories = category::where('layanan_kategori_id', $category1->id)->exists();

                if ($hasChildCategories) {
                    return response()->json(['success' => false, 'message' => 'Kategori ini memiliki sub-kategori. Hapus sub-kategori terlebih dahulu.'], 400);
                }
            }

            // Hapus data kategori
            $category1->delete();

            // Commit transaksi setelah penghapusan berhasil
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Kategori berhasil dihapus.'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Kategori tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus kategori.'], 500);
        }
    }

    public function activateCategory($uuid)
    {
        if (auth()->user()->role != 'superadmin') {
            return redirect()->route('kategori.index')->with('error', 'Anda tidak memiliki akses untuk mengaktifkan kategori.');
        }

        // Cari kategori berdasarkan UUID
        $category = category::where('uuid', $uuid)->firstOrFail();

        // Jika ini adalah kategori utama (parent_id null), aktifkan semua subkategori juga
        if ($category->parent_id === null) {
            // Aktifkan kategori utama
            $category->update(['status_kategori' => 'active']);

            // Aktifkan semua subkategori yang terkait dengan kategori utama ini
            category::where('parent_id', $category->id)->update(['status_kategori' => 'active']);
        } else {
            // Jika ini adalah subkategori, hanya aktifkan subkategori ini saja
            $category->update(['status_kategori' => 'active']);
        }

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diaktifkan.');
    }

    public function deactivateCategory($uuid)
    {
        if (auth()->user()->role != 'superadmin') {
            return redirect()->route('kategori.index')->with('error', 'Anda tidak memiliki akses untuk menonaktifkan kategori.');
        }

        // Cari kategori berdasarkan UUID
        $category = category::where('uuid', $uuid)->firstOrFail();

        // Jika ini adalah kategori utama (parent_id null), nonaktifkan semua subkategori juga
        if ($category->parent_id === null) {
            // Nonaktifkan kategori utama
            $category->update(['status_kategori' => 'nonactive']);

            // Nonaktifkan semua subkategori yang terkait dengan kategori utama ini
            category::where('parent_id', $category->id)->update(['status_kategori' => 'nonactive']);
        } else {
            // Jika ini adalah subkategori, hanya nonaktifkan subkategori ini saja
            $category->update(['status_kategori' => 'nonactive']);
        }

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dinonaktifkan.');
    }
}

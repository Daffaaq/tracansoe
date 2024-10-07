<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryBlogRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Models\CategoryBlog;
use Illuminate\Support\Facades\DB;

class CategoryBlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('category_blogs.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $dataPlusService = CategoryBlog::select("uuid", "name_category_blog", "slug")->get();
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
        return view('category_blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryBlogRequest $request)
    {
        // Create a new category
        CategoryBlog::create([
            'name_category_blog' => $request->name_category_blog,
        ]);

        return redirect()->route('kategori-blog.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        // Find category by UUID
        $category_blog = CategoryBlog::where('uuid', $uuid)->firstOrFail();

        // Show form to edit a category
        return view('category_blogs.edit', compact('category_blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryBlogRequest $request, string $uuid)
    {
        // Find category by UUID
        $category_blog = CategoryBlog::where('uuid', $uuid)->firstOrFail();
        // Update category
        $category_blog->update([
            'name_category_blog' => $request->name_category_blog,
        ]);

        return redirect()->route('kategori-blog.index')->with('success', 'Category updated successfully.');
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
            $category_blog = CategoryBlog::where('uuid', $uuid)->firstOrFail();
            $nama_kategori = $category_blog->name_category_blog;

            $category_blog->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Kategori Blog '{$nama_kategori}' berhasil dihapus."
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Kategori Blog tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus kategori blog.'], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\category;
use App\Models\promosi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
    public function landingPage()
    {
        // Get current date
        $currentDate = date('Y-m-d');

        // Query active promo
        $activePromo = promosi::where('start_date', '<=', $currentDate)
            ->where('end_date', '>=', $currentDate)
            ->where('status', 'active')
            ->first();

        // Query upcoming promos
        $upcomingPromos = promosi::where('start_date', '>', $currentDate)
            ->where('status', 'upcoming')
            ->get();

        // Query expired promos
        $expiredPromos = promosi::where('end_date', '<', $currentDate)
            ->where('status', 'expired')
            ->get();

        // Query categories including their subcategories
        $categories = category::with('subKriteria') // Ambil subkategori
            ->whereNull('parent_id') // Hanya ambil kategori induk
            ->get();

        $blog = Blog::with('category')->where('status_publish', 'published')->latest()->take(6)->get();
        // dd($blog);

        return view('LandingPage.index', compact('activePromo', 'upcomingPromos', 'expiredPromos', 'categories', 'blog'));
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kategori Induk
        $category_sepatus = DB::table('category_sepatus')->insert([
            'category_sepatu' => 'Berwarna'
             
        ]);
        $category_sepatus2 = DB::table('category_sepatus')->insert([
            'category_sepatu' => 'Putih'
             
        ]);
        $fastCleaningId = DB::table('categories')->insertGetId([
            'uuid' => Str::uuid(),
            'nama_kategori' => 'Fast Cleaning',
            'price' => null,
            'description' => 'Kategori untuk layanan pembersihan cepat',
            'estimation' => null,
            'parent_id' => null,
            'status_kategori' => 'active', // Status active
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $deepCleaningId = DB::table('categories')->insertGetId([
            'uuid' => Str::uuid(),
            'nama_kategori' => 'Deep Cleaning',
            'price' => null,
            'description' => 'Kategori untuk layanan pembersihan mendalam',
            'estimation' => null,
            'parent_id' => null,
            'status_kategori' => 'active', // Status active
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $repaintId = DB::table('categories')->insertGetId([
            'uuid' => Str::uuid(),
            'nama_kategori' => 'Repaint',
            'price' => null,
            'description' => 'Kategori untuk layanan restorasi warna sepatu',
            'estimation' => null,
            'parent_id' => null,
            'status_kategori' => 'active', // Status active
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Sub-kategori untuk Fast Cleaning
        DB::table('categories')->insert([
            [
                'uuid' => Str::uuid(),
                'nama_kategori' => 'Fast Cleaning Reguler',
                'price' => 30000,
                'description' => 'Pencucian instant pada bagian Upper dan Midsole',
                'estimation' => 1,
                'parent_id' => $fastCleaningId,
                'status_kategori' => 'active', // Status active
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'nama_kategori' => 'Fast Cleaning Outsole',
                'price' => 50000,
                'description' => 'Pencucian instant pada bagian Upper, Midsole, dan Outsole',
                'estimation' => 1,
                'parent_id' => $fastCleaningId,
                'status_kategori' => 'active', // Status active
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Sub-kategori untuk Deep Cleaning
        DB::table('categories')->insert([
            [
                'uuid' => Str::uuid(),
                'nama_kategori' => 'Deep Cleaning Mid',
                'description' => 'Perawatan pembersihan secara detail dan menyeluruh pada tingkat menengah.',
                'price' => 60000,
                'estimation' => 3,
                'parent_id' => $deepCleaningId,
                'status_kategori' => 'active', // Status active
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'nama_kategori' => 'Deep Cleaning Reguler',
                'description' => 'Perawatan pembersihan secara detail dan menyeluruh.',
                'price' => 80000,
                'estimation' => 3,
                'parent_id' => $deepCleaningId,
                'status_kategori' => 'active', // Status active
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'nama_kategori' => 'Deep Cleaning Hard',
                'description' => 'Perawatan pembersihan secara mendalam.',
                'price' => 160000,
                'estimation' => 4,
                'parent_id' => $deepCleaningId,
                'status_kategori' => 'active', // Status active
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Sub-kategori untuk Repaint
        DB::table('categories')->insert([
            [
                'uuid' => Str::uuid(),
                'nama_kategori' => 'Repaint Soft',
                'description' => 'Perawatan restorasi warna ringan.',
                'price' => 200000,
                'estimation' => 5,
                'parent_id' => $repaintId,
                'status_kategori' => 'active', // Status active
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'nama_kategori' => 'Repaint Medium',
                'description' => 'Perawatan restorasi warna tingkat menengah.',
                'price' => 250000,
                'estimation' => 6,
                'parent_id' => $repaintId,
                'status_kategori' => 'active', // Status active
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'nama_kategori' => 'Repaint Hard',
                'description' => 'Perawatan restorasi warna intensif.',
                'price' => 300000,
                'estimation' => 7,
                'parent_id' => $repaintId,
                'status_kategori' => 'active', // Status active
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


        // DB::table('categories')->insert([
        //     [
        //         'uuid' => Str::uuid(),
        //         'nama_kategori' => 'Fast Cleaning Reguler',
        //         'price' => 30000,
        //         'description' => 'Pencucian instant pada bagian Upper dan Midsole',
        //         'estimation' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'uuid' => Str::uuid(),
        //         'nama_kategori' => 'Fast Cleaning Outsole',
        //         'price' => 50000,
        //         'description' => 'Pencucian instant pada bagian Upper, Midsole, dan Outsole',
        //         'estimation' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'uuid' => Str::uuid(),
        //         'nama_kategori' => 'Deep Cleaning Mid',
        //         'description' => 'Perawatan pembersihan secara detail dan menyeluruh pada tingkat menengah dengan fokus pada area yang sering terlewat.',
        //         'price' => 60000,
        //         'estimation' => 3,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'uuid' => Str::uuid(),
        //         'nama_kategori' => 'Deep Cleaning Reguler',
        //         'description' => 'Perawatan pembersihan secara detail dan menyeluruh dengan fokus pada area standar yang membutuhkan perhatian rutin.',
        //         'price' => 80000,
        //         'estimation' => 3,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'uuid' => Str::uuid(),
        //         'nama_kategori' => 'Deep Cleaning Hard',
        //         'description' => 'Perawatan pembersihan secara mendalam dengan fokus pada area sulit yang membutuhkan tenaga ekstra dan alat khusus.',
        //         'price' => 160000,
        //         'estimation' => 4,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'uuid' => Str::uuid(),
        //         'nama_kategori' => 'Premium Treatment',
        //         'description' => 'Perawatan yang di tunjukkan untuk material khusus dalam pengerjaannya dan menggunakan bahan khusus dalam setiap produknya',
        //         'price' => 100000,
        //         'estimation' => 5,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'uuid' => Str::uuid(),
        //         'nama_kategori' => 'Unyellow and Whitening',
        //         'description' => 'Perawatan pada bagian midsole yang telah menguning untuk mengilangkan warna kuning menjadi semula tanpa harus repaint',
        //         'price' => 120000,
        //         'estimation' => 6,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'uuid' => Str::uuid(),
        //         'nama_kategori' => 'Repaint Soft',
        //         'description' => 'Perawatan restorasi warna ringan dengan fokus pada area kecil atau bagian yang memudar secara minimal, menggunakan cat khusus untuk mengembalikan warna asli sepatu.',
        //         'price' => 200000,
        //         'estimation' => 5,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'uuid' => Str::uuid(),
        //         'nama_kategori' => 'Repaint Medium',
        //         'description' => 'Perawatan restorasi warna tingkat menengah dengan penanganan pada area yang lebih luas, menggunakan cat khusus untuk mengembalikan warna asli sepatu secara menyeluruh.',
        //         'price' => 250000,
        //         'estimation' => 6,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'uuid' => Str::uuid(),
        //         'nama_kategori' => 'Repaint Hard',
        //         'description' => 'Perawatan restorasi warna intensif dengan fokus pada seluruh bagian sepatu atau area yang mengalami kerusakan parah, menggunakan cat khusus untuk mengembalikan warna asli sepatu.',
        //         'price' => 300000,
        //         'estimation' => 7,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        // ]);
    }
}

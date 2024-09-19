<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PlusServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plus_services')->insert([
            [
                'uuid' => Str::uuid(),
                'name' => 'Pewangi Sepatu',
                'price' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Anti Air',
                'price' => 20000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Pembersih Kulit',
                'price' => 15000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

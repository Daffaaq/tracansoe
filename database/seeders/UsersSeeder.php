<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password123'), // Make sure to hash the password
            'role' => 'superadmin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert a karyawan user
        DB::table('users')->insert([
            'name' => 'Ache',
            'email' => 'ache@gmail.com',
            'password' => Hash::make('password123'), // Make sure to hash the password
            'role' => 'karyawan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'name' => 'Melati',
            'email' => 'melati@gmail.com',
            'password' => Hash::make('password123'), // Make sure to hash the password
            'role' => 'karyawan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

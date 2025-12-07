<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Import model User

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menambahkan data admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'), // Password admin yang sudah di-hash
            'role' => 'admin', // Menambahkan role 'admin'
        ]);

        // Menambahkan data user biasa
        User::create([
            'name' => 'Mama',
            'email' => 'mama@gmail.com',
            'password' => Hash::make('mama'), // Password user biasa yang sudah di-hash
            'role' => 'user', // Menambahkan role 'user'
        ]);

        // Menambahkan data dokter
        User::create([
            'name' => 'Dokter 1',
            'email' => 'dokter1@gmail.com',
            'password' => Hash::make('dokter1'), // Password dokter yang sudah di-hash
            'role' => 'dokter', // Menambahkan role 'dokter'
        ]);
        User::create([
            'name' => 'Dokter 2',
            'email' => 'dokter2@gmail.com',
            'password' => Hash::make('dokter2'), // Password dokter yang sudah di-hash
            'role' => 'dokter', // Menambahkan role 'dokter'
        ]);
    }
}

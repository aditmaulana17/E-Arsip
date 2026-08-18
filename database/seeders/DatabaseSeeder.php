<?php

namespace Database\Seeders;

use App\Models\Instansi;
use App\Models\KategoriSurat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Data User (Mengecek berdasarkan email agar tidak duplikat)
        User::firstOrCreate(
            ['email' => 'admin@arsipsurat.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'jabatan' => 'Kepala Tata Usaha',
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff@arsipsurat.test'],
            [
                'name' => 'Staff Arsip',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'jabatan' => 'Staff Administrasi',
            ]
        );

        // 2. Seed Data Kategori Surat
        $kategoris = [
            ['nama_kategori' => 'Surat Undangan', 'kode' => 'UND', 'sifat' => 'biasa'],
            ['nama_kategori' => 'Surat Keputusan', 'kode' => 'SK', 'sifat' => 'penting'],
            ['nama_kategori' => 'Surat Edaran', 'kode' => 'SE', 'sifat' => 'biasa'],
            ['nama_kategori' => 'Surat Perjanjian Kerjasama', 'kode' => 'MOU', 'sifat' => 'rahasia'],
            ['nama_kategori' => 'Surat Permohonan', 'kode' => 'PER', 'sifat' => 'biasa'],
        ];

        foreach ($kategoris as $k) {
            KategoriSurat::firstOrCreate(
                ['kode' => $k['kode']], // Cek unik berdasarkan kode
                $k
            );
        }

        // 3. Seed Data Instansi
        $instansis = [
            ['nama_instansi' => 'Dinas Pendidikan Kabupaten', 'jenis' => 'eksternal', 'email' => 'disdik@pemkab.go.id'],
            ['nama_instansi' => 'PT Maju Bersama', 'jenis' => 'eksternal', 'email' => 'info@majubersama.co.id'],
            ['nama_instansi' => 'Bagian Keuangan Internal', 'jenis' => 'internal', 'email' => 'keuangan@internal.local'],
            ['nama_instansi' => 'Kantor Kecamatan', 'jenis' => 'eksternal', 'email' => 'kecamatan@pemkab.go.id'],
        ];

        foreach ($instansis as $i) {
            Instansi::firstOrCreate(
                ['nama_instansi' => $i['nama_instansi']], // Cek unik berdasarkan nama instansi
                $i
            );
        }
    }
}
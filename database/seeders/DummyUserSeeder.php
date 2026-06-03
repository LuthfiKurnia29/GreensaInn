<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin - Internal
        User::updateOrCreate(
            ['email' => 'admin.internal@greensainn.com'],
            [
                'nama_lengkap' => 'Admin Internal UINSA',
                'username' => 'admin_internal',
                'nomor_telepon' => '081234567890',
                'role' => 'admin',
                'instansi' => 'internal',
                'password' => Hash::make('password123')
            ]
        );

        // Admin - Umum
        User::updateOrCreate(
            ['email' => 'admin.umum@greensainn.com'],
            [
                'nama_lengkap' => 'Admin Umum Cabang',
                'username' => 'admin_umum',
                'nomor_telepon' => '081234567891',
                'role' => 'admin',
                'instansi' => 'umum',
                'password' => Hash::make('password123')
            ]
        );

        // Peminjam - Internal
        User::updateOrCreate(
            ['email' => 'peminjam.internal@greensainn.com'],
            [
                'nama_lengkap' => 'Luthfi Mahasiswa',
                'username' => 'peminjam_internal',
                'nomor_telepon' => '081234567892',
                'role' => 'peminjam',
                'instansi' => 'internal',
                'password' => Hash::make('password123')
            ]
        );

        User::updateOrCreate(
            ['email' => 'peminjam.internal2@greensainn.com'],
            [
                'nama_lengkap' => 'Pak Dosen',
                'username' => 'peminjam_internal2',
                'nomor_telepon' => '086172819991',
                'role' => 'peminjam',
                'instansi' => 'internal',
                'password' => Hash::make('password123')
            ]
        );

        // Peminjam - Umum
        User::updateOrCreate(
            ['email' => 'peminjam.umum@greensainn.com'],
            [
                'nama_lengkap' => 'Budi Perusahaan Umum',
                'username' => 'peminjam_umum',
                'nomor_telepon' => '081234567893',
                'role' => 'peminjam',
                'instansi' => 'umum',
                'password' => Hash::make('password123')
            ]
        );
    }
}

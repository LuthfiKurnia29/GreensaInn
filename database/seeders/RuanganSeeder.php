<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        $ruangans = [
            [
                'id' => 1,
                'nama_ruangan' => 'Emerald Executive Boardroom',
                'kapasitas' => 20,
                'lokasi_ruangan' => 'Lantai 3',
                'deskripsi' => 'Ruang rapat eksklusif dengan desain elegan dan kursi ergonomis mewah untuk keputusan penting bisnis Anda.',
                'status_tersedia' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nama_ruangan' => 'Creative Hub & Jam Space',
                'kapasitas' => 12,
                'lokasi_ruangan' => 'Lantai 2',
                'deskripsi' => 'Ruang kolaborasi dengan atmosfer kasual dan penuh warna untuk memicu ide-ide kreatif tim Anda.',
                'status_tersedia' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nama_ruangan' => 'Synergy Seminar Hall',
                'kapasitas' => 60,
                'lokasi_ruangan' => 'Lantai 1',
                'deskripsi' => 'Ruangan berskala besar dengan panggung kecil dan sound system lengkap untuk seminar, pelatihan, atau workshop.',
                'status_tersedia' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nama_ruangan' => 'Huddle Pod Room 4A',
                'kapasitas' => 4,
                'lokasi_ruangan' => 'Lantai 4',
                'deskripsi' => 'Ruang diskusi kecil privat yang kedap suara, cocok untuk wawancara, meeting 1-on-1, atau panggilan video.',
                'status_tersedia' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($ruangans as $ruangan) {
            DB::table('ruangans')->updateOrInsert(
                ['id' => $ruangan['id']],
                $ruangan
            );
        }
    }
}

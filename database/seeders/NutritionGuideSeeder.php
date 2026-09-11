<?php

namespace Database\Seeders;

use App\Models\NutritionGuide;
use App\Models\User;
use Illuminate\Database\Seeder;

class NutritionGuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari dokter untuk dikaitkan dengan panduan (misal Dr. Boyke atau dr pertama yang ada)
        $doctor1 = User::where('role', 'dokter')->first();
        $doctor2 = User::where('role', 'dokter')->skip(1)->first() ?? $doctor1;

        if (!$doctor1) {
            $this->command->info('Belum ada dokter di database. Silakan jalankan DatabaseSeeder utama terlebih dahulu.');
            return;
        }

        $guides = [
            [
                'doctor_id' => $doctor1->id,
                'title' => 'Asam Folat Tinggi (Bayam, Brokoli)',
                'type' => 'Rekomendasi',
                'trimester' => '1',
                'description' => 'Sangat penting untuk mencegah cacat tabung saraf pada janin. Trimester 1 adalah masa pembentukan organ krusial.',
            ],
            [
                'doctor_id' => $doctor2->id,
                'title' => 'Daging, Telur, dan Ikan Mentah',
                'type' => 'Pantangan',
                'trimester' => 'Umum',
                'description' => 'Berisiko mengandung bakteri Listeria, Salmonella, atau parasit Toksoplasma yang dapat menyebabkan keguguran atau cacat lahir. Semua daging harus dimasak matang sempurna.',
            ],
            [
                'doctor_id' => $doctor1->id,
                'title' => 'Kalsium (Susu, Keju Pasteurisasi)',
                'type' => 'Rekomendasi',
                'trimester' => '2',
                'description' => 'Tulang dan gigi janin berkembang pesat di trimester ini. Kalsium mencegah osteoporosis pada ibu dan memaksimalkan pertumbuhan tulang janin.',
            ],
            [
                'doctor_id' => $doctor2->id,
                'title' => 'Kopi / Kafein Berlebih',
                'type' => 'Pantangan',
                'trimester' => 'Umum',
                'description' => 'Maksimal konsumsi kafein adalah 200mg (sekitar 1-2 cangkir) per hari. Kafein berlebih dapat menembus plasenta dan memengaruhi detak jantung janin serta meningkatkan risiko berat lahir rendah.',
            ],
            [
                'doctor_id' => $doctor1->id,
                'title' => 'Makanan Kaya Zat Besi (Daging Merah Tanpa Lemak)',
                'type' => 'Rekomendasi',
                'trimester' => '3',
                'description' => 'Dibutuhkan untuk mencegah anemia pada ibu menjelang persalinan. Volume darah ibu meningkat tajam, sehingga butuh asupan besi ekstra.',
            ],
            [
                'doctor_id' => $doctor2->id,
                'title' => 'Makanan Asin & Tinggi Natrium (Junk Food)',
                'type' => 'Pantangan',
                'trimester' => '3',
                'description' => 'Trimester 3 rawan terjadi bengkak (edema) pada kaki. Terlalu banyak garam memperparah penumpukan cairan dan meningkatkan risiko tekanan darah tinggi (Preeklampsia).',
            ]
        ];

        foreach ($guides as $guide) {
            NutritionGuide::create($guide);
        }

        $this->command->info('Nutrition Guides berhasil di-seed!');
    }
}

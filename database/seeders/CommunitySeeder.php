<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CommunityPost;
use App\Models\CommunityComment;
use App\Models\CommunityLike;
use Illuminate\Support\Facades\Hash;

class CommunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Buat beberapa user dummy jika belum ada
        $dokter = User::firstOrCreate(
            ['email' => 'dokter.komunitas@mamacare.com'],
            [
                'name' => 'dr. Anita Larasati, Sp.A',
                'password' => Hash::make('password123'),
                'role' => 'dokter',
            ]
        );

        $mama1 = User::firstOrCreate(
            ['email' => 'mama.siti@mamacare.com'],
            [
                'name' => 'Siti Aisyah',
                'password' => Hash::make('password123'),
                'role' => 'mama',
            ]
        );

        $mama2 = User::firstOrCreate(
            ['email' => 'mama.rini@mamacare.com'],
            [
                'name' => 'Rini Wulandari',
                'password' => Hash::make('password123'),
                'role' => 'mama',
            ]
        );

        // 2. Buat Postingan Pertama dari Mama
        $post1 = CommunityPost::create([
            'user_id' => $mama1->id,
            'title' => 'Anak GTM di usia 8 bulan, gimana ya?',
            'content' => 'Halo Bunda semua. Anakku sekarang umur 8 bulan, udah 3 hari ini GTM (Gerakan Tutup Mulut) tiap disuapin MPASI. Padahal menu udah divariasiin. Ada yang punya pengalaman atau tips jitu gak ya buat ngatasinnya? Sedih banget liat berat badannya takut turun. 🥺',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        // Komentar untuk Postingan Pertama
        CommunityComment::create([
            'user_id' => $mama2->id,
            'community_post_id' => $post1->id,
            'content' => 'Semangat ya Bun Siti! Anakku dulu juga gitu pas tumgi (tumbuh gigi). Coba kasih makanan yang agak dingin atau teksturnya dibikin lebih halus sedikit.',
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        CommunityComment::create([
            'user_id' => $dokter->id,
            'community_post_id' => $post1->id,
            'content' => 'Halo Ibu Siti. Fase GTM wajar terjadi pada bayi usia 8 bulan, seringkali karena sedang tumbuh gigi, tidak enak badan, atau bosan dengan menu/tekstur. Coba terapkan aturan makan (feeding rules): jadwal teratur, batasi waktu makan maksimal 30 menit, dan ciptakan suasana menyenangkan. Jika lebih dari seminggu berat badan tidak naik, silakan periksa ke faskes terdekat ya Bu.',
            'created_at' => now()->subHours(10),
            'updated_at' => now()->subHours(10),
        ]);

        // Like untuk Postingan Pertama
        CommunityLike::create(['user_id' => $mama2->id, 'community_post_id' => $post1->id]);
        CommunityLike::create(['user_id' => $dokter->id, 'community_post_id' => $post1->id]);

        // 3. Buat Postingan Kedua dari Dokter (Edukasi)
        $post2 = CommunityPost::create([
            'user_id' => $dokter->id,
            'title' => 'Pentingnya Imunisasi Dasar Lengkap 💉',
            'content' => 'Selamat pagi para Bunda! Jangan lupa cek kembali buku KIA-nya ya. Imunisasi dasar sangat penting untuk mencegah penyakit berbahaya seperti Polio, Campak, dan TBC. Pastikan buah hati mendapatkan imunisasi sesuai jadwal usianya. Lebih baik mencegah daripada mengobati. Sehat selalu untuk si Kecil!',
            'created_at' => now()->subHours(5),
            'updated_at' => now()->subHours(5),
        ]);

        // Komentar untuk Postingan Kedua
        CommunityComment::create([
            'user_id' => $mama1->id,
            'community_post_id' => $post2->id,
            'content' => 'Terima kasih pengingatnya, Dok! Kebetulan minggu depan jadwal dedek imunisasi DPT.',
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(2),
        ]);

        // Like untuk Postingan Kedua
        CommunityLike::create(['user_id' => $mama1->id, 'community_post_id' => $post2->id]);
        CommunityLike::create(['user_id' => $mama2->id, 'community_post_id' => $post2->id]);
        
        // 4. Buat Postingan Ketiga (Singkat)
        $post3 = CommunityPost::create([
            'user_id' => $mama2->id,
            'title' => 'Rekomendasi Skincare Bayi untuk Kulit Sensitif',
            'content' => 'Moms, minta rekomendasi cream atau lotion untuk kulit bayi yang gampang bruntusan dong. Udah coba merk A malah tambah merah-merah. Bantu share pengalamannya ya! 🙏',
            'created_at' => now()->subMinutes(30),
            'updated_at' => now()->subMinutes(30),
        ]);
    }
}

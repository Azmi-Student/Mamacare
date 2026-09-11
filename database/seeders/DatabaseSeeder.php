<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Article;
use App\Models\KehamilanMama;
use App\Models\Appointment;
use App\Models\Conversation;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. DATA PENGGUNA (USER & DOKTER)
        // ==========================================

        // 1. User Mama
        $mama = User::create([
            'name' => 'Bunda Jule',
            'email' => 'mama@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'mama',
        ]);

        // 2. User Admin (Cuma buat kelola sistem, TIDAK NULIS ARTIKEL)
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 3. Dr. Boyke
        $dokter1 = User::create([
            'name' => 'Dr. Boyke Dian',
            'email' => 'dokter@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'dokter',
        ]);

        $profilDokter1 = Doctor::create([
            'user_id' => $dokter1->id,
            'name' => 'Dr. Boyke Dian, Sp.OG',
            'specialist' => 'Spesialis Kandungan',
            'phone_number' => '081234567890',
            'experience' => 15,
            'image' => 'https://ui-avatars.com/api/?name=Boyke+Dian&background=C21B75&color=fff&size=128', 
            'description' => 'Dokter senior yang sangat humoris dan detail dalam menjelaskan.'
        ]);

        // 4. Dr. Aisah
        $dokter2 = User::create([
            'name' => 'Dr. Aisah Putri',
            'email' => 'aisah@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'dokter',
        ]);

        $profilDokter2 = Doctor::create([
            'user_id' => $dokter2->id,
            'name' => 'Dr. Aisah Putri, Sp.OG',
            'specialist' => 'Dokter Kandungan',
            'phone_number' => '089876543210',
            'experience' => 8,
            'image' => 'https://ui-avatars.com/api/?name=Aisah+Putri&background=FF3EA5&color=fff&size=128',
            'description' => 'Sangat keibuan, sabar mendengarkan keluhan, dan pro-persalinan normal.'
        ]);

        // 5. Bidan Siti
        $dokter3 = User::create([
            'name' => 'Bidan Siti',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'dokter',
        ]);

        $profilDokter3 = Doctor::create([
            'user_id' => $dokter3->id,
            'name' => 'Bidan Siti Aminah, S.Tr.Keb',
            'specialist' => 'Bidan Sahabat Ibu',
            'phone_number' => '085566778899',
            'experience' => 5,
            'image' => 'https://ui-avatars.com/api/?name=Siti+Aminah&background=FF90C8&color=fff&size=128',
            'description' => 'Bidan muda yang cekatan, telaten mengurus baby, dan fokus pada relaksasi ibu hamil.'
        ]);

        // ==========================================
        // 2. DATA ARTIKEL (LENGKAP SEMUA KATEGORI)
        // ==========================================
        
        // --- ARTIKEL HARIAN (Oleh Bidan Siti) ---
        Article::create([
            'user_id' => $dokter3->id,
            'title' => 'Rutinitas Pagi Bunda Hamil agar Tetap Fit',
            'slug' => Str::slug('Rutinitas Pagi Bunda Hamil agar Tetap Fit') . '-' . time(),
            'category' => 'Harian',
            'excerpt' => 'Membangun kebiasaan pagi yang baik sangat berpengaruh pada mood dan kesehatan Bunda seharian.',
            'content' => '
                <p>Halo Bunda! Tahukah Bunda bahwa apa yang kita lakukan dalam 1 jam pertama setelah bangun tidur menentukan tingkat energi kita seharian?</p>
                <h3>Tips Pagi Hari:</h3>
                <ul>
                    <li><strong>Minum Air Putih:</strong> Segera setelah bangun, minum segelas air suhu ruang untuk mengaktifkan organ tubuh.</li>
                    <li><strong>Peregangan Ringan:</strong> Lakukan stretching leher dan bahu selama 5 menit untuk melancarkan aliran darah.</li>
                    <li><strong>Sinar Matahari:</strong> Usahakan terkena sinar matahari pagi pukul 07.00 - 08.00 untuk asupan Vitamin D alami.</li>
                </ul>
                <p>Jangan lupa sarapan protein tinggi ya, Bun, seperti telur atau kacang-kacangan.</p>
            ',
            'image' => null,
            'status' => 'published',
        ]);

        // --- ARTIKEL MINGGUAN (Oleh Dr. Aisah) ---
        Article::create([
            'user_id' => $dokter2->id,
            'title' => 'Checklist Mingguan: Apa yang Harus Dipantau?',
            'slug' => Str::slug('Checklist Mingguan Apa yang Harus Dipantau') . '-' . time(),
            'category' => 'Mingguan',
            'excerpt' => 'Setiap minggu ada perkembangan baru bagi janin. Pantau berat badan dan gerakan si kecil secara berkala.',
            'content' => '
                <p>Minggu demi minggu berlalu, si kecil di dalam perut tumbuh dengan kecepatan yang luar biasa. Berikut adalah hal-hal yang harus Bunda perhatikan setiap minggunya:</p>
                <h2>1. Pantau Gerakan Janin</h2>
                <p>Mulai masuk minggu ke-20, si kecil sudah mulai aktif menendang. Pastikan Bunda merasakan minimal 10 gerakan dalam waktu 2 jam saat si kecil sedang aktif.</p>
                <h2>2. Ukur Lingkar Perut & Berat Badan</h2>
                <p>Kenaikan berat badan yang ideal adalah sekitar 0.5kg per minggu pada trimester kedua dan ketiga. Jangan diet ya, Bun, fokuslah pada nutrisi!</p>
                <blockquote>Tips Dr. Aisah: Jika Bunda merasakan tekanan darah tinggi atau bengkak pada wajah, segera hubungi kami.</blockquote>
            ',
            'image' => null,
            'status' => 'published',
        ]);

        // --- ARTIKEL BULANAN (Oleh Dr. Boyke) ---
        Article::create([
            'user_id' => $dokter1->id,
            'title' => 'Agenda Kontrol Dokter Bulanan yang Wajib Bunda Tahu',
            'slug' => Str::slug('Agenda Kontrol Dokter Bulanan yang Wajib Bunda Tahu') . '-' . time(),
            'category' => 'Bulanan',
            'excerpt' => 'Jangan lewatkan jadwal USG dan pemeriksaan laboratorium setiap bulannya untuk memastikan tumbuh kembang janin maksimal.',
            'content' => '
                <p>Banyak Bunda yang bertanya, "Kenapa sih Dok harus kontrol tiap bulan kalau saya merasa sehat-sehat saja?". Nah, pemeriksaan bulanan itu bukan cuma soal lihat bayi lewat USG lho.</p>
                <h3>Yang Diperiksa Setiap Bulan:</h3>
                <ul>
                    <li><strong>Detak Jantung Janin (DJJ):</strong> Memastikan irama jantung si kecil normal.</li>
                    <li><strong>Cek Urine & Darah:</strong> Mendeteksi risiko anemia atau infeksi saluran kemih yang sering tidak terasa gejalanya.</li>
                    <li><strong>Posisi Plasenta:</strong> Memantau agar plasenta tidak menutupi jalan lahir.</li>
                </ul>
                <p>Biasanya pada kontrol bulan ke-5, kita sudah bisa mengintip jenis kelamin si kecil jika posisinya pas!</p>
            ',
            'image' => null,
            'status' => 'published',
        ]);

        // --- ARTIKEL TRIMESTER 1 (Oleh Dr. Aisah) ---
        Article::create([
            'user_id' => $dokter2->id,
            'title' => 'Mencegah Dehidrasi Saat Morning Sickness di Trimester 1',
            'slug' => Str::slug('Mencegah Dehidrasi Saat Morning Sickness Trimester 1') . '-' . time(),
            'category' => 'Trimester 1',
            'excerpt' => 'Mual muntah berlebih bisa berbahaya jika Bunda kurang cairan. Yuk, belajar cara minum yang benar.',
            'content' => '
                <p>Trimester pertama adalah fase perjuangan bagi banyak Bunda. Mual muntah memang normal, tapi jangan sampai Bunda terkena dehidrasi.</p>
                <h2>Tanda Dehidrasi:</h2>
                <p>Jika urine Bunda berwarna sangat kuning pekat atau mulut terasa sangat kering, itu tandanya tubuh kekurangan cairan.</p>
                <h3>Cara Minum agar Tidak Mual:</h3>
                <ol>
                    <li>Jangan minum sekaligus banyak. Gunakan metode <strong>"Little and Often"</strong> (sedikit-sedikit tapi sering).</li>
                    <li>Gunakan sedotan untuk membantu air langsung masuk ke tenggorokan tanpa menyentuh lidah bagian belakang yang sensitif.</li>
                    <li>Tambahkan irisan lemon atau daun mint segar pada air minum Bunda.</li>
                </ol>
            ',
            'image' => null,
            'status' => 'published',
        ]);

        // --- ARTIKEL TRIMESTER 2 (Oleh Dr. Boyke) ---
        Article::create([
            'user_id' => $dokter1->id,
            'title' => 'Trimester Kedua: Waktu Terbaik untuk Babymoon!',
            'slug' => Str::slug('Trimester Kedua Waktu Terbaik untuk Babymoon') . '-' . time(),
            'category' => 'Trimester 2',
            'excerpt' => 'Energi Bunda kembali pulih! Manfaatkan waktu ini untuk berolahraga ringan atau jalan-jalan santai bersama Ayah.',
            'content' => '
                <p>Trimester kedua sering disebut sebagai <strong>Golden Age</strong> dalam kehamilan. Mengapa? Karena mual biasanya sudah hilang dan perut belum terlalu berat.</p>
                <h2>Nikmati Waktu Bunda:</h2>
                <p>Ini adalah waktu yang paling aman untuk melakukan perjalanan udara atau darat (Babymoon). Tapi pastikan konsultasi ke saya dulu ya untuk cek kondisi mulut rahim.</p>
                <h3>Olahraga yang Disarankan:</h3>
                <ul>
                    <li>Prenatal Yoga: Untuk melenturkan otot panggul.</li>
                    <li>Berenang: Mengurangi beban pada tulang belakang Bunda.</li>
                    <li>Jalan santai: Memperbaiki sirkulasi darah.</li>
                </ul>
            ',
            'image' => null,
            'status' => 'published',
        ]);

        // --- ARTIKEL TRIMESTER 3 (Oleh Bidan Siti) ---
        Article::create([
            'user_id' => $dokter3->id,
            'title' => 'Menyiapkan Tas Persalinan (Hospital Bag) di Trimester 3',
            'slug' => Str::slug('Menyiapkan Tas Persalinan Trimester 3') . '-' . time(),
            'category' => 'Trimester 3',
            'excerpt' => 'Persalinan bisa terjadi kapan saja di akhir trimester ini. Pastikan tas perlengkapan Bunda dan bayi sudah siap di dekat pintu.',
            'content' => '
                <p>Memasuki minggu ke-36, Bunda harus sudah "siaga 1". Jangan sampai saat ketuban pecah, Bunda masih sibuk cari baju ganti.</p>
                <h3>Isi Tas untuk Bunda:</h3>
                <ul>
                    <li>Daster atau baju kancing depan (mudah untuk IMD/Menyusui).</li>
                    <li>Kain jarik atau sarung.</li>
                    <li>Pembalut nifas ukuran besar.</li>
                    <li>Dokumen (KTP, Buku KIA, Kartu Asuransi/BPJS).</li>
                </ul>
                <h3>Isi Tas untuk Bayi:</h3>
                <ul>
                    <li>Baju bayi & popok kain.</li>
                    <li>Bedong & selimut hangat.</li>
                    <li>Minyak telon & tisu basah non-alkohol.</li>
                </ul>
                <p>Semangat ya Bun, sebentar lagi kita bertemu si Kecil!</p>
            ',
            'image' => null,
            'status' => 'published',
        ]);

        // ==========================================
        // 3. DATA FITUR INTERAKTIF (Bunda Jule)
        // ==========================================
        
        // --- A. Profil Kehamilan ---
        // HPHT diset 4 bulan yang lalu agar pas masuk trimester 2
        KehamilanMama::create([
            'user_id' => $mama->id,
            'hpht' => Carbon::now()->subMonths(4)->format('Y-m-d'),
            'ai_data' => null,
        ]);

        // --- B. Data Reservasi (Appointment & Rekap) ---
        // 1. Rekap Medis Masa Lalu (Selesai)
        Appointment::create([
            'user_id' => $mama->id,
            'doctor_id' => $profilDokter1->id, // Dr. Boyke
            'date' => Carbon::now()->subDays(10)->format('Y-m-d'),
            'time' => '10:00',
            'notes' => 'Bunda merasa sedikit kram di perut bagian bawah saat bangun tidur.',
            'diagnosis' => 'Kram perut normal akibat peregangan otot rahim (Round Ligament Pain). Kondisi janin sangat sehat dan aktif.',
            'prescription' => "1. Vitamin C 500mg (1x sehari)\n2. Kalsium (1x sehari)\n3. Banyak istirahat dan kurangi naik turun tangga.",
            'image' => null,
            'status' => 'completed',
        ]);

        // 2. Jadwal Mendatang (Pending)
        Appointment::create([
            'user_id' => $mama->id,
            'doctor_id' => $profilDokter2->id, // Dr. Aisah
            'date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'time' => '15:30',
            'notes' => 'Jadwal kontrol rutin bulanan dan ingin cek jenis kelamin.',
            'diagnosis' => null,
            'prescription' => null,
            'image' => null,
            'status' => 'pending',
        ]);

        // --- C. Data Chat (Conversation & Messages) ---
        $chat = Conversation::create([
            'user_id' => $mama->id,
            'doctor_id' => $profilDokter1->id,
        ]);

        Message::create([
            'conversation_id' => $chat->id,
            'sender_type' => 'user',
            'message' => 'Halo Dok, selamat pagi. Saya mau tanya, kalau ibu hamil trimester 2 boleh makan durian tidak ya? Soalnya lagi kepengen banget.',
            'is_read' => true,
            'created_at' => Carbon::now()->subHours(2),
        ]);

        Message::create([
            'conversation_id' => $chat->id,
            'sender_type' => 'doctor',
            'message' => 'Halo Bunda Jule! Boleh saja kok makan durian, asalkan jangan berlebihan ya. Maksimal 2-3 potong kecil saja, karena durian mengandung gula dan gas yang tinggi.',
            'is_read' => true,
            'created_at' => Carbon::now()->subHours(1)->subMinutes(45),
        ]);

        Message::create([
            'conversation_id' => $chat->id,
            'sender_type' => 'user',
            'message' => 'Syukurlah, terima kasih banyak Dok penjelasannya! Nanti saya batasi porsinya.',
            'is_read' => false,
            'created_at' => Carbon::now()->subMinutes(10),
        ]);

        // ==========================================
        // 4. JALANKAN SEEDER LAINNYA
        // ==========================================
        $this->call([
            CommunitySeeder::class,
            NutritionGuideSeeder::class,
        ]);
    }
}
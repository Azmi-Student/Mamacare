<p align="center">
  <img src="public/images/logo-icon.png" width="100" alt="Mamacare Logo">
</p>

<h1 align="center">Mamacare V2</h1>

<p align="center">
  Platform asisten kehamilan cerdas berbasis web yang menghubungkan ibu hamil (Mama) dengan tenaga medis profesional (Dokter & Bidan). Dilengkapi dengan integrasi Artificial Intelligence (AI) untuk membantu menganalisis keluhan dan nutrisi secara <i>real-time</i>.
</p>

---

## 📌 Daftar Isi
1. [Tentang Mamacare](#-tentang-mamacare)
2. [Fitur Utama](#-fitur-utama)
   - [Akses Mama](#akses-mama)
   - [Akses Dokter](#akses-dokter)
   - [Akses Admin](#akses-admin)
3. [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
4. [Panduan Instalasi](#-panduan-instalasi)
5. [Konfigurasi API](#-konfigurasi-api)
6. [Data Akun Uji Coba](#-data-akun-uji-coba)

---

## 📖 Tentang Mamacare
Mamacare bertujuan untuk mengurangi angka kecemasan pada ibu hamil dengan menyediakan informasi medis yang tervalidasi, asisten AI siaga 24/7, serta akses langsung ke fasilitas layanan kesehatan (dokter dan bidan).

---

## 🌟 Fitur Utama

### Akses Mama
- **Chat Mama AI:** Asisten cerdas (Google Gemini AI) untuk menjawab keluhan kehamilan seketika.
- **AI Food Scanner:** Memindai foto makanan dan menganalisis kandungan gizi serta keamanannya bagi ibu hamil.
- **Tanya Dokter (Live Chat):** Konsultasi langsung dengan tenaga medis profesional terdaftar.
- **Reservasi Medis:** Pembuatan jadwal temu (*appointment*) untuk pemeriksaan kehamilan luring (offline).
- **Pemantau Kehamilan:**
  - **Ukuran Janin:** Visualisasi ukuran janin setiap minggu disandingkan dengan analogi buah.
  - **Pantau Berat Badan:** Grafik *weight tracker* cerdas berbasis perhitungan *Body Mass Index* (BMI).
  - **Penghitung Tendangan (Kick Counter):** Pencatat frekuensi pergerakan janin dalam rentang waktu tertentu.
  - **Penghitung Kontraksi (Contraction Timer):** Pengukur durasi dan interval kontraksi menjelang persalinan.
- **Panduan Nutrisi:** Direktori makanan rekomendasi dan pantangan per trimester yang dikurasi langsung oleh dokter.
- **Kalender Kehamilan & Rekap Medis:** Pencatatan HPHT, estimasi usia kandungan, dan rekam medis lengkap.

### Akses Dokter
- **Manajemen Artikel Kesehatan:** Platform *Content Management System* (CMS) untuk menulis dan menerbitkan edukasi.
- **Kelola Panduan Nutrisi:** Mengatur daftar makanan yang disarankan atau dilarang berdasarkan kriteria medis.
- **Manajemen Pasien & Reservasi:** Mengelola jadwal janji temu dan mengisi riwayat diagnosis.
- **Jawab Konsultasi:** Sistem perpesanan untuk membalas keluhan dari para pengguna (Mama).

### Akses Admin
- **Kelola Pengguna:** Melakukan kurasi, verifikasi, atau penghapusan terhadap pendaftaran akun dokter.
- **Dasbor Analitik:** Memantau ringkasan statistik dan aktivitas keseluruhan platform.

---

## 🛠️ Teknologi yang Digunakan
- **Backend:** Laravel 10 (PHP)
- **Frontend:** Blade Templating, TailwindCSS, Alpine.js
- **Database:** MySQL
- **AI Integration:** Google Gemini API
- **Asset Compiler:** Vite

---

## 🚀 Panduan Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan Mamacare di sistem lokal Anda:

1. **Unduh Repositori**
   ```bash
   git clone https://github.com/Azmi-Student/MamacareV2.git
   cd mamacare
   ```

2. **Pasang Dependensi (PHP & Node.js)**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin file konfigurasi bawaan dan sesuaikan informasi koneksi database Anda (seperti DB_DATABASE, DB_USERNAME).
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi dan Seed Database**
   Perintah ini akan menyusun seluruh struktur tabel dan memasukkan data tiruan (*dummy data*) yang diperlukan untuk pengujian.
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Jalankan Aplikasi**
   Buka dua terminal terpisah untuk menjalankan *server* PHP dan *compiler* aset statis.
   ```bash
   php artisan serve
   ```
   ```bash
   npm run dev
   ```

6. **Akses Aplikasi**
   Buka browser web Anda dan kunjungi `http://localhost:8000`.

---

## 🔑 Konfigurasi API
Untuk memastikan fitur **Chat Mama AI** dan **AI Food Scanner** beroperasi, Anda wajib mendaftarkan dan memasukkan kunci API dari Google Gemini ke dalam file `.env`.
Tambahkan baris berikut di file `.env`:
```env
GEMINI_API_KEY="AIzaSyYourApiKeyHere..."
```

---

## 👥 Data Akun Uji Coba (Seeder)
Sistem otomatis menyediakan beberapa akun *dummy* untuk memudahkan pengujian. Password untuk **seluruh** akun di bawah ini adalah: `password`.

| Peran (Role) | Email | Keterangan |
| :--- | :--- | :--- |
| **Admin** | `admin@gmail.com` | Administrator Sistem |
| **Dokter** | `dokter@gmail.com` | Dr. Boyke (Spesialis Kandungan) |
| **Dokter** | `aisah@gmail.com` | Dr. Aisah (Spesialis Kandungan) |
| **Mama** | `mama@gmail.com` | Pengguna Ibu Hamil |

---
*Mamacare — Dikembangkan untuk mendukung perjalanan kehamilan yang lebih aman, nyaman, dan teredukasi.*

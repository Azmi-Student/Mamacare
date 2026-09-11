@extends('layouts.app')

@section('title', 'Dashboard Dokter - Mamacare')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ========================================================= --}}
            {{-- BARU: HERO SECTION DOKTER DENGAN EFEK GELAS & GAMBAR BESAR --}}
            {{-- ========================================================= --}}

            {{-- 1. Kita masukkan CSS Efek Gelasnya di sini (agar lokal hanya di halaman ini) --}}
            <style>
                @keyframes smooth-shine {
                    0% { left: -100%; opacity: 0; }
                    10% { opacity: 1; }
                    50% { left: 200%; opacity: 1; }
                    100% { left: 200%; opacity: 0; }
                }

                .efek-gelas-premium {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 60%; /* Sedikit lebih lebar biar kilatannya mantap */
                    height: 100%;
                    /* Gradient putih transparan untuk efek kilau kaca */
                    background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.1) 30%, rgba(255, 255, 255, 0.4) 50%, rgba(255, 255, 255, 0.1) 70%, rgba(255, 255, 255, 0) 100%);
                    transform: skewX(-25deg); /* Memiringkan kilatan */
                    animation: smooth-shine 5s infinite ease-in-out; /* Animasi berulang */
                    pointer-events: none; /* Agar tidak mengganggu klik */
                    z-index: 20; /* Di atas background, di bawah teks/gambar */
                }
            </style>

            {{-- 2. Container Utama Hero Section --}}
            {{-- Tambahkan 'isolate' untuk memastikan z-index bekerja rapi --}}
            <div class="bg-[#FF3EA5] rounded-3xl border-2 border-[#FF3EA5] shadow-[6px_6px_0px_0px_#ff90c8] p-6 md:p-8 mb-8 relative overflow-hidden flex items-center justify-between isolate">

                {{-- === ELEMEN EFEK GELAS === --}}
                {{-- Div ini akan berjalan membuat efek kilau di atas background pink --}}
                <div class="efek-gelas-premium"></div>
                {{-- ========================= --}}


                {{-- Dekorasi Background Abstrak (Blur di belakang) --}}
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-20 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute left-1/2 bottom-0 w-32 h-32 bg-pink-400 opacity-30 rounded-full blur-xl pointer-events-none"></div>

                {{-- KONTEN TEKS (Kiri) --}}
                {{-- z-index 30 agar di atas efek gelas --}}
                <div class="relative z-30 w-full md:w-3/5">
                    <p class="text-pink-200 font-bold uppercase tracking-widest text-xs mb-1">
                        Dashboard Profesional
                    </p>
                    <h1 class="text-3xl md:text-4xl font-black text-white leading-tight drop-shadow-md">
                        Halo, <br>
                        <span class="bg-white text-[#FF3EA5] px-3 py-1 rounded-lg inline-block mt-2 shadow-sm break-words max-w-full">
                            {{ $doctor->name ?? 'Dokter' }}
                        </span>
                    </h1>
                    <p class="mt-4 text-white font-medium text-sm md:text-base border-l-4 border-pink-200 pl-3 md:max-w-md">
                        Selamat bertugas, Dok! Mari berikan pelayanan terbaik untuk kesehatan Bunda dan Buah Hati hari ini.
                    </p>
                </div>

                {{-- IKON ILUSTRASI DOKTER (Kanan) --}}
                {{-- z-index 30 agar di atas efek gelas --}}
                {{-- Tambah pr-6 agar gambar besar tidak terlalu mepet kanan --}}
                <div class="hidden md:flex relative z-30 w-2/5 justify-end items-center pr-6">
                    
                    {{-- REVISI UKURAN: w-64 h-64 (Sangat Besar) --}}
                    <img src="{{ asset('images/img-landingpage/icon_docter.png') }}" 
                         alt="Ikon Dokter"
                         class="w-64 h-64 object-contain animate-bounce-slow drop-shadow-2xl">

                </div>
            </div>

        {{-- SECTION 1: STATS CARDS --}}
        <div
            class="bg-white overflow-hidden rounded-3xl border-2 border-[#FF3EA5] shadow-[6px_6px_0px_0px_#FF3EA5] mb-8">
            <div class="p-6">
                <h3 class="text-xl font-black mb-6 text-[#FF3EA5] uppercase">Panel Konsultasi Dokter</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Card 1: Total Pasien --}}
                    <div
                        class="p-5 bg-white border-2 border-[#FF3EA5] rounded-2xl shadow-sm hover:-translate-y-1 transition-transform">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-full bg-pink-50 flex items-center justify-center text-[#FF3EA5]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-pink-300 uppercase">Total Pasien Saya</p>
                                <p class="text-3xl font-black text-[#C21B75]">{{ $totalPasien }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Status Dokter --}}
                    <div
                        class="p-5 bg-[#FF3EA5] border-2 border-[#FF3EA5] rounded-2xl shadow-sm hover:-translate-y-1 transition-transform">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white/80 uppercase">Status Akun</p>
                                <p class="text-2xl font-black text-white">Aktif / Verified</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- SECTION 2: TABEL PASIEN --}}
        <div
            class="bg-white overflow-hidden rounded-3xl border-2 border-[#FF3EA5] shadow-[6px_6px_0px_0px_#FF3EA5]">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-black text-[#C21B75] uppercase">Daftar Pengguna Mama</h3>
                    <span
                        class="text-xs font-bold bg-pink-50 text-[#FF3EA5] px-3 py-1 rounded-full border border-pink-100">
                        Database Pasien
                    </span>
                </div>

                <div class="overflow-x-auto rounded-xl border-2 border-pink-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-pink-50 border-b-2 border-pink-100">
                                <th class="p-4 text-xs font-black uppercase text-[#FF3EA5] tracking-wider">Nama Bunda
                                </th>
                                <th class="p-4 text-xs font-black uppercase text-[#FF3EA5] tracking-wider">Email</th>
                                <th class="p-4 text-xs font-black uppercase text-[#FF3EA5] tracking-wider text-center">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-pink-100">
                            @forelse($pasiens as $pasien)
                                <tr class="hover:bg-pink-50/50 transition duration-150">
                                    <td class="p-4 text-sm font-bold text-[#C21B75] flex items-center gap-3">
                                        <x-avatar :user="$pasien" size="sm" class="w-8 h-8" />
                                        {{ $pasien->name }}
                                    </td>
                                    <td class="p-4 text-sm font-bold text-pink-400">{{ $pasien->email }}</td>
                                    <td class="p-4 text-center">
                                        <button
                                            class="bg-white border-2 border-[#FF3EA5] text-[#FF3EA5] px-4 py-1.5 rounded-lg text-xs font-black uppercase shadow-[2px_2px_0px_0px_#FF3EA5] hover:shadow-none hover:translate-y-[1px] hover:translate-x-[1px] transition-all">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-8 text-center text-pink-300 font-bold italic">
                                        Belum ada data pasien mama yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination (Custom Style biar gak biru) --}}
                <div class="mt-6">
                    {{ $pasiens->links() }}
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Style Tambahan Khusus Pagination (Opsional) --}}
<style>
    /* Mengubah warna pagination bawaan Tailwind/Laravel jadi Pink */
    nav[role="navigation"] span[aria-current="page"] span {
        background-color: #FF3EA5 !important;
        border-color: #FF3EA5 !important;
        color: white !important;
    }

    nav[role="navigation"] a {
        color: #C21B75 !important;
    }

    nav[role="navigation"] a:hover {
        background-color: #fce7f3 !important;
        /* pink-100 */
    }
</style>
@endsection
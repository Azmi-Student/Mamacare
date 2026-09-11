@extends('layouts.guest')

@section('title', 'Akses Dibatasi - Mamacare')

@section('content')
    <div class="w-full min-h-screen flex items-center justify-center bg-white p-6 relative overflow-hidden">

        {{-- Background Pattern (Titik-titik halus agar tidak kosong banget) --}}
        <div class="absolute inset-0 opacity-[0.03] select-none pointer-events-none"
            style="background-image: radial-gradient(#FF3EA5 2px, transparent 2px); background-size: 30px 30px;"></div>

        {{-- Dekorasi Angka 403 Besar --}}
        <h1 class="absolute text-[25rem] font-black text-pink-50/50 select-none z-0 tracking-tighter">403</h1>

        <div class="w-full max-w-lg text-center relative z-10">
            {{-- Ikon Gembok Neo-Brutalism --}}
            <div class="flex justify-center mb-10">
                <div class="relative">
                    <div class="absolute inset-0 bg-primary rounded-[2.5rem] translate-x-2 translate-y-2"></div>
                    <div
                        class="relative w-28 h-28 bg-white border-4 border-primary rounded-[2.5rem] flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-primary" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="mb-12">
                <h2 class="text-5xl font-black text-primary uppercase tracking-[-0.05em] leading-none mb-6">
                    Akses<br>Terbatas
                </h2>
                <div class="relative inline-block">
                    {{-- Dekorasi Garis Bawah Judul --}}
                    <div class="absolute -bottom-2 left-0 w-full h-2 bg-pink-100 -z-10"></div>
                    <p class="text-[12px] font-black text-primary/60 uppercase tracking-[0.3em] mb-8">
                        Area Khusus Terproteksi
                    </p>
                </div>

                <div class="max-w-sm mx-auto">
                    <p class="text-[13px] font-bold text-primary leading-relaxed opacity-80 uppercase tracking-wider">
                        @if (auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'dokter'))
                            {{-- Teks untuk Admin atau Dokter --}}
                            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Akses dibatasi hanya untuk otoritas
                            tertentu. Silakan kembali ke halaman sebelumnya.
                        @else
                            {{-- Teks default untuk Mama atau Guest --}}
                            Maaf Mama, sepertinya Mama mencoba mengakses area yang memerlukan izin khusus. Silakan kembali
                            ke halaman sebelumnya.
                        @endif
                    </p>
                </div>
            </div>

            @php
                // Inisialisasi rute default (untuk role 'mama' atau default)
                $dashboardRoute = 'dashboard';

                // Cek apakah user sudah login untuk menentukan rute spesifik
                if (auth()->check()) {
                    if (auth()->user()->role === 'admin') {
                        $dashboardRoute = 'admin.dashboard';
                    } elseif (auth()->user()->role === 'dokter') {
                        $dashboardRoute = 'dokter.dashboard';
                    }
                }
            @endphp

            <div class="max-w-xs mx-auto">
                {{-- Jika belum login, arahkan ke landing page ('/'), jika sudah arahkan ke route dashboard-nya --}}
                <a href="{{ auth()->check() ? route($dashboardRoute) : url('/') }}"
                    class="group relative inline-block w-full">

                    {{-- Layer Bayangan Luar --}}
                    <div
                        class="absolute inset-0 bg-primary rounded-2xl translate-x-1.5 translate-y-1.5 transition-transform group-hover:translate-x-0 group-hover:translate-y-0">
                    </div>

                    {{-- Tombol Utama --}}
                    <div
                        class="relative bg-white border-4 border-primary text-primary font-black py-5 rounded-2xl uppercase text-[13px] tracking-[0.3em] transition-transform group-hover:-translate-x-1 group-hover:-translate-y-1 active:translate-x-0 active:translate-y-0 text-center">
                        Kembali Ke Dashboard
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

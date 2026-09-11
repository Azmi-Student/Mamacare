@extends('layouts.app')

@section('title', 'Pengaturan Profil - Mamacare')

@section('content')
<div class="container mx-auto pt-6 pb-16 px-4 max-w-6xl relative">
    
    {{-- NOTIFIKASI SUCCESS (POP-UP MELAYANG DI ATAS) --}}
    @if (session('status'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 3000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-10"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-10"
             class="fixed top-10 left-1/2 -translate-x-1/2 z-[99] w-[90%] max-w-xs">
            
            <div class="bg-white border-4 border-[#FF3EA5] p-4 rounded-2xl shadow-[6px_6px_0px_0px_#ff90c8] flex items-center gap-3">
                <div class="bg-[#FF3EA5] text-white w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-[#FF3EA5] uppercase tracking-widest leading-none">Berhasil!</p>
                    <p class="text-[9px] font-bold text-pink-300 uppercase mt-1">Perubahan telah tersimpan ✨</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Header --}}
    <header class="flex items-center gap-4 mb-10">
        <a href="{{ route('dashboard') }}"
            class="bg-white border-2 border-[#FF3EA5] p-2 rounded-xl shadow-[3px_3px_0px_0px_#FF3EA5] active:shadow-none active:translate-x-0.5 active:translate-y-0.5 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF3EA5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h2 class="text-xl font-black text-[#FF3EA5] uppercase tracking-tight">Pengaturan Akun</h2>
            <p class="text-[9px] font-bold text-pink-300 uppercase tracking-widest mt-1">Kelola informasi profil dan keamanan Anda</p>
        </div>
    </header>

    {{-- Grid Utama --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
        
        {{-- KOLOM KIRI: INFORMASI PROFIL --}}
        <section class="bg-white border-2 border-[#FF3EA5] rounded-[2.5rem] p-6 md:p-8 shadow-[8px_8px_0px_0px_#ff90c8]">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-pink-50 rounded-xl flex items-center justify-center border-2 border-[#FF3EA5] text-xl">👤</div>
                <div>
                    <h3 class="text-sm font-black text-[#FF3EA5] uppercase leading-none">Informasi Profil</h3>
                    <p class="text-[8px] font-bold text-pink-300 uppercase mt-1">Perbarui nama dan alamat email</p>
                </div>
            </div>
            
            <div class="w-full">
                @include('profile.partials.update-profile-information-form')
            </div>
        </section>

        {{-- KOLOM KANAN: KEAMANAN --}}
        <section class="bg-white border-2 border-[#FF3EA5] rounded-[2.5rem] p-6 md:p-8 shadow-[8px_8px_0px_0px_#ff90c8]">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-pink-50 rounded-xl flex items-center justify-center border-2 border-[#FF3EA5] text-xl">🔐</div>
                <div>
                    <h3 class="text-sm font-black text-[#FF3EA5] uppercase leading-none">Keamanan Akun</h3>
                    <p class="text-[8px] font-bold text-pink-300 uppercase mt-1">Perbarui kata sandi Anda</p>
                </div>
            </div>

            <div class="w-full">
                @include('profile.partials.update-password-form')
            </div>
        </section>

    </div>

    {{-- HAPUS AKUN --}}
    <div class="mt-12 max-w-2xl mx-auto">
        <section class="bg-white border-2 border-red-400 rounded-[2.5rem] p-6 shadow-[6px_6px_0px_0px_#fee2e2]">
            <div class="flex items-center gap-3 mb-6 text-red-500">
                <span class="text-xl">⚠️</span>
                <h3 class="text-sm font-black uppercase tracking-wider">Hapus Akun Permanen</h3>
            </div>
            @include('profile.partials.delete-user-form')
        </section>
    </div>

</div>
@endsection
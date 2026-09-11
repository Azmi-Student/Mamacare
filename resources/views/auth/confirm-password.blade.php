@extends('layouts.guest')

@section('title', 'Konfirmasi Sandi - Mamacare')

@section('content')
    <div class="w-full min-h-screen flex items-center justify-center bg-white p-6 relative">
        
        {{-- Tombol Kembali (Opsional, biasanya ke Dashboard) --}}
        <div class="absolute top-8 left-8 md:top-12 md:left-12">
            <a href="javascript:history.back()" class="flex items-center gap-3 group">
                <div class="w-9 h-9 bg-white border-2 border-primary rounded-xl flex items-center justify-center shadow-[3px_3px_0px_0px_#FF3EA5] group-active:shadow-none group-active:translate-x-0.5 group-active:translate-y-0.5 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
                <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">Batal</span>
            </a>
        </div>

        {{-- Card Form Tengah --}}
        <div class="w-full max-w-md text-center">
            
            {{-- Ikon Gembok Keamanan --}}
            <div class="flex justify-center mb-8">
                <div class="w-20 h-20 bg-pink-50 border-2 border-primary rounded-[2.5rem] flex items-center justify-center shadow-[6px_6px_0px_0px_#ff90c8]">
                    <span class="text-4xl">🔐</span>
                </div>
            </div>

            {{-- Info Text --}}
            <div class="mb-8">
                <h2 class="text-3xl font-black text-primary uppercase tracking-tighter leading-none mb-4">Area Terproteksi</h2>
                <div class="bg-pink-50 p-6 rounded-[2rem] border-2 border-dashed border-primary">
                    <p class="text-[11px] font-bold text-primary uppercase tracking-widest leading-relaxed">
                        Ini adalah area aman. Harap konfirmasi kata sandi Mama sebelum melanjutkan akses ke halaman ini.
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6 text-left">
                @csrf

                {{-- Input Password --}}
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-primary uppercase tracking-[0.2em] ml-1">Kata Sandi Anda</label>
                    <input type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                        class="w-full border-2 border-primary p-4 rounded-2xl font-bold text-primary shadow-[4px_4px_0px_0px_#ff90c8] focus:ring-0 focus:border-primary outline-none transition-all bg-white text-sm">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-[9px] font-bold text-red-500 uppercase ml-1" />
                </div>

                {{-- Tombol Konfirmasi --}}
                <button type="submit" 
                    class="w-full bg-primary text-white font-black py-5 rounded-2xl uppercase text-[12px] tracking-[0.25em] shadow-[6px_6px_0px_0px_#ff90c8] hover:shadow-none hover:translate-x-1 hover:translate-y-1 active:scale-95 transition-all">
                    Konfirmasi Akses 🚀
                </button>
            </form>
        </div>
    </div>
@endsection
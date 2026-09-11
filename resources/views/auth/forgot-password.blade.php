@extends('layouts.guest')

@section('title', 'Lupa Sandi - Mamacare')

@section('content')
    <div class="w-full min-h-screen flex items-center justify-center bg-white p-6 relative">
        
        {{-- Tombol Kembali (Pojok Kiri Atas) --}}
        <div class="absolute top-8 left-8 md:top-12 md:left-12">
            <a href="{{ route('login') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 bg-white border-2 border-primary rounded-xl flex items-center justify-center shadow-[3px_3px_0px_0px_#FF3EA5] group-active:shadow-none group-active:translate-x-0.5 group-active:translate-y-0.5 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
                <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">Kembali</span>
            </a>
        </div>

        {{-- Card Form Tengah --}}
        <div class="w-full max-w-md text-center">
            
            {{-- Logo --}}
            <div class="flex justify-center mb-8">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Mamacare Logo" class="w-20 h-20 object-contain">
            </div>

            {{-- Welcome Text --}}
            <div class="mb-8">
                <h2 class="text-4xl font-black text-primary uppercase tracking-tighter leading-none mb-4">Lupa Kata Sandi?</h2>
                <div class="bg-pink-50 p-6 rounded-[2rem] border-2 border-dashed border-primary">
                    <p class="text-[11px] font-bold text-primary uppercase tracking-widest leading-relaxed">
                        Masukkan alamat email Anda yang terdaftar, dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
                    </p>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-8 p-4 bg-primary text-white rounded-2xl shadow-[4px_4px_0px_0px_#ff90c8] text-[10px] font-black uppercase tracking-widest animate-bounce">
                    {{ session('status') }} ✨
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6 text-left">
                @csrf

                {{-- Input Email --}}
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-primary uppercase tracking-[0.2em] ml-1">Alamat Email</label>
                    <input type="email" name="email" :value="old('email')" required autofocus placeholder="nama@email.com"
                        class="w-full border-2 border-primary p-4 rounded-2xl font-bold text-primary shadow-[4px_4px_0px_0px_#ff90c8] focus:ring-0 focus:border-primary outline-none transition-all placeholder:text-pink-200 bg-white">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-[9px] font-bold text-red-500 uppercase ml-1" />
                </div>

                {{-- Tombol Kirim --}}
                <button type="submit" 
                    class="w-full bg-primary text-white font-black py-5 rounded-2xl uppercase text-[12px] tracking-[0.25em] shadow-[6px_6px_0px_0px_#ff90c8] hover:shadow-none hover:translate-x-1 hover:translate-y-1 active:scale-95 transition-all">
                    Kirim Tautan 🚀
                </button>
            </form>
        </div>
    </div>
@endsection
@extends('layouts.guest')

@section('title', 'Atur Ulang Sandi - Mamacare')

@section('content')
    <div class="w-full min-h-screen flex items-center justify-center bg-white p-6 relative">
        
        {{-- Card Form Tengah --}}
        <div class="w-full max-w-md text-center">
            
            {{-- Logo --}}
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Mamacare Logo" class="w-20 h-20 object-contain">
            </div>

            {{-- Judul --}}
            <div class="mb-8">
                <h2 class="text-3xl font-black text-primary uppercase tracking-tighter leading-none mb-3">Atur Ulang Sandi</h2>
                <p class="text-[10px] font-bold text-pink-300 uppercase tracking-[0.2em]">Silakan buat kata sandi baru Mama</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5 text-left">
                @csrf

                {{-- Password Reset Token --}}
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- Email Address (Disabled/Readonly agar user tahu email mana yang direset) --}}
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-primary uppercase tracking-[0.2em] ml-1">Email Anda</label>
                    <input type="email" name="email" value="{{ old('email', $request->email) }}" required readonly
                        class="w-full border-2 border-primary p-3.5 rounded-2xl font-bold text-pink-200 bg-pink-50 cursor-not-allowed outline-none text-sm">
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-[9px] font-bold text-red-500 uppercase ml-1" />
                </div>

                {{-- Password Baru --}}
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-primary uppercase tracking-[0.2em] ml-1">Kata Sandi Baru</label>
                    <input type="password" name="password" required autofocus autocomplete="new-password" placeholder="••••••••"
                        class="w-full border-2 border-primary p-3.5 rounded-2xl font-bold text-primary shadow-[4px_4px_0px_0px_#ff90c8] focus:ring-0 focus:border-primary outline-none transition-all bg-white text-sm">
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-[9px] font-bold text-red-500 uppercase ml-1" />
                </div>

                {{-- Konfirmasi Password --}}
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-primary uppercase tracking-[0.2em] ml-1">Ulangi Kata Sandi</label>
                    <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••"
                        class="w-full border-2 border-primary p-3.5 rounded-2xl font-bold text-primary shadow-[4px_4px_0px_0px_#ff90c8] focus:ring-0 focus:border-primary outline-none transition-all bg-white text-sm">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-[9px] font-bold text-red-500 uppercase ml-1" />
                </div>

                {{-- Tombol Submit --}}
                <div class="pt-2">
                    <button type="submit" 
                        class="w-full bg-primary text-white font-black py-4 rounded-2xl uppercase text-[11px] tracking-[0.2em] shadow-[6px_6px_0px_0px_#ff90c8] hover:shadow-none hover:translate-x-1 hover:translate-y-1 active:scale-95 transition-all">
                        Simpan Sandi Baru 🔐
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
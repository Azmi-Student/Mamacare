@extends('layouts.app')
@section('title', 'Baby Size Visualizer - Mamacare')
@section('content')
<div class="min-h-screen py-6 md:py-10 text-[#FF3EA5] font-sans">
    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-[#FF3EA5] text-[#FF3EA5] font-black uppercase rounded-xl shadow-[3px_3px_0px_0px_#ff90c8]">
                Kembali
            </a>
        </div>
        
        <div class="mb-10 border-b-2 border-dashed border-[#FF3EA5] pb-6 text-center">
            <h2 class="text-3xl md:text-5xl font-black uppercase tracking-tighter">Ukuran <span class="text-stroke-pink text-white">Janin</span></h2>
            <p class="font-bold opacity-80 mt-2">Gambaran ukuran si kecil di minggu ke-{{ $week }}.</p>
        </div>

        <div class="bg-white border-2 border-[#FF3EA5] rounded-[2rem] p-8 md:p-12 text-center shadow-[4px_4px_0px_0px_#ff90c8] relative overflow-hidden">
            {{-- Hiasan latar Neo-brutalism --}}
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-pink-100 rounded-full mix-blend-multiply opacity-50"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-pink-200 rounded-full mix-blend-multiply opacity-50"></div>

            <div class="relative z-10">
                <p class="font-black uppercase tracking-widest opacity-60 mb-2">Usia Kehamilan</p>
                <h3 class="text-4xl md:text-5xl font-black mb-8">{{ $week }} Minggu</h3>

                <div class="w-48 h-48 md:w-64 md:h-64 mx-auto bg-pink-50 border-4 border-[#FF3EA5] rounded-full shadow-[8px_8px_0px_0px_#ff90c8] flex items-center justify-center text-8xl md:text-9xl mb-8 transform hover:scale-110 transition-transform duration-300">
                    {{ $babySize['emoji'] }}
                </div>

                <p class="font-black text-xl uppercase mb-1">Sebesar {{ $babySize['fruit'] }}</p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-6 my-6">
                    <div class="bg-white border-2 border-[#FF3EA5] px-4 py-2 rounded-xl shadow-[3px_3px_0px_0px_#ff90c8]">
                        <p class="text-xs font-bold uppercase opacity-60">Panjang</p>
                        <p class="font-black text-lg">{{ $babySize['length'] }}</p>
                    </div>
                    <div class="bg-white border-2 border-[#FF3EA5] px-4 py-2 rounded-xl shadow-[3px_3px_0px_0px_#ff90c8]">
                        <p class="text-xs font-bold uppercase opacity-60">Berat</p>
                        <p class="font-black text-lg">{{ $babySize['weight'] }}</p>
                    </div>
                </div>

                <div class="bg-[#FF3EA5] text-white p-6 rounded-2xl border-2 border-[#FF3EA5] shadow-[4px_4px_0px_0px_#ff90c8] mt-8 inline-block max-w-lg text-left">
                    <p class="font-black uppercase tracking-widest text-xs mb-2 opacity-80">Catatan Perkembangan</p>
                    <p class="font-bold text-lg leading-relaxed">{{ $babySize['desc'] }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

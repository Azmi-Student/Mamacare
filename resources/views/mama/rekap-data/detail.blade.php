@extends('layouts.app')

@section('title', 'Detail Rekap Medis - Mamacare')

@section('content')
    {{-- CSS KHUSUS PRINT --}}
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #print-template,
            #print-template * {
                visibility: visible;
            }

            #print-template {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 20px;
                background: white;
            }

            @page {
                size: auto;
                margin: 0mm;
            }
        }
    </style>

    {{-- WRAPPER UTAMA (TAMPILAN WEB) --}}
    <div class="min-h-screen py-6 md:py-10 text-[#FF3EA5] font-sans selection:bg-[#FF3EA5] selection:text-white print:hidden"
        x-data="{ showModal: false, activeImage: '' }">

        <div class="max-w-4xl mx-auto px-4 sm:px-6">

            {{-- TOMBOL NAVIGASI --}}
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('mama.rekap-data') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-[#FF3EA5] text-[#FF3EA5] font-black uppercase rounded-xl shadow-[3px_3px_0px_0px_#FF3EA5] hover:bg-pink-50 hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all text-[10px] md:text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <button onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#FF3EA5] border-2 border-[#FF3EA5] text-white font-black uppercase rounded-xl shadow-[3px_3px_0px_0px_#b82c76] hover:shadow-none hover:translate-x-[1px] hover:translate-y-[1px] transition-all text-[10px] md:text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Cetak Resmi
                </button>
            </div>

            {{-- KARTU DETAIL --}}
            <div
                class="bg-white border-2 border-[#FF3EA5] rounded-[2rem] shadow-[4px_4px_0px_0px_#ff90c8] overflow-hidden">
                {{-- Header --}}
                <div class="bg-white border-b-2 border-dashed border-[#FF3EA5] p-6 md:p-8">

                    {{-- 1. INFO PASIEN --}}
                    <div
                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4 mb-6 pb-6 border-b-2 border-dashed border-[#FF3EA5]/30">
                        <div
                            class="self-start bg-[#FF3EA5] text-white px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-[2px_2px_0px_0px_rgba(0,0,0,0.1)] shrink-0">
                            Pasien
                        </div>
                        <h1
                            class="text-xl sm:text-2xl md:text-4xl font-black uppercase tracking-tighter leading-tight text-[#FF3EA5] break-words">
                            {{ Auth::user()->name ?? 'Bunda' }}
                        </h1>
                    </div>

                    {{-- 2. INFO DOKTER & TANGGAL --}}
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div class="flex items-start gap-4 w-full md:w-auto">
                            <div
                                class="w-12 h-12 md:w-16 md:h-16 rounded-full border-2 border-[#FF3EA5] flex items-center justify-center shrink-0 shadow-[2px_2px_0px_0px_#ff90c8]">
                                <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[9px] md:text-[10px] font-black uppercase tracking-widest opacity-60">
                                    Dokter Pemeriksa
                                </p>
                                <h2
                                    class="text-lg sm:text-xl md:text-3xl font-black uppercase tracking-tighter leading-tight break-words text-[#FF3EA5]">
                                    {{ $rekap->doctor->name }}
                                </h2>
                                <p
                                    class="text-[10px] md:text-xs font-bold uppercase tracking-widest opacity-80 italic mt-1">
                                    {{ $rekap->time }} WIB
                                </p>
                            </div>
                        </div>
                        <div
                            class="w-full md:w-auto bg-white border-2 border-[#FF3EA5] px-5 py-3 rounded-2xl shadow-[3px_3px_0px_0px_#ff90c8] text-center">
                            <p class="text-[8px] md:text-[10px] font-black uppercase opacity-60">Tanggal Periksa</p>
                            <p class="text-base md:text-lg font-black uppercase whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($rekap->date)->isoFormat('D MMM YYYY') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Isi Detail --}}
                <div class="p-6 md:p-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
                        {{-- Kolom Kiri --}}
                        <div class="space-y-8">
                            {{-- BAGIAN KELUHAN --}}
                            <div>
                                <div
                                    class="inline-block bg-[#FF3EA5] text-white px-3 py-1 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-widest mb-3">
                                    Keluhan Awal
                                </div>
                                <div
                                    class="p-5 md:p-6 bg-pink-50 border-2 border-[#FF3EA5] rounded-[1.5rem] font-bold text-sm md:text-base leading-relaxed text-[#FF3EA5] shadow-[3px_3px_0px_0px_#ff90c8]">
                                    {{ $rekap->notes ?? 'Tidak ada keluhan spesifik.' }}
                                </div>
                            </div>

                            {{-- Diagnosa --}}
                            <div>
                                <div
                                    class="inline-block bg-[#FF3EA5] text-white px-3 py-1 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-widest mb-3">
                                    Diagnosa</div>
                                <div
                                    class="p-5 md:p-6 bg-white border-2 border-[#FF3EA5] rounded-[1.5rem] font-bold text-sm md:text-base leading-relaxed shadow-[3px_3px_0px_0px_#ff90c8]">
                                    {{ $rekap->diagnosis }}
                                </div>
                            </div>

                            {{-- Resep --}}
                            <div>
                                <div
                                    class="inline-block bg-white text-[#FF3EA5] border-2 border-[#FF3EA5] px-3 py-1 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-widest mb-3">
                                    Resep & Saran
                                </div>
                                <div
                                    class="p-5 md:p-6 bg-white border-2 border-[#FF3EA5] border-dashed rounded-[1.5rem] font-bold text-sm md:text-base leading-relaxed">
                                    {!! nl2br(e($rekap->prescription)) !!}
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Foto (UPDATE LAYOUT 1 + 2) --}}
                        <div>
                            <div
                                class="inline-block bg-[#FF3EA5] text-white px-3 py-1 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-widest mb-3">
                                Hasil Foto USG
                            </div>
                            
                            @php
                                $images = [];
                                if($rekap->image) {
                                    $decoded = json_decode($rekap->image, true);
                                    $images = is_array($decoded) ? $decoded : [$rekap->image];
                                }
                            @endphp

                            @if (count($images) > 0)
                                {{-- PERBAIKAN LAYOUT: Grid 2 Kolom --}}
                                <div class="grid grid-cols-2 gap-3 md:gap-4">
                                    @foreach($images as $index => $img)
                                        <div @click="activeImage = '{{ asset('storage/' . $img) }}'; showModal = true"
                                            class="group relative cursor-pointer border-2 border-[#FF3EA5] rounded-2xl overflow-hidden shadow-[3px_3px_0px_0px_#ff90c8] transition-transform hover:scale-[1.01]
                                            {{-- LOGIKA 1 + 2 --}}
                                            {{-- Jika foto pertama: Span 2 kolom (Full Width) & Tinggi --}}
                                            {{ $loop->first ? 'col-span-2 h-48 md:h-80' : 'col-span-1 h-32 md:h-48' }}">
                                            
                                            <img src="{{ asset('storage/' . $img) }}"
                                                class="w-full h-full object-cover" />
                                            
                                            {{-- Overlay Hover --}}
                                            <div
                                                class="absolute inset-0 bg-[#FF3EA5]/20 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                                                <span
                                                    class="bg-white border-2 border-[#FF3EA5] px-3 py-1 font-black uppercase text-[8px] shadow-[2px_2px_0px_0px_#FF3EA5]">
                                                    Perbesar
                                                </span>
                                            </div>

                                            {{-- Badge Urutan Foto (Opsional, biar manis) --}}
                                            <div class="absolute top-2 left-2 bg-[#FF3EA5] text-white text-[8px] font-black w-5 h-5 flex items-center justify-center rounded-full border-2 border-white">
                                                {{ $loop->iteration }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div
                                    class="h-64 md:h-80 border-2 border-dashed border-[#FF3EA5] rounded-[2rem] flex flex-col items-center justify-center text-[#FF3EA5] opacity-40 bg-pink-50">
                                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p class="font-black text-[10px] uppercase tracking-widest text-center px-6 italic">
                                        Tidak ada lampiran foto</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-12 pt-6 border-t-2 border-dashed border-[#FF3EA5] text-center">
                        <p class="text-[8px] md:text-[9px] font-black uppercase opacity-40 tracking-[0.3em]">Mamacare</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Lightbox --}}
        <div x-show="showModal" x-transition.opacity
            class="fixed inset-0 z-[100] bg-white/90 backdrop-blur-sm flex items-center justify-center p-4 md:p-10"
            style="display: none;">
            <div class="relative bg-white border-2 border-[#FF3EA5] rounded-[2rem] md:rounded-[3rem] p-2 shadow-[6px_6px_0px_0px_#ff90c8] max-w-5xl w-full"
                @click.away="showModal = false">
                <button @click="showModal = false"
                    class="absolute -top-4 -right-4 md:-top-6 md:-right-6 w-10 h-10 md:w-14 md:h-14 bg-white border-2 border-[#FF3EA5] text-[#FF3EA5] font-black text-lg md:text-2xl rounded-full shadow-[3px_3px_0px_0px_#ff90c8] hover:rotate-90 transition-all">X</button>
                <img :src="activeImage"
                    class="w-full h-auto max-h-[70vh] md:max-h-[80vh] object-contain rounded-[1.5rem] md:rounded-[2.5rem]">
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- TEMPLATE KHUSUS CETAK --}}
    {{-- ========================================== --}}
    <div id="print-template" class="hidden print:block print:bg-white text-black font-serif p-8">
        {{-- KOP SURAT --}}
        <div class="border-b-4 border-black pb-4 mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo-icon.png') }}" class="w-16 h-16 object-contain grayscale"
                    alt="Logo Mamacare">
                <div>
                    <h1 class="text-3xl font-bold uppercase tracking-wider">Mamacare</h1>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs">Dicetak pada:</p>
                <p class="font-bold">{{ now()->format('d M Y H:i') }}</p>
            </div>
        </div>

        {{-- JUDUL --}}
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold underline decoration-2 underline-offset-4">REKAP MEDIS PASIEN</h2>
        </div>

        {{-- INFO PASIEN --}}
        <div class="mb-6 border-b-2 border-dashed border-gray-300 pb-4">
            <p class="text-xs uppercase font-bold text-gray-500 mb-1">Nama Pasien:</p>
            <p class="text-xl font-bold uppercase tracking-wide">{{ Auth::user()->name ?? 'Bunda' }}</p>
        </div>

        {{-- INFO DOKTER --}}
        <div class="grid grid-cols-2 gap-4 mb-8">
            <div>
                <p class="text-xs uppercase font-bold text-gray-500 mb-1">Dokter Pemeriksa:</p>
                <p class="text-lg font-bold">{{ $rekap->doctor->name }}</p>
            </div>
            <div>
                <p class="text-xs uppercase font-bold text-gray-500 mb-1">Tanggal & Waktu:</p>
                <p class="text-lg font-bold">
                    {{ \Carbon\Carbon::parse($rekap->date)->isoFormat('D MMMM YYYY') }}
                    <span class="text-sm font-normal">({{ $rekap->time }} WIB)</span>
                </p>
            </div>
        </div>

        {{-- KELUHAN --}}
        <div class="mb-6">
            <p class="text-xs uppercase font-bold text-gray-500 mb-1">Keluhan Pasien:</p>
            <div class="border-l-4 border-black pl-4 py-1">
                <p class="text-lg italic text-gray-700">{{ $rekap->notes ?? 'Tidak ada keluhan spesifik.' }}</p>
            </div>
        </div>

        {{-- ISI HASIL --}}
        <div class="border-2 border-black p-0 mb-8">
            <div class="border-b-2 border-black p-4 bg-gray-50">
                <p class="font-bold uppercase text-xs mb-2">Hasil Diagnosa:</p>
                <p class="text-lg leading-relaxed">{{ $rekap->diagnosis }}</p>
            </div>
            <div class="p-4">
                <p class="font-bold uppercase text-xs mb-2">Resep Obat & Saran:</p>
                <p class="text-lg leading-relaxed font-mono">
                    {!! nl2br(e($rekap->prescription ?: 'Tidak ada resep khusus.')) !!}
                </p>
            </div>
        </div>

        {{-- LAMPIRAN FOTO USG --}}
        @php
            $printImages = [];
            if($rekap->image) {
                $decoded = json_decode($rekap->image, true);
                $printImages = is_array($decoded) ? $decoded : [$rekap->image];
            }
        @endphp

        @if (count($printImages) > 0)
            <div class="border-2 border-black p-4 mb-8 break-inside-avoid">
                <p class="font-bold uppercase text-xs mb-4">Lampiran Foto USG (Total: {{ count($printImages) }})</p>
                
                {{-- LAYOUT CETAK: Tetap 3 kolom berjejer agar hemat kertas (tidak terlalu tinggi) --}}
                <div class="grid grid-cols-3 gap-4">
                    @foreach($printImages as $img)
                        <div class="bg-gray-50 border border-gray-300 p-2 flex items-center justify-center aspect-square">
                            <img src="{{ asset('storage/' . $img) }}"
                                class="max-w-full max-h-full object-contain">
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="border-2 border-dashed border-gray-300 p-4 mb-8 text-center text-gray-400 italic text-sm">
                - Tidak ada lampiran foto USG -
            </div>
        @endif

        {{-- FOOTER --}}
        <div class="mt-8 text-center text-[10px] text-gray-400 italic">Dokumen ini dihasilkan secara digital oleh sistem
            Mamacare.</div>
    </div>
@endsection
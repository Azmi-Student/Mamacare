@extends('layouts.app')

@section('title', 'Daftar Rekap Medis - Mamacare')

@section('content')
<div class="min-h-screen py-6 md:py-10 text-[#FF3EA5] font-sans selection:bg-[#FF3EA5] selection:text-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        
        {{-- 1. TOMBOL KEMBALI --}}
        <div class="mb-6 md:mb-8">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-[#FF3EA5] text-[#FF3EA5] font-black uppercase rounded-xl shadow-[3px_3px_0px_0px_#FF3EA5] hover:bg-pink-50 hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all text-[10px] md:text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        {{-- 2. HEADER SECTION (Ukuran Font diperkecil agar lebih elegan) --}}
        <div class="mb-10 border-b-2 border-dashed border-[#FF3EA5] pb-6 md:pb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div class="max-w-xl">
                <h2 class="text-3xl md:text-5xl font-black uppercase tracking-tighter leading-none mb-2 text-[#FF3EA5]">
                    Riwayat <span class="text-stroke-pink">Periksa</span>
                </h2>
                <p class="text-[10px] md:text-xs font-bold uppercase tracking-[0.15em] leading-relaxed text-[#FF3EA5] opacity-80">
                    Kumpulan diagnosa dan foto USG Mama. Pilih jadwal untuk detail lengkap.
                </p>
            </div>
            {{-- Badge Total --}}
            <div class="bg-white text-[#FF3EA5] px-4 py-1.5 border-2 border-[#FF3EA5] shadow-[3px_3px_0px_0px_#ff90c8] rounded-xl font-black text-[10px] md:text-xs uppercase tracking-tighter">
                Total: {{ $rekaps->total() }} Catatan
            </div>
        </div>

        {{-- 3. GRID LIST REKAP (Responsive: 1 col mobile, 2 col tablet, 3 col desktop) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @forelse ($rekaps as $rekap)
                <a href="{{ route('mama.rekap-data.detail', $rekap->id) }}" 
                   class="group relative bg-white border-2 border-[#FF3EA5] rounded-[2rem] p-5 md:p-6 shadow-[4px_4px_0px_0px_#ff90c8] hover:shadow-[2px_2px_0px_0px_#FF3EA5] hover:translate-x-[2px] hover:translate-y-[2px] transition-all duration-200 flex flex-col h-full active:shadow-none active:translate-x-1 active:translate-y-1">
                    
                    {{-- Status Floating --}}
                    <div class="absolute -top-3 right-5 bg-white border-2 border-[#FF3EA5] px-3 py-0.5 rounded-lg shadow-[2px_2px_0px_0px_#FF3EA5]">
                        <span class="text-[8px] md:text-[9px] font-black uppercase tracking-widest text-[#FF3EA5]">Selesai</span>
                    </div>

                    {{-- Tanggal (Ukuran disesuaikan agar rapi di mobile) --}}
                    <div class="mb-4">
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-[#FF3EA5] mb-1 opacity-70">Waktu Periksa</p>
                        <div class="flex items-baseline gap-2 text-[#FF3EA5]">
                            <span class="text-2xl md:text-3xl font-black tracking-tighter uppercase">
                                {{ \Carbon\Carbon::parse($rekap->date)->isoFormat('D MMM') }}
                            </span>
                            <span class="text-sm md:text-lg font-bold opacity-40 uppercase italic">
                                {{ \Carbon\Carbon::parse($rekap->date)->isoFormat('YYYY') }}
                            </span>
                        </div>
                    </div>

                    {{-- Info Dokter (Box Putih Simpel) --}}
                    <div class="flex items-center gap-3 mb-6 p-3 rounded-xl border-2 border-[#FF3EA5] border-dashed">
                        <div class="w-10 h-10 rounded-full bg-white border-2 border-[#FF3EA5] flex items-center justify-center text-[#FF3EA5] shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-sm md:text-base font-black uppercase leading-tight truncate text-[#FF3EA5]">
                                {{ $rekap->doctor->name }}
                            </h4>
                            <p class="text-[9px] font-bold uppercase tracking-tighter text-[#FF3EA5] opacity-50">{{ $rekap->time }} WIB</p>
                        </div>
                    </div>
                    
                    {{-- Action Footer (Warna Pink Solid saat Hover) --}}
                    <div class="mt-auto pt-4 border-t-2 border-[#FF3EA5] flex items-center justify-between group-hover:bg-[#FF3EA5] transition-colors duration-200 -mx-5 md:-mx-6 -mb-5 md:-mb-6 px-5 md:px-6 py-4 rounded-b-[1.3rem] md:rounded-b-[1.8rem]">
                        <span class="text-[10px] font-black uppercase tracking-widest italic group-hover:text-white transition-colors">Hasil Medis</span>
                        <div class="w-8 h-8 bg-white text-[#FF3EA5] border-2 border-[#FF3EA5] rounded-full flex items-center justify-center shadow-[2px_2px_0px_0px_#FF3EA5] group-hover:shadow-none group-hover:border-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-20 border-2 border-dashed border-[#FF3EA5] rounded-[2rem] text-center bg-pink-50">
                    <p class="font-black text-lg md:text-xl uppercase text-[#FF3EA5] tracking-tighter opacity-40">
                        Belum ada riwayat periksa.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- 4. PAGINATION --}}
        <div class="mt-12 custom-pagination flex justify-center">
            {{ $rekaps->links() }}
        </div>
    </div>
</div>

<style>
    /* Pagination Styling - Strictly Pink & White */
    .custom-pagination nav svg { width: 16px; height: 16px; display: inline; }
    .custom-pagination nav div div flex,
    .custom-pagination nav div div span, 
    .custom-pagination nav a {
        background-color: white !important;
        border: 2px solid #FF3EA5 !important;
        color: #FF3EA5 !important;
        border-radius: 8px !important;
        font-size: 10px !important;
        font-weight: 900 !important;
        padding: 6px 12px !important;
        margin: 0 2px !important;
        box-shadow: 2px 2px 0px 0px #FF3EA5 !important;
    }
    .custom-pagination nav a:hover {
        background-color: #FF3EA5 !important;
        color: white !important;
        box-shadow: none !important;
        transform: translate(1px, 1px) !important;
    }
    .custom-pagination nav p { display: none; }
</style>
@endsection
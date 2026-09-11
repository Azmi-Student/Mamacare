@extends('layouts.app')

@section('title', 'Laporan Detail Kehamilan - Mamacare')

@section('content')
<div class="container mx-auto py-6 px-4 max-w-5xl">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('mama.kalender', ['hpht' => $data['hpht']]) }}" 
            class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-[#FF3EA5] text-[#FF3EA5] font-black uppercase rounded-xl shadow-[3px_3px_0px_0px_#FF3EA5] hover:bg-pink-50 hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all text-[10px] md:text-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
        <div class="text-right">
            <h2 class="text-xl font-black text-[#FF3EA5] uppercase italic tracking-tighter leading-none italic">Detail Report</h2>
            <p class="text-[9px] font-bold text-[#FF3EA5] opacity-60 uppercase italic">Minggu ke-{{ $data['minggu'] }}</p>
        </div>
    </div>

    <div class="space-y-8">
        
        {{-- 1. Milestone & Progress Card --}}
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Trimester Progress --}}
            <div class="md:col-span-2 bg-white border-4 border-[#FF3EA5] rounded-[2.5rem] p-8 shadow-[10px_10px_0px_0px_#ff90c8]">
                <div class="flex justify-between items-end mb-4">
                    <h3 class="text-2xl font-black text-[#FF3EA5] uppercase italic italic tracking-tighter leading-none">Status Trimester</h3>
                    <span class="bg-[#FF3EA5] text-white px-3 py-1 rounded-lg font-black text-xs italic">{{ $data['persen'] }}%</span>
                </div>
                <div class="w-full h-8 bg-pink-50 border-4 border-[#FF3EA5] rounded-2xl overflow-hidden p-1 relative mb-6">
                    <div class="h-full bg-[#FF3EA5] rounded-xl transition-all duration-1000" style="width: {{ $data['persen'] }}%"></div>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div class="text-[10px] font-black text-center {{ $data['trimester'] == 1 ? 'text-[#FF3EA5]' : 'text-pink-200' }}">TRIMESTER 1</div>
                    <div class="text-[10px] font-black text-center {{ $data['trimester'] == 2 ? 'text-[#FF3EA5]' : 'text-pink-200' }}">TRIMESTER 2</div>
                    <div class="text-[10px] font-black text-center {{ $data['trimester'] == 3 ? 'text-[#FF3EA5]' : 'text-pink-200' }}">TRIMESTER 3</div>
                </div>
            </div>

            {{-- Baby Stats (Countdown & HPL) --}}
            <div class="bg-[#FF3EA5] border-4 border-[#FF3EA5] rounded-[2.5rem] p-8 shadow-[10px_10px_0px_0px_#ff90c8] text-white text-center flex flex-col justify-center">
                <p class="text-[10px] font-black uppercase tracking-widest mb-2 opacity-80">Menuju Kelahiran</p>
                <h3 class="text-5xl font-black italic tracking-tighter leading-none mb-1">{{ number_format($data['sisa_hari'], 0) }}</h3>
                <p class="text-xs font-bold uppercase italic">Hari Lagi</p>
                <div class="mt-4 pt-4 border-t-2 border-white/20">
                    <p class="text-[9px] font-black uppercase opacity-80 italic">Estimasi HPL</p>
                    <p class="text-sm font-black italic">{{ $data['hpl'] }}</p>
                </div>
            </div>
        </section>

        {{-- NEW SECTION: Milestone Organ & Perkembangan Janin --}}
        <section class="bg-white border-4 border-[#FF3EA5] rounded-[2.5rem] p-8 shadow-[10px_10px_0px_0px_#ff90c8]">
            <h4 class="font-black text-[#FF3EA5] uppercase text-sm mb-6 flex items-center gap-2 italic">
                <span class="p-1 bg-[#FF3EA5] text-white rounded text-xs">👶</span> Perkembangan Organ Janin
            </h4>
            <div class="bg-pink-50 border-2 border-dashed border-[#FF3EA5] p-6 rounded-2xl">
                <p class="text-sm font-bold text-[#FF3EA5] uppercase italic tracking-tighter leading-relaxed italic">
                    "{{ $data['detail'] ?? 'Janin sedang tumbuh dengan sempurna minggu ini.' }}"
                </p>
            </div>
        </section>

        {{-- 2. Checklist Nutrisi & Aktivitas (DENGAN PENGAMAN) --}}
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php 
                $cards = [
                    ['key' => 'harian', 'title' => 'Tugas Harian', 'icon' => '1'],
                    ['key' => 'mingguan', 'title' => 'Target Minggu Ini', 'icon' => '2'],
                    ['key' => 'bulanan', 'title' => 'Persiapan Bulan Ini', 'icon' => '3']
                ];
            @endphp

            @foreach($cards as $c)
            <div class="bg-white border-4 border-[#FF3EA5] p-8 rounded-[2rem] shadow-[8px_8px_0px_0px_#ff90c8]">
                <div class="w-10 h-10 bg-[#FF3EA5] text-white rounded-xl flex items-center justify-center font-black italic mb-6 shadow-[3px_3px_0px_0px_#ff90c8]">{{ $c['icon'] }}</div>
                <h5 class="font-black text-[#FF3EA5] uppercase text-xs mb-4 italic tracking-widest leading-none">{{ $c['title'] }}</h5>
                <ol class="space-y-4">
                    {{-- Proteksi Null: Pakai ?? [] untuk mencegah error --}}
                    @php 
                        $rawItems = $data[$c['key']] ?? ['Menyiapkan data...'];
                        $items = is_array($rawItems) ? $rawItems : [$rawItems]; 
                    @endphp
                    @foreach($items as $i => $item)
                        <li class="flex gap-3 text-xs font-bold text-[#FF3EA5] uppercase italic tracking-tighter leading-tight italic">
                            <span class="text-[#FF3EA5] opacity-50">{{ $i+1 }}.</span>
                            {{ $item }}
                        </li>
                    @endforeach
                </ol>
            </div>
            @endforeach
        </section>

        {{-- 3. Panduan Nutrisi & Keamanan (DENGAN PENGAMAN) --}}
        <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Rekomendasi Nutrisi --}}
            <div class="bg-white border-4 border-[#FF3EA5] p-10 rounded-[3rem] shadow-[12px_12px_0px_0px_#ff90c8]">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-pink-50 border-2 border-[#FF3EA5] text-[#FF3EA5] rounded-2xl flex items-center justify-center font-black text-2xl italic shadow-[3px_3px_0px_0px_#FF3EA5]">✓</div>
                    <h5 class="font-black text-[#FF3EA5] uppercase text-2xl italic tracking-tighter leading-none italic">Sangat Disarankan</h5>
                </div>
                <ul class="space-y-4">
                    @php 
                        $rawRecoms = $data['rekomendasi'] ?? ['Menunggu saran nutrisi AI...'];
                        $recoms = is_array($rawRecoms) ? $rawRecoms : [$rawRecoms]; 
                    @endphp
                    @foreach($recoms as $r)
                        <li class="flex gap-3 text-sm font-bold text-[#FF3EA5] uppercase italic tracking-tighter italic">
                            <span class="text-[#FF3EA5] text-lg">✦</span> {{ $r }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Pantangan --}}
            <div class="bg-white border-4 border-dashed border-[#FF3EA5] p-10 rounded-[3rem]">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-pink-50 border-2 border-[#FF3EA5] text-[#FF3EA5] rounded-2xl flex items-center justify-center font-black text-2xl italic opacity-60">✕</div>
                    <h5 class="font-black text-[#FF3EA5] uppercase text-2xl italic tracking-tighter leading-none italic opacity-60">Mohon Hindari</h5>
                </div>
                <ul class="space-y-4">
                    @php 
                        $rawStops = $data['hindari'] ?? ['Menunggu analisis pantangan...'];
                        $stops = is_array($rawStops) ? $rawStops : [$rawStops]; 
                    @endphp
                    @foreach($stops as $s)
                        <li class="flex gap-3 text-sm font-bold text-[#FF3EA5] uppercase italic tracking-tighter opacity-60 italic leading-snug">
                            <span class="text-[#FF3EA5] text-lg opacity-40">✕</span> {{ $s }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{-- NEW SECTION: Belanja & Persiapan Teknis --}}
        <section class="bg-white border-4 border-[#FF3EA5] rounded-[2.5rem] p-8 shadow-[8px_8px_0px_0px_#ff90c8]">
            <h4 class="font-black text-[#FF3EA5] uppercase text-sm mb-6 flex items-center gap-2 italic">
                <span class="p-1 bg-[#FF3EA5] text-white rounded text-xs">🛒</span> Rencana Persiapan & Belanja
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-bold text-[#FF3EA5] uppercase italic">
                <div class="p-4 bg-pink-50 rounded-xl border-2 border-[#FF3EA5]">
                    <span class="block mb-1 opacity-50 tracking-widest text-[10px]">Perlengkapan Mama:</span>
                    {{ $data['perlengkapan_mama'] ?? 'Pakaian Longgar, Vitamin Prenatal, Krim Stretchmark.' }}
                </div>
                <div class="p-4 bg-pink-50 rounded-xl border-2 border-[#FF3EA5]">
                    <span class="block mb-1 opacity-50 tracking-widest text-[10px]">Persiapan Kamar:</span>
                    {{ $data['persiapan_kamar'] ?? 'Cek Area Tidur, Atur Ventilasi, Siapkan Musik Relaksasi.' }}
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <div class="text-center pt-6">
            <p class="text-[8px] font-black text-[#FF3EA5] uppercase tracking-[0.4em] opacity-40 italic">Mamacare AI - Smart Pregnancy Assistant</p>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')
@section('title', 'Panduan Nutrisi - Mamacare')
@section('content')
<div class="min-h-screen py-6 md:py-10 text-[#FF3EA5] font-sans" x-data="{ activeTab: 'Semua' }">
    <div class="max-w-4xl mx-auto px-4">
        
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-[#FF3EA5] text-[#FF3EA5] font-black uppercase rounded-xl shadow-[3px_3px_0px_0px_#ff90c8] hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#ff90c8] transition-all">
                Kembali
            </a>
            <span class="bg-pink-50 text-[#FF3EA5] px-3 py-1 rounded-full text-xs font-black border-2 border-[#FF3EA5] shadow-sm">Tim Medis</span>
        </div>

        <div class="mb-10 text-center">
            <h2 class="text-3xl md:text-5xl font-black uppercase tracking-tighter">Panduan <span class="text-stroke-pink text-white">Nutrisi</span></h2>
            <p class="font-bold opacity-80 mt-2">Panduan gizi & pantangan resmi dari dokter Mamacare.</p>
        </div>

        {{-- Tab Navigation --}}
        <div class="flex flex-wrap gap-2 justify-center mb-8">
            @php
                $tabs = ['Semua', 'Trimester 1', 'Trimester 2', 'Trimester 3', 'Pantangan'];
            @endphp
            @foreach($tabs as $tab)
            <button @click="activeTab = '{{ $tab }}'"
                    :class="activeTab === '{{ $tab }}' ? 'bg-[#FF3EA5] text-white shadow-[4px_4px_0px_0px_#ff90c8] -translate-y-1' : 'bg-white text-[#FF3EA5] hover:bg-pink-50'"
                    class="px-4 py-2 rounded-xl border-2 border-[#FF3EA5] font-black uppercase text-xs md:text-sm transition-all outline-none">
                {{ $tab }}
            </button>
            @endforeach
        </div>

        {{-- Content Area --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($guides as $guide)
                <div x-show="activeTab === 'Semua' || (activeTab === 'Pantangan' && '{{ $guide->type }}' === 'Pantangan') || (activeTab === 'Trimester {{ $guide->trimester }}' && '{{ $guide->type }}' === 'Rekomendasi') || (activeTab === '{{ $guide->trimester }}' && '{{ $guide->type }}' === 'Rekomendasi')"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="bg-white border-2 border-[#FF3EA5] rounded-3xl p-6 shadow-[6px_6px_0px_0px_#ff90c8] flex flex-col justify-between" style="display: none;">
                    
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-black uppercase">{{ $guide->title }}</h3>
                            <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase text-white {{ $guide->type == 'Rekomendasi' ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ $guide->type }}
                            </span>
                        </div>
                        <p class="font-medium text-sm text-gray-700 leading-relaxed mb-4">
                            {{ $guide->description }}
                        </p>
                    </div>

                    <div class="mt-4 pt-4 border-t-2 border-dashed border-pink-100 flex items-center justify-between">
                        <div class="flex items-center gap-2 text-xs font-bold text-[#FF3EA5]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Dr. {{ $guide->doctor->name ?? 'Mamacare' }}
                        </div>
                        <span class="text-[10px] font-black opacity-50 uppercase bg-pink-50 px-2 py-1 rounded-md">
                            Trimester {{ $guide->trimester }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 text-center py-10 opacity-60">
                    <p class="font-black italic">Belum ada panduan nutrisi dari dokter.</p>
                </div>
            @endforelse
        </div>

        {{-- CTA --}}
        <div class="mt-12 text-center bg-pink-50 border-2 border-[#FF3EA5] p-6 rounded-3xl shadow-[4px_4px_0px_0px_#ff90c8]">
            <p class="font-black text-lg mb-2">Masih ragu soal makanan ini?</p>
            <p class="font-bold text-sm opacity-80 mb-6">Jangan khawatir, tanyakan langsung ke Mama AI atau pakai fitur AI Scanner Gizi!</p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('mama.food-scanner') }}" class="bg-[#FF3EA5] text-white px-6 py-3 rounded-xl font-black uppercase shadow-[3px_3px_0px_0px_#ff1b91] hover:-translate-y-1 hover:shadow-none transition-all flex justify-center items-center gap-2">
                    📸 AI Scanner Gizi
                </a>
                <a href="{{ route('mama.ai') }}" class="bg-white text-[#FF3EA5] border-2 border-[#FF3EA5] px-6 py-3 rounded-xl font-black uppercase shadow-[3px_3px_0px_0px_#ff90c8] hover:-translate-y-1 hover:shadow-none transition-all flex justify-center items-center gap-2">
                    💬 Tanya Mama AI
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

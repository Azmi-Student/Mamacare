@extends('layouts.app')

@section('title', 'Artikel & Tips - Mamacare')

@section('content')
    <style>
        :root {
            --pink: #ff47a1;
            --white: #ffffff;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Tambahan agar kata yang sangat panjang tidak merusak layout */
        .break-words {
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
        }
    </style>

    <div x-data="{ activeTab: 'Semua', activeTrimester: '1' }" class="min-h-screen pb-24 text-[#ff47a1] max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- HEADER --}}
        <div class="mt-6 mb-8 border-b-2 border-[#ff47a1] pb-4">
            {{-- Tambah break-words untuk judul --}}
            <h1 class="text-2xl md:text-4xl font-black uppercase tracking-tight mb-2 break-words leading-tight">
                Bacaan Bunda
            </h1>
            <p class="font-medium text-sm md:text-base opacity-80 max-w-2xl break-words">
                Ensiklopedia kehamilan terlengkap. Temukan tips kesehatan dan panduan tumbuh kembang janin di sini.
            </p>
        </div>

        {{-- 1. MAIN FILTER --}}
        <div class="flex flex-nowrap md:flex-wrap gap-3 mb-6 overflow-x-auto pb-2 no-scrollbar -mx-4 px-4 md:mx-0 md:px-0">
            @php $mainTabs = ['Semua', 'Harian', 'Mingguan', 'Bulanan', 'Trimester']; @endphp
            @foreach($mainTabs as $tab)
                <button @click="activeTab = '{{ $tab }}'"
                    class="shrink-0 px-5 py-2 rounded-xl font-bold uppercase text-[10px] md:text-xs tracking-wide border-2 transition-all duration-150"
                    :class="activeTab === '{{ $tab }}' ? 'bg-[#ff47a1] text-white border-[#ff47a1] shadow-[3px_3px_0px_0px_rgba(255,255,255,0.5)]' : 'bg-white text-[#ff47a1] border-[#ff47a1] shadow-[3px_3px_0px_0px_#ff47a1] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-none'">
                    {{ $tab }}
                </button>
            @endforeach
        </div>

        {{-- 2. SUB FILTER --}}
        <div x-show="activeTab === 'Trimester'" x-transition
             class="flex items-center gap-4 mb-8 p-3 border-2 border-[#ff47a1] w-full md:w-fit bg-white shadow-[3px_3px_0px_0px_#ff47a1] rounded-xl overflow-hidden">
            <span class="text-xs md:text-sm font-bold uppercase whitespace-nowrap">Pilih Trimester:</span>
            <div class="flex gap-2 w-full md:w-auto">
                @foreach(['1', '2', '3'] as $tri)
                    <button @click="activeTrimester = '{{ $tri }}'"
                        class="flex-1 md:flex-none w-10 h-10 rounded-lg border-2 font-black text-sm flex items-center justify-center transition-all"
                        :class="activeTrimester === '{{ $tri }}' ? 'bg-[#ff47a1] text-white border-[#ff47a1]' : 'bg-white text-[#ff47a1] border-[#ff47a1] hover:bg-pink-50'">
                        {{ $tri }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- 3. CONTENT GRID - Versi Mini (4 Kolom di XL, 3 Kolom di LG) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-5">
    @foreach($articles as $article)
        <div x-show="(activeTab === 'Semua') || (activeTab === '{{ $article->category }}') || (activeTab === 'Trimester' && '{{ $article->category }}' === 'Trimester ' + activeTrimester)"
             x-transition:enter="transition ease-out duration-300"
             class="bg-white border-2 border-[#ff47a1] rounded-2xl shadow-[3px_3px_0px_0px_#ff47a1] hover:shadow-none hover:translate-x-[3px] hover:translate-y-[3px] transition-all flex flex-col h-full group relative overflow-hidden min-w-0">
            
            {{-- IMAGE - Kembali ke Square agar Tidak Kepotong, tapi Card mengecil karena grid-nya banyak --}}
            <div class="aspect-square w-full overflow-hidden border-b-2 border-[#ff47a1] relative bg-pink-50 shrink-0">
                @if($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" 
                         alt="{{ $article->title }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center text-[#ff47a1] font-black text-[10px] uppercase opacity-40 italic text-center">
                        Mamacare <br> Photo
                    </div>
                @endif
                
                {{-- Badge Kategori Mungil --}}
                <div class="absolute top-2 left-2">
                    <span class="bg-white text-[#ff47a1] text-[9px] font-black uppercase px-2 py-0.5 rounded-lg border-2 border-[#ff47a1] shadow-[1.5px_1.5px_0px_0px_#ff47a1]">
                        {{ $article->category }}
                    </span>
                </div>
            </div>

            {{-- Bagian Teks: P-4 (Rapat) --}}
            <div class="p-4 flex flex-col flex-1 min-w-0">
                {{-- Meta --}}
<div class="flex items-center flex-wrap gap-y-1 gap-x-2 mb-2 text-[9px] font-bold uppercase tracking-wider opacity-60">
    {{-- Penulis --}}
    <span class="truncate max-w-[80px]">{{ $article->author->name ?? 'Admin' }}</span>
    
    <span>•</span>
    
    {{-- Tanggal --}}
    <span class="shrink-0">{{ $article->created_at->format('d/m/y') }}</span>

    <span>•</span>

    {{-- VIEWS (Jumlah Pembaca) --}}
    <div class="flex items-center gap-1 shrink-0" title="Dibaca {{ $article->views }} kali">
        {{-- Ikon Mata --}}
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
        {{-- Angka Views (diberi default 0 jika null) --}}
        <span>{{ number_format($article->views ?? 0) }}</span>
    </div>
</div>

                {{-- Judul: text-sm (Lebih Mungil) --}}
                <h3 class="text-sm md:text-base font-black leading-tight mb-2 group-hover:text-[#ff47a1] line-clamp-2 break-words uppercase">
                    {{ $article->title }}
                </h3>

                {{-- Excerpt: text-[11px] (2 Baris Saja) --}}
                <p class="text-[11px] font-medium leading-snug mb-4 line-clamp-2 opacity-80 break-words text-gray-500">
                    {{ $article->excerpt }}
                </p>

                {{-- Tombol: Keren & Interaktif --}}
<div class="mt-auto pt-4">
    <a href="{{ route('artikel.show', $article->slug) }}" 
       class="group/btn relative flex items-center justify-center w-full py-2.5 px-4 
              bg-white border-2 border-[#ff47a1] rounded-xl 
              text-[#ff47a1] text-[10px] md:text-xs font-black uppercase tracking-widest
              shadow-[3px_3px_0px_0px_#ff47a1] 
              hover:shadow-none hover:translate-x-[3px] hover:translate-y-[3px] 
              hover:bg-[#ff47a1] hover:text-white 
              transition-all duration-200 gap-2">
        
        <span>Baca Lengkap</span>
        
        {{-- Ikon Panah yang bergerak saat hover --}}
        <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover/btn:translate-x-1" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
        </svg>
    </a>
</div>
            </div>
        </div>
    @endforeach
</div>

        {{-- EMPTY STATE --}}
        <div x-data x-show="$nextTick(() => { const visible = Array.from(document.querySelectorAll('.group')).filter(el => el.style.display !== 'none'); return visible.length === 0; })" 
             class="hidden text-center py-20 px-4">
             <div class="inline-block border-2 border-[#ff47a1] px-6 md:px-8 py-6 shadow-[4px_4px_0px_0px_#ff47a1] rounded-2xl bg-white max-w-full">
                 <svg class="w-12 h-12 mx-auto mb-3 text-[#ff47a1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                 <p class="font-black text-sm md:text-base uppercase tracking-widest break-words">Belum ada artikel</p>
                 <p class="text-xs mt-1 opacity-70">Silakan pilih kategori lain.</p>
             </div>
        </div>

    </div>
@endsection
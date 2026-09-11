@extends('layouts.app')

@section('title', $article->title . ' - Mamacare')

@section('content')
    <style>
        :root {
            --pink: #ff47a1;
            --white: #ffffff;
        }
        /* Memastikan konten dari Rich Text Editor (Trix/HTML) patuh pada layout */
        .prose-neo { 
            color: var(--pink) !important; 
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .prose-neo h1, .prose-neo h2, .prose-neo h3, .prose-neo h4, .prose-neo h5, .prose-neo h6 { 
            color: var(--pink) !important; 
            font-weight: 800; 
            text-transform: uppercase; 
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        /* Mengatur gambar di dalam artikel agar tidak melebar keluar */
        .prose-neo img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 1rem;
            border: 2px solid var(--pink);
            margin: 1.5rem 0;
        }
        .prose-neo strong, .prose-neo b { color: var(--pink) !important; font-weight: 900; }
        .prose-neo a { color: var(--pink) !important; text-decoration: underline; }
        .prose-neo p, .prose-neo li { color: var(--pink) !important; margin-bottom: 1rem; }
        .prose-neo blockquote { 
            border-left-color: var(--pink); 
            color: var(--pink) !important; 
            font-style: italic; 
            background: #fff0f7; 
            padding: 1rem; 
            border-radius: 0 1rem 1rem 0; 
            margin: 1.5rem 0;
        }
        /* Custom Line Clamp untuk Sidebar */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 text-[#ff47a1]">
        
        {{-- BACK BUTTON --}}
        <div class="mb-6 mt-4">
            <a href="{{ route('artikel.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-[#FF3EA5] text-[#FF3EA5] font-black uppercase rounded-xl shadow-[3px_3px_0px_0px_#FF3EA5] hover:bg-pink-50 hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all text-[10px] md:text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            
            {{-- KOLOM UTAMA (ARTIKEL) --}}
            <div class="flex-1 min-w-0">
                <article class="bg-white border-2 border-[#ff47a1] rounded-3xl overflow-hidden shadow-[4px_4px_0px_0px_#ff47a1]">
                    
                    {{-- Hero Image (Aspect Video agar proporsional) --}}
                    <div class="aspect-[21/9] w-full border-b-2 border-[#ff47a1] bg-pink-50 relative overflow-hidden group">
                        @if($article->image)
                            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-[#ff47a1] font-black text-sm uppercase opacity-50">No Image</div>
                        @endif
                        
                        <div class="absolute top-4 left-4">
                            <span class="bg-white text-[#ff47a1] px-3 py-1.5 rounded-lg text-[10px] font-black uppercase border-2 border-[#ff47a1] shadow-sm">
                                {{ $article->category }}
                            </span>
                        </div>
                    </div>

                    {{-- Body Content --}}
                    <div class="p-5 md:p-10">
                        {{-- Judul: break-words penting agar tidak kepotong --}}
                        <h1 class="text-2xl md:text-4xl font-black leading-tight mb-6 uppercase text-[#ff47a1] break-words">
                            {{ $article->title }}
                        </h1>

                        {{-- Meta Bar: Responsif Stack di Mobile --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-y-2 border-[#ff47a1] py-4 mb-8 gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 shrink-0 bg-white rounded-full border-2 border-[#ff47a1] flex items-center justify-center text-[#ff47a1]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[9px] opacity-70 font-bold uppercase tracking-widest">Penulis</p>
                                    <p class="text-xs font-black truncate">{{ $article->author->name ?? 'Admin' }}</p>
                                </div>
                            </div>
                            <div class="sm:text-right shrink-0">
                                <p class="text-[9px] opacity-70 font-bold uppercase tracking-widest">Tanggal Terbit</p>
                                <p class="text-xs font-black">{{ $article->created_at->format('d M Y') }}</p>
                            </div>
                        </div>

                        {{-- Main Typography Content --}}
                        <div class="prose-neo max-w-none">
                            {{-- Excerpt / Ringkasan --}}
                            <p class="font-bold text-lg md:text-xl text-[#ff47a1] border-l-4 border-[#ff47a1] pl-4 italic break-words mb-8 leading-relaxed">
                                {{ $article->excerpt }}
                            </p>
                            
                            {{-- Content dari Editor --}}
                            <div class="text-[#ff47a1] break-words leading-relaxed text-sm md:text-base">
                                {!! $article->content !!}
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            {{-- KOLOM KANAN (SIDEBAR) --}}
            <div class="lg:w-80 shrink-0">
                <div class="lg:sticky lg:top-6 space-y-8">
                    
                    {{-- Rekomendasi Bacaan --}}
                    <div>
                        <h3 class="text-lg font-black text-[#ff47a1] uppercase mb-4 flex items-center gap-2">
                            <span class="w-2 h-6 bg-[#ff47a1] rounded-sm"></span>
                            Baca Juga
                        </h3>

                        <div class="flex flex-col gap-4">
                            @foreach($related as $rel)
                                <a href="{{ route('artikel.show', $rel->slug) }}" class="group block bg-white border-2 border-[#ff47a1] rounded-2xl p-3 shadow-[3px_3px_0px_0px_#ff47a1] hover:shadow-none hover:translate-x-[1px] hover:translate-y-[1px] transition-all">
                                    <div class="flex gap-3">
                                        <div class="w-20 h-20 shrink-0 rounded-xl overflow-hidden border-2 border-[#ff47a1] bg-pink-50">
                                            @if($rel->image)
                                                <img src="{{ asset('storage/' . $rel->image) }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-[8px] font-black text-[#ff47a1] uppercase opacity-40">No Img</div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0 flex flex-col justify-center">
                                            <span class="text-[8px] font-black uppercase text-[#ff47a1] mb-1 border border-[#ff47a1] px-1.5 rounded-md w-fit bg-pink-50">
                                                {{ $rel->category }}
                                            </span>
                                            <h4 class="font-bold text-xs leading-tight text-[#ff47a1] line-clamp-2 break-words uppercase">
                                                {{ $rel->title }}
                                            </h4>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Banner Promo / CTA --}}
                    <div class="bg-[#ff47a1] rounded-3xl p-6 text-white text-center shadow-[4px_4px_0px_0px_#ff90c8] border-2 border-[#ff47a1]">
                        <p class="font-bold text-[10px] uppercase opacity-90 mb-2 tracking-widest">Butuh Jawaban Medis?</p>
                        <h4 class="font-black text-xl mb-5 uppercase leading-tight">Konsultasi dengan Dokter</h4>
                        <a href="{{ route('mama.tanya-dokter') }}" class="inline-block w-full bg-white text-[#ff47a1] font-black text-xs uppercase px-6 py-3 rounded-xl hover:bg-pink-50 border-2 border-white transition-all active:scale-95 shadow-md">
                            Chat Sekarang
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
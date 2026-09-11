@extends('layouts.app')

@section('title', 'Manajemen Artikel')

@section('content')
    <style>
        :root {
            --pink: #ff47a1;
            --white: #ffffff;
        }
        /* Custom Scrollbar */
        .scrollbar-pink::-webkit-scrollbar { height: 8px; width: 8px; }
        .scrollbar-pink::-webkit-scrollbar-track { background: #fff0f7; }
        .scrollbar-pink::-webkit-scrollbar-thumb { background: var(--pink); border-radius: 4px; }

        /* Mencegah teks meluap di kolom info */
        .break-text {
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 text-[#ff47a1]">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 border-b-2 border-[#ff47a1] pb-6 mt-4">
            <div class="min-w-0">
                <h1 class="text-2xl md:text-3xl font-black uppercase tracking-tight mb-1 break-words">
                    Manajemen Artikel
                </h1>
                <p class="font-bold text-sm opacity-80">
                    Kelola tulisan edukasi Anda di sini.
                </p>
            </div>
            
            <a href="{{ route('dokter.kelola-artikel.create') }}" class="group inline-flex items-center justify-center gap-2 bg-[#ff47a1] text-white px-6 py-3 rounded-xl font-black uppercase text-xs border-2 border-[#ff47a1] shadow-[4px_4px_0px_0px_#ffffff] hover:bg-white hover:text-[#ff47a1] hover:shadow-[4px_4px_0px_0px_#ff47a1] transition-all active:scale-95 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Tulis Artikel Baru
            </a>
        </div>

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
            <div class="mb-6 bg-white border-2 border-[#ff47a1] p-4 rounded-xl shadow-[3px_3px_0px_0px_#ff47a1] flex justify-between items-center animate-bounce-once">
                <div class="flex items-center gap-3">
                    <div class="bg-[#ff47a1] text-white p-1 rounded-full shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-bold text-sm break-text">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="font-black text-xl hover:scale-125 transition-transform px-2">&times;</button>
            </div>
        @endif

        {{-- SEARCH & FILTER SECTION --}}
        <div class="bg-white border-2 border-[#ff47a1] p-4 rounded-2xl mb-8 shadow-[4px_4px_0px_0px_#ff47a1]">
            <form action="{{ route('dokter.kelola-artikel.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
                
                {{-- Input Search --}}
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul artikel..." 
                        class="w-full pl-10 pr-4 py-3 bg-pink-50 border-2 border-[#ff47a1] rounded-xl text-sm font-bold placeholder-[#ff47a1]/50 text-[#ff47a1] focus:outline-none focus:bg-white focus:shadow-[2px_2px_0px_0px_#ff47a1] transition-all">
                    <svg class="w-4 h-4 absolute left-3.5 top-4 text-[#ff47a1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                {{-- Filter Status --}}
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1 sm:w-48">
                        <select name="status" onchange="this.form.submit()" 
                            class="appearance-none w-full px-4 py-3 bg-white border-2 border-[#ff47a1] rounded-xl text-sm font-bold text-[#ff47a1] focus:outline-none cursor-pointer pr-10 hover:bg-pink-50 transition-colors">
                            <option value="">Semua Status</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Terbit</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                        <svg class="w-4 h-4 absolute right-3.5 top-4 text-[#ff47a1] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                    </div>

                    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-[#ff47a1] text-white font-black uppercase text-xs rounded-xl border-2 border-[#ff47a1] hover:bg-white hover:text-[#ff47a1] transition-all shadow-[2px_2px_0px_0px_rgba(0,0,0,0.1)] active:scale-95">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        {{-- TABEL --}}
        <div class="bg-white border-2 border-[#ff47a1] rounded-2xl overflow-hidden shadow-[6px_6px_0px_0px_#ff47a1] min-w-0">
            <div class="overflow-x-auto scrollbar-pink">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead class="bg-[#ff47a1] text-white">
                        <tr>
                            <th class="p-5 text-xs font-black uppercase tracking-wider border-b-2 border-white w-24 shrink-0">Cover</th>
                            <th class="p-5 text-xs font-black uppercase tracking-wider border-b-2 border-white">Info Artikel</th>
                            <th class="p-5 text-xs font-black uppercase tracking-wider border-b-2 border-white w-44">Status (Ubah)</th>
                            <th class="p-5 text-xs font-black uppercase tracking-wider border-b-2 border-white text-right w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-[#ff47a1]/20">
                        @forelse($articles as $article)
                            <tr class="hover:bg-pink-50 transition-colors group">
                                
                                {{-- Kolom 1: Gambar --}}
                                <td class="p-5 align-top">
                                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-xl border-2 border-[#ff47a1] overflow-hidden bg-white shadow-sm shrink-0">
                                        @if($article->image)
                                            <img src="{{ asset('storage/' . $article->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center bg-pink-50 text-[#ff47a1]">
                                                <svg class="w-6 h-6 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                
                                {{-- Kolom 2: Info --}}
                                <td class="p-5 align-top min-w-0">
                                    <span class="inline-block text-[10px] font-black uppercase text-white bg-[#ff47a1] px-2 py-1 rounded mb-2 border border-[#ff47a1]">
                                        {{ $article->category }}
                                    </span>
                                    <h3 class="text-sm md:text-base font-black text-[#ff47a1] leading-tight mb-2 break-text line-clamp-2">
                                        {{ $article->title }}
                                    </h3>
                                    <p class="text-[11px] md:text-xs font-bold text-[#ff47a1] opacity-70 line-clamp-2 break-text">
                                        {{ $article->excerpt }}
                                    </p>
                                    <div class="mt-3 text-[9px] font-bold text-[#ff47a1] opacity-50 uppercase tracking-widest flex flex-wrap gap-2">
                                        <span>Dibuat: {{ $article->created_at->format('d M Y') }}</span>
                                        <span class="hidden sm:inline">•</span>
                                        <span>Views: {{ $article->views ?? 0 }}</span>
                                    </div>
                                </td>

                                {{-- Kolom 3: STATUS DROPDOWN --}}
                                <td class="p-5 align-top">
                                    <form action="{{ route('dokter.kelola-artikel.updateStatus', $article->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="relative w-full max-w-[140px]">
                                            <select name="status" onchange="this.form.submit()" 
                                                class="appearance-none w-full pl-3 pr-8 py-2 rounded-lg text-[10px] font-black uppercase border-2 border-[#ff47a1] focus:outline-none cursor-pointer transition-all
                                                {{ $article->status == 'published' ? 'bg-[#ff47a1] text-white' : 'bg-white text-[#ff47a1] border-dashed' }}">
                                                <option value="published" {{ $article->status == 'published' ? 'selected' : '' }} class="bg-white text-[#ff47a1] font-bold">Terbit</option>
                                                <option value="draft" {{ $article->status == 'draft' ? 'selected' : '' }} class="bg-white text-[#ff47a1] font-bold">Draft</option>
                                            </select>
                                            <div class="absolute right-2.5 top-2.5 pointer-events-none {{ $article->status == 'published' ? 'text-white' : 'text-[#ff47a1]' }}">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                    </form>
                                </td>

                                {{-- Kolom 4: Aksi --}}
                                <td class="p-5 align-top text-right">
                                    <div class="flex items-center justify-end gap-2 shrink-0">
                                        <a href="{{ route('dokter.kelola-artikel.edit', $article->id) }}" class="p-2.5 bg-white border-2 border-[#ff47a1] text-[#ff47a1] rounded-xl hover:bg-[#ff47a1] hover:text-white transition-all shadow-[2px_2px_0px_0px_#ff47a1] hover:shadow-none hover:translate-x-[1px] hover:translate-y-[1px]" title="Edit Konten">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        
                                        <form action="{{ route('dokter.kelola-artikel.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Hapus artikel ini secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2.5 bg-white border-2 border-[#ff47a1] text-[#ff47a1] rounded-xl hover:bg-red-500 hover:border-red-500 hover:text-white transition-all shadow-[2px_2px_0px_0px_#ff47a1] hover:shadow-none hover:translate-x-[1px] hover:translate-y-[1px]" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-12 text-center">
                                    <div class="inline-flex flex-col items-center justify-center p-8 border-2 border-dashed border-[#ff47a1] rounded-2xl bg-pink-50 max-w-full">
                                        <div class="w-16 h-16 bg-white border-2 border-[#ff47a1] rounded-full flex items-center justify-center mb-4 text-[#ff47a1]">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                        </div>
                                        <p class="font-black uppercase text-base mb-1 text-[#ff47a1]">Data Tidak Ditemukan</p>
                                        <p class="text-xs font-bold text-[#ff47a1] opacity-70 mb-6">Artikel yang Anda cari tidak ada atau belum dibuat.</p>
                                        <a href="{{ route('dokter.kelola-artikel.create') }}" class="text-xs font-black bg-[#ff47a1] text-white px-6 py-2.5 rounded-lg hover:bg-white hover:text-[#ff47a1] border-2 border-[#ff47a1] transition-colors shadow-sm">
                                            + Buat Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="p-4 border-t-2 border-[#ff47a1] bg-white">
                {{ $articles->withQueryString()->links() }} 
            </div>
        </div>
    </div>
@endsection
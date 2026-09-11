@extends('layouts.app')

@section('title', 'Manajemen Nutrisi')

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
                    Manajemen Nutrisi
                </h1>
                <p class="font-bold text-sm opacity-80">
                    Kelola daftar makanan rekomendasi dan pantangan untuk Mama.
                </p>
            </div>
            
            <a href="{{ route('dokter.kelola-nutrisi.create') }}" class="group inline-flex items-center justify-center gap-2 bg-[#ff47a1] text-white px-6 py-3 rounded-xl font-black uppercase text-xs border-2 border-[#ff47a1] shadow-[4px_4px_0px_0px_#ffffff] hover:bg-white hover:text-[#ff47a1] hover:shadow-[4px_4px_0px_0px_#ff47a1] transition-all active:scale-95 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Tambah Panduan
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

        {{-- TABEL --}}
        <div class="bg-white border-2 border-[#ff47a1] rounded-2xl overflow-hidden shadow-[6px_6px_0px_0px_#ff47a1] min-w-0">
            <div class="overflow-x-auto scrollbar-pink">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead class="bg-[#ff47a1] text-white">
                        <tr>
                            <th class="p-5 text-xs font-black uppercase tracking-wider border-b-2 border-white">Info Makanan / Gizi</th>
                            <th class="p-5 text-xs font-black uppercase tracking-wider border-b-2 border-white w-40 text-center">Tipe</th>
                            <th class="p-5 text-xs font-black uppercase tracking-wider border-b-2 border-white text-right w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-[#ff47a1]/20">
                        @forelse($guides as $guide)
                            <tr class="hover:bg-pink-50 transition-colors group">
                                
                                {{-- Kolom 1: Info --}}
                                <td class="p-5 align-top min-w-0">
                                    <span class="inline-block text-[10px] font-black uppercase text-white bg-[#ff47a1] px-2 py-1 rounded mb-2 border border-[#ff47a1]">
                                        Trimester {{ $guide->trimester }}
                                    </span>
                                    <h3 class="text-sm md:text-base font-black text-[#ff47a1] leading-tight mb-2 break-text line-clamp-2">
                                        {{ $guide->title }}
                                    </h3>
                                    <p class="text-[11px] md:text-xs font-bold text-[#ff47a1] opacity-70 line-clamp-2 break-text">
                                        {{ Str::limit($guide->description, 100) }}
                                    </p>
                                </td>

                                {{-- Kolom 2: Tipe --}}
                                <td class="p-5 align-top text-center">
                                    <div class="inline-block px-3 py-1 rounded-lg border-2 text-[10px] font-black uppercase {{ $guide->type == 'Rekomendasi' ? 'bg-green-100 text-green-700 border-green-400' : 'bg-red-100 text-red-700 border-red-400' }}">
                                        {{ $guide->type }}
                                    </div>
                                </td>

                                {{-- Kolom 3: Aksi --}}
                                <td class="p-5 align-top text-right">
                                    <div class="flex items-center justify-end gap-2 shrink-0">
                                        <a href="{{ route('dokter.kelola-nutrisi.edit', $guide->id) }}" class="p-2.5 bg-white border-2 border-[#ff47a1] text-[#ff47a1] rounded-xl hover:bg-[#ff47a1] hover:text-white transition-all shadow-[2px_2px_0px_0px_#ff47a1] hover:shadow-none hover:translate-x-[1px] hover:translate-y-[1px]" title="Edit Konten">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        
                                        <form action="{{ route('dokter.kelola-nutrisi.destroy', $guide->id) }}" method="POST" onsubmit="return confirm('Hapus panduan ini secara permanen?')">
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
                                <td colspan="3" class="p-12 text-center">
                                    <div class="inline-flex flex-col items-center justify-center p-8 border-2 border-dashed border-[#ff47a1] rounded-2xl bg-pink-50 max-w-full">
                                        <div class="w-16 h-16 bg-white border-2 border-[#ff47a1] rounded-full flex items-center justify-center mb-4 text-[#ff47a1]">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        </div>
                                        <p class="font-black uppercase text-base mb-1 text-[#ff47a1]">Belum Ada Data</p>
                                        <p class="text-xs font-bold text-[#ff47a1] opacity-70 mb-6">Anda belum menambahkan pedoman nutrisi apa pun.</p>
                                        <a href="{{ route('dokter.kelola-nutrisi.create') }}" class="text-xs font-black bg-[#ff47a1] text-white px-6 py-2.5 rounded-lg hover:bg-white hover:text-[#ff47a1] border-2 border-[#ff47a1] transition-colors shadow-sm">
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
                {{ $guides->withQueryString()->links() }} 
            </div>
        </div>
    </div>
@endsection

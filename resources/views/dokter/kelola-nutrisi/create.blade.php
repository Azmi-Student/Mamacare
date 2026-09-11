@extends('layouts.app')

@section('title', 'Tulis Panduan Baru')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 text-[#ff47a1]">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 border-b-2 border-[#ff47a1] pb-6 mt-4">
            <div class="min-w-0">
                <a href="{{ route('dokter.kelola-nutrisi.index') }}" class="inline-flex items-center gap-2 text-xs font-black uppercase hover:opacity-70 transition-opacity mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
                <h1 class="text-2xl md:text-3xl font-black uppercase tracking-tight mb-1 break-words">
                    Tulis Panduan Baru
                </h1>
                <p class="font-bold text-sm opacity-80">
                    Tambahkan informasi nutrisi baru untuk ibu hamil.
                </p>
            </div>
        </div>

        {{-- FORM WRAPPER --}}
        <div class="bg-white border-2 border-[#ff47a1] rounded-2xl overflow-hidden shadow-[6px_6px_0px_0px_#ff47a1]">
            <form action="{{ route('dokter.kelola-nutrisi.store') }}" method="POST" class="p-6 md:p-8">
                @csrf
                
                {{-- Judul Makanan --}}
                <div class="mb-6">
                    <label class="block text-sm font-black uppercase mb-2">Nama Makanan / Gizi <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Misal: Asam Folat, Daging Mentah, Kopi" 
                        class="w-full px-4 py-3 bg-pink-50 border-2 border-[#ff47a1] rounded-xl text-sm font-bold text-[#ff47a1] placeholder-[#ff47a1]/40 focus:outline-none focus:bg-white focus:shadow-[2px_2px_0px_0px_#ff47a1] transition-all">
                    @error('title') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Tipe & Trimester --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-black uppercase mb-2">Tipe Panduan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="type" required class="appearance-none w-full px-4 py-3 bg-pink-50 border-2 border-[#ff47a1] rounded-xl text-sm font-bold text-[#ff47a1] focus:outline-none focus:bg-white focus:shadow-[2px_2px_0px_0px_#ff47a1] transition-all cursor-pointer pr-10">
                                <option value="Rekomendasi" {{ old('type') == 'Rekomendasi' ? 'selected' : '' }}>Rekomendasi (Aman)</option>
                                <option value="Pantangan" {{ old('type') == 'Pantangan' ? 'selected' : '' }}>Pantangan (Bahaya)</option>
                            </select>
                            <svg class="w-4 h-4 absolute right-4 top-4 text-[#ff47a1] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                        @error('type') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-black uppercase mb-2">Trimester <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="trimester" required class="appearance-none w-full px-4 py-3 bg-pink-50 border-2 border-[#ff47a1] rounded-xl text-sm font-bold text-[#ff47a1] focus:outline-none focus:bg-white focus:shadow-[2px_2px_0px_0px_#ff47a1] transition-all cursor-pointer pr-10">
                                <option value="Umum" {{ old('trimester') == 'Umum' ? 'selected' : '' }}>Umum (Semua Trimester)</option>
                                <option value="1" {{ old('trimester') == '1' ? 'selected' : '' }}>Trimester 1</option>
                                <option value="2" {{ old('trimester') == '2' ? 'selected' : '' }}>Trimester 2</option>
                                <option value="3" {{ old('trimester') == '3' ? 'selected' : '' }}>Trimester 3</option>
                            </select>
                            <svg class="w-4 h-4 absolute right-4 top-4 text-[#ff47a1] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                        @error('trimester') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-8">
                    <label class="block text-sm font-black uppercase mb-2">Penjelasan Medis <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="6" required placeholder="Jelaskan alasan kenapa makanan ini direkomendasikan / dilarang..." 
                        class="w-full px-4 py-3 bg-pink-50 border-2 border-[#ff47a1] rounded-xl text-sm font-bold text-[#ff47a1] placeholder-[#ff47a1]/40 focus:outline-none focus:bg-white focus:shadow-[2px_2px_0px_0px_#ff47a1] transition-all resize-y">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- TOMBOL SUBMIT --}}
                <div class="flex justify-end border-t-2 border-dashed border-[#ff47a1]/30 pt-6">
                    <button type="submit" class="w-full md:w-auto px-8 py-3 bg-[#ff47a1] text-white font-black uppercase text-sm rounded-xl border-2 border-[#ff47a1] hover:bg-white hover:text-[#ff47a1] transition-all shadow-[4px_4px_0px_0px_rgba(255,71,161,0.2)] hover:shadow-[4px_4px_0px_0px_#ff47a1] active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Panduan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

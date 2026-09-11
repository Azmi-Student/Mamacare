@extends('layouts.app')

@section('title', 'Tulis Artikel Baru')

@section('content')
    {{-- 1. LOAD TRIX EDITOR (CDN) --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    <style>
        :root { --pink: #ff47a1; --white: #ffffff; }
        
        /* Style Input File */
        input[type="file"]::file-selector-button {
            background-color: var(--white); color: var(--pink); border: 2px solid var(--pink);
            padding: 6px 12px; margin-right: 15px; font-weight: 900; text-transform: uppercase;
            font-size: 10px; cursor: pointer; transition: all 0.2s; border-radius: 8px;
        }
        input[type="file"]::file-selector-button:hover { background-color: var(--pink); color: var(--white); }

        /* CUSTOM STYLE TRIX EDITOR (BIAR PINK) */
        trix-toolbar .trix-button--icon { color: var(--pink) !important; }
        trix-toolbar .trix-button.trix-active { background: #ffeaf5 !important; }
        trix-editor {
            border: 2px solid var(--pink) !important;
            border-radius: 0.75rem !important;
            padding: 1.25rem !important;
            min-height: 300px;
            background: white;
            color: #ff47a1;
        }
        trix-editor:focus {
            outline: none !important;
            box-shadow: 4px 4px 0px 0px #ff47a1 !important;
        }
        .trix-button-group--file-tools { display: none !important; }
    </style>

    <div class="max-w-4xl mx-auto pb-20 text-[#ff47a1]">
        
        <div class="flex items-center justify-between mb-8 border-b-2 border-[#ff47a1] pb-4">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tight mb-1">Tulis Artikel</h1>
                <p class="text-xs font-bold opacity-80">Bagikan ilmu kesehatan untuk para Bunda.</p>
            </div>
            <a href="{{ route('dokter.kelola-artikel.index') }}" class="group flex items-center gap-2 text-xs font-black uppercase hover:underline">
                <span class="w-6 h-6 border-2 border-[#ff47a1] rounded-full flex items-center justify-center group-hover:bg-[#ff47a1] group-hover:text-white transition-colors">&larr;</span>
                Batal
            </a>
        </div>

        <div class="bg-white border-2 border-[#ff47a1] rounded-3xl p-6 md:p-10 shadow-[8px_8px_0px_0px_#ff47a1]">
            <form action="{{ route('dokter.kelola-artikel.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- 1. JUDUL --}}
                <div class="space-y-2">
                    <label class="block text-xs font-black uppercase tracking-widest ml-1">Judul Artikel</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Tips Mengatasi Morning Sickness..." 
                        class="w-full bg-white border-2 border-[#ff47a1] rounded-xl px-5 py-4 font-bold text-lg text-[#ff47a1] placeholder-[#ff47a1]/40 focus:outline-none focus:shadow-[4px_4px_0px_0px_#ff47a1] transition-all">
                    @error('title') <p class="text-xs font-bold mt-1 bg-[#ff47a1] text-white px-2 py-1 inline-block rounded">{{ $message }}</p> @enderror
                </div>

                {{-- 2. EXCERPT (BARU: Ringkasan Artikel) --}}
                <div class="space-y-2">
                    <label class="block text-xs font-black uppercase tracking-widest ml-1">Ringkasan (Excerpt)</label>
                    <textarea name="excerpt" rows="3" placeholder="Tulis ringkasan singkat artikel di sini (maks 150 kata)..." 
                        class="w-full bg-white border-2 border-[#ff47a1] rounded-xl px-5 py-3 font-bold text-sm text-[#ff47a1] placeholder-[#ff47a1]/40 focus:outline-none focus:shadow-[4px_4px_0px_0px_#ff47a1] transition-all">{{ old('excerpt') }}</textarea>
                    <p class="text-[10px] font-bold opacity-60 ml-1 italic">*Teks ini akan muncul di kartu artikel halaman depan.</p>
                    @error('excerpt') <p class="text-xs font-bold mt-1 bg-[#ff47a1] text-white px-2 py-1 inline-block rounded">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- 3. KATEGORI --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-black uppercase tracking-widest ml-1">Kategori</label>
                        <div class="relative">
                            <select name="category" class="w-full bg-white border-2 border-[#ff47a1] rounded-xl px-5 py-4 font-bold text-sm text-[#ff47a1] focus:outline-none focus:shadow-[4px_4px_0px_0px_#ff47a1] appearance-none cursor-pointer transition-all">
                                <option value="" disabled {{ old('category') ? '' : 'selected' }}>Pilih Kategori...</option>
                                @foreach(['Harian', 'Mingguan', 'Bulanan', 'Trimester 1', 'Trimester 2', 'Trimester 3'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-5 top-5 pointer-events-none">
                                <svg class="w-4 h-4 text-[#ff47a1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('category') <p class="text-xs font-bold mt-1 bg-[#ff47a1] text-white px-2 py-1 inline-block rounded">{{ $message }}</p> @enderror
                    </div>

                    {{-- 4. STATUS --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-black uppercase tracking-widest ml-1">Status Publikasi</label>
                        <div class="flex gap-4 h-[58px] items-center">
                            <label class="cursor-pointer flex items-center gap-3 bg-pink-50 border-2 border-[#ff47a1] px-4 py-3 rounded-xl hover:bg-white transition-colors flex-1 justify-center">
                                <input type="radio" name="status" value="published" {{ old('status') == 'published' ? 'checked' : '' }} class="accent-[#ff47a1] w-5 h-5 border-2 border-[#ff47a1]">
                                <span class="text-xs font-black uppercase">Terbit</span>
                            </label>
                            <label class="cursor-pointer flex items-center gap-3 bg-pink-50 border-2 border-[#ff47a1] px-4 py-3 rounded-xl hover:bg-white transition-colors flex-1 justify-center">
                                <input type="radio" name="status" value="draft" {{ old('status') == 'draft' || !old('status') ? 'checked' : '' }} class="accent-[#ff47a1] w-5 h-5 border-2 border-[#ff47a1]">
                                <span class="text-xs font-black uppercase">Draft</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- 5. GAMBAR COVER --}}
                <div class="space-y-2">
                    <label class="block text-xs font-black uppercase tracking-widest ml-1">Gambar Cover</label>
                    <div class="relative w-full aspect-video border-2 border-dashed border-[#ff47a1] bg-pink-50 rounded-2xl flex flex-col items-center justify-center text-center group hover:bg-white transition-colors overflow-hidden">
                        <input type="file" name="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" onchange="previewImage(event)">
                        <div class="space-y-3 relative z-10 pointer-events-none" id="upload-placeholder">
                            <div class="w-14 h-14 bg-white border-2 border-[#ff47a1] rounded-full flex items-center justify-center mx-auto text-[#ff47a1] shadow-[3px_3px_0px_0px_#ff47a1]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div><p class="text-sm font-black uppercase">Upload Gambar</p><p class="text-[10px] font-bold opacity-60">Format JPG/PNG, Max 2MB</p></div>
                        </div>
                        <div id="preview-container" class="hidden absolute inset-0 bg-white z-10">
                            <img id="img-preview" class="w-full h-full object-cover">
                            <div class="absolute bottom-4 right-4 bg-white text-[#ff47a1] px-3 py-1 text-[10px] font-black uppercase border-2 border-[#ff47a1] shadow-sm rounded-lg pointer-events-none">Ganti Gambar</div>
                        </div>
                    </div>
                </div>

                {{-- 6. KONTEN (TRIX) --}}
                <div class="space-y-2">
                    <label class="block text-xs font-black uppercase tracking-widest ml-1">Isi Konten</label>
                    <input id="body" type="hidden" name="content" value="{{ old('content') }}">
                    <trix-editor input="body" placeholder="Mulai menulis di sini, Dok..."></trix-editor>
                    @error('content') <p class="text-xs font-bold mt-1 bg-[#ff47a1] text-white px-2 py-1 inline-block rounded">{{ $message }}</p> @enderror
                </div>

                <div class="pt-6 border-t-2 border-[#ff47a1]">
                    <button type="submit" class="w-full md:w-auto bg-[#ff47a1] text-white font-black uppercase px-10 py-4 rounded-xl border-2 border-[#ff47a1] shadow-[4px_4px_0px_0px_#ffffff] hover:bg-white hover:text-[#ff47a1] hover:shadow-[4px_4px_0px_0px_#ff47a1] hover:-translate-y-1 transition-all">Simpan Artikel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                document.getElementById('img-preview').src = reader.result;
                document.getElementById('preview-container').classList.remove('hidden');
                document.getElementById('upload-placeholder').classList.add('hidden');
            }
            if(event.target.files[0]) reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
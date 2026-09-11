@extends('layouts.app')
@section('title', 'AI Scanner Gizi - Mamacare')
@section('content')
<div class="min-h-screen py-6 md:py-10 text-[#FF3EA5] font-sans" x-data="foodScanner()">
    <div class="max-w-lg mx-auto px-4">
        
        <div class="mb-6 flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-[#FF3EA5] text-[#FF3EA5] font-black uppercase rounded-xl shadow-[3px_3px_0px_0px_#ff90c8] active:translate-y-1 active:shadow-none transition-all">
                Kembali
            </a>
        </div>
        
        <div class="mb-8 text-center">
            <h2 class="text-3xl md:text-5xl font-black uppercase tracking-tighter mb-2">Scanner <span class="text-stroke-pink text-white">Gizi</span></h2>
            <p class="font-bold opacity-80 text-sm">Jepret makanan Bunda, Mama AI bantu cek gizinya!</p>
        </div>

        {{-- AREA UPLOAD (Satu Kartu Rapi) --}}
        <div class="bg-white border-2 border-[#FF3EA5] rounded-3xl p-4 md:p-6 shadow-[4px_4px_0px_0px_#ff90c8]">
            <div class="border-4 border-dashed border-pink-200 rounded-2xl flex flex-col items-center justify-center relative overflow-hidden transition-all hover:border-[#FF3EA5] hover:bg-pink-50 min-h-[250px]"
                 :class="imagePreview ? 'border-none bg-black/5 p-0' : 'p-6'">
                
                {{-- Input File Tersembunyi --}}
                <input type="file" x-ref="fileInput" @change="previewImage" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" :disabled="isLoading">
                
                {{-- State: Kosong --}}
                <div x-show="!imagePreview" class="text-center pointer-events-none flex flex-col items-center">
                    <span class="text-6xl mb-4">📸</span>
                    <p class="font-black uppercase text-sm text-[#FF3EA5]">Ketuk untuk Upload Foto</p>
                    <p class="font-bold text-xs opacity-60 mt-2">Format: JPG, PNG (Max 5MB)</p>
                </div>

                {{-- State: Ada Gambar --}}
                <img x-show="imagePreview" :src="imagePreview" class="w-full h-full object-cover absolute inset-0 pointer-events-none" style="display: none;">
            </div>
            
            {{-- Tombol Hapus & Scan --}}
            <div class="mt-4 flex gap-3" x-show="imagePreview" style="display: none;">
                <button @click="clearImage" class="flex-1 py-3 bg-white text-[#FF3EA5] border-2 border-[#FF3EA5] rounded-xl font-black uppercase shadow-[3px_3px_0px_0px_#ff90c8] active:translate-y-1 active:shadow-none transition-all" :disabled="isLoading">
                    Hapus
                </button>
                <button @click="scanFood" class="flex-[2] py-3 bg-[#FF3EA5] text-white border-2 border-[#FF3EA5] rounded-xl font-black uppercase shadow-[3px_3px_0px_0px_#ff90c8] active:translate-y-1 active:shadow-none transition-all flex justify-center items-center gap-2" :disabled="isLoading">
                    <span x-show="!isLoading">Scan Gizi! ✨</span>
                    <span x-show="isLoading">Menganalisis... ⏳</span>
                </button>
            </div>
        </div>

        {{-- AREA HASIL --}}
        <div class="mt-6 bg-white border-2 border-[#FF3EA5] rounded-3xl p-6 shadow-[4px_4px_0px_0px_#ff90c8] relative overflow-hidden" x-show="hasResult || isLoading" style="display: none;" x-transition>
            <h3 class="font-black text-lg uppercase mb-4 border-b-2 border-[#FF3EA5] pb-2 text-center" x-show="hasResult && !errorMsg">Hasil Analisis Mama AI</h3>
            
            {{-- State: Loading --}}
            <div x-show="isLoading" class="flex flex-col items-center justify-center text-center py-4">
                <div class="w-12 h-12 border-4 border-pink-100 border-t-[#FF3EA5] rounded-full animate-spin mb-4"></div>
                <p class="font-black uppercase tracking-wider text-sm animate-pulse">Mama AI sedang mengintip gizinya...</p>
            </div>

            {{-- State: Hasil Berhasil / Error --}}
            <div x-show="hasResult && !isLoading" class="w-full">
                {{-- Box Pesan Error --}}
                <div x-show="errorMsg" class="bg-red-50 border-2 border-red-400 text-red-600 p-4 rounded-xl font-bold text-center" x-text="errorMsg"></div>

                {{-- Konten HTML dari AI --}}
                <div x-show="!errorMsg" class="prose prose-pink prose-sm max-w-none font-bold leading-relaxed" x-html="aiResult"></div>
            </div>
            
            {{-- CSS tambahan untuk menata HTML dari AI --}}
            <style>
                .prose h3 { font-weight: 900; text-transform: uppercase; color: #FF3EA5; margin-bottom: 0.5rem; font-size: 1.25rem; }
                .prose h4 { font-weight: 900; color: #FF3EA5; margin-top: 1rem; margin-bottom: 0.25rem; text-transform: uppercase; font-size: 0.9rem; }
                .prose ul { list-style-type: none; padding-left: 0; }
                .prose li { position: relative; padding-left: 1.25rem; margin-bottom: 0.25rem; }
                .prose li::before { content: '✨'; position: absolute; left: 0; color: #FF3EA5; font-size: 0.7rem; top: 0.2rem; }
                .prose p { margin-bottom: 0.75rem; opacity: 0.9; }
            </style>
        </div>

    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('foodScanner', () => ({
        imagePreview: null,
        selectedFile: null,
        isLoading: false,
        hasResult: false,
        aiResult: '',
        errorMsg: '',

        previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validasi ukuran 5MB
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran foto terlalu besar, Bun! Maksimal 5MB ya.');
                this.clearImage();
                return;
            }

            this.selectedFile = file;
            this.imagePreview = URL.createObjectURL(file);
            this.hasResult = false;
            this.aiResult = '';
            this.errorMsg = '';
        },

        clearImage() {
            this.imagePreview = null;
            this.selectedFile = null;
            this.hasResult = false;
            this.aiResult = '';
            this.errorMsg = '';
            if(this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        },

        async scanFood() {
            if (!this.selectedFile) return;

            this.isLoading = true;
            this.hasResult = false;
            this.errorMsg = '';

            const formData = new FormData();
            formData.append('image', this.selectedFile);

            try {
                const response = await fetch('{{ route("mama.food-scanner.scan") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.aiResult = data.result;
                    this.hasResult = true;
                } else {
                    this.errorMsg = data.message || 'Terjadi kesalahan saat memproses gambar.';
                    this.hasResult = true;
                }
            } catch (error) {
                this.errorMsg = 'Koneksi terputus. Pastikan internet lancar ya Bun!';
                this.hasResult = true;
            } finally {
                this.isLoading = false;
            }
        }
    }));
});
</script>
@endsection

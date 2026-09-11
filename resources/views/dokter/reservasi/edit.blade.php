@extends('layouts.app')

@section('title', 'Periksa Pasien - Mamacare')

@section('content')
    <div class="min-h-screen py-8 bg-white text-[#FF3EA5] font-sans selection:bg-[#FF3EA5] selection:text-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            
            {{-- 1. HEADER --}}
            <div class="flex items-center justify-between mb-8 border-b-2 border-[#FF3EA5] pb-6 border-dashed">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dokter.reservasi.index') }}" 
                       class="w-10 h-10 flex items-center justify-center bg-white border-2 border-[#FF3EA5] text-[#FF3EA5] rounded-lg shadow-[3px_3px_0px_0px_#FF3EA5] hover:shadow-none hover:translate-x-[1px] hover:translate-y-[1px] transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-black uppercase tracking-tight">Pemeriksaan Medis</h2>
                        <p class="text-xs font-bold opacity-60">Manajemen Pasien</p>
                    </div>
                </div>
            </div>

            {{-- 2. GRID LAYOUT --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                
                {{-- KOLOM KIRI (Info Pasien) --}}
                <div class="md:col-span-4 space-y-6">
                    <div class="bg-white border-2 border-[#FF3EA5] rounded-2xl p-6 shadow-[5px_5px_0px_0px_#FF3EA5] sticky top-6">
                        <div class="flex flex-col items-center text-center mb-6">
                            <x-avatar :user="$appointment->user" size="lg" class="mb-3" />
                            <h3 class="text-lg font-black uppercase leading-tight">{{ $appointment->user->name }}</h3>
                            <p class="text-xs font-bold opacity-60 mt-1">{{ $appointment->user->email }}</p>
                        </div>
                        <div class="space-y-4 pt-4 border-t-2 border-dashed border-[#FF3EA5]">
                            <div>
                                <p class="text-[10px] font-black uppercase bg-[#FF3EA5] text-white inline-block px-2 py-0.5 rounded mb-1">Jadwal</p>
                                <p class="text-sm font-bold">{{ \Carbon\Carbon::parse($appointment->date)->isoFormat('dddd, D MMMM Y') }}</p>
                                <p class="text-sm font-bold opacity-60">Jam {{ $appointment->time }} WIB</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase bg-[#FF3EA5] text-white inline-block px-2 py-0.5 rounded mb-1">Keluhan</p>
                                <div class="text-sm font-medium italic opacity-80">"{{ $appointment->notes ?? '-' }}"</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN (Form Update) --}}
                <div class="md:col-span-8">
                    
                    @php
                        $statusDB = $appointment->status;
                        $isPending = $statusDB == 'pending';
                        $isConfirmed = $statusDB == 'confirmed';
                        $isCompleted = $statusDB == 'completed';
                        $isCancelled = $statusDB == 'cancelled';

                        $lockPending = $isConfirmed || $isCompleted || $isCancelled;
                        $lockConfirmed = $isCompleted || $isCancelled;
                    @endphp

                    <form action="{{ route('dokter.reservasi.update', $appointment->id) }}" method="POST" enctype="multipart/form-data"
                          x-data="{ status: '{{ old('status', $appointment->status) }}' }"> 
                        @csrf
                        @method('PATCH')

                        <div class="bg-white border-2 border-[#FF3EA5] rounded-2xl p-6 md:p-8 shadow-[5px_5px_0px_0px_#FF3EA5] relative overflow-hidden">
                            
                            {{-- ALERT JIKA CANCELLED --}}
                            @if($isCancelled)
                                <div class="mb-8 bg-pink-50 border-2 border-[#FF3EA5] p-4 rounded-xl flex items-start gap-4">
                                    <div class="w-10 h-10 bg-[#FF3EA5] rounded-full flex items-center justify-center text-white font-bold shrink-0">X</div>
                                    <div>
                                        <h4 class="font-black uppercase text-[#FF3EA5] text-lg">Janji Temu Dibatalkan</h4>
                                        <p class="text-xs font-bold text-[#FF3EA5] opacity-70">Data ini dikunci dan tidak dapat diubah kembali.</p>
                                    </div>
                                </div>
                            @else
                                <h3 class="text-xl font-black uppercase mb-6 flex items-center gap-2">
                                    <span class="w-3 h-3 bg-[#FF3EA5] rounded-full inline-block"></span>
                                    Update Status
                                </h3>
                            @endif

                            {{-- WRAPPER FIELDSET --}}
                            <fieldset {{ $isCancelled ? 'disabled' : '' }} class="{{ $isCancelled ? 'opacity-50 grayscale' : '' }} transition-all">
                                
                                <div class="space-y-3 mb-8">
                                    {{-- 1. PENDING --}}
                                    <label class="group relative flex items-center gap-4 p-4 rounded-xl border-2 transition-all
                                        {{ $lockPending ? 'border-[#FF3EA5]/20 bg-pink-50/30 opacity-60 cursor-not-allowed' : 'border-[#FF3EA5] cursor-pointer hover:bg-pink-50' }}">
                                        
                                        <input type="radio" name="status" value="pending" x-model="status" class="peer sr-only" {{ $lockPending ? 'disabled' : '' }}>
                                        <div class="w-6 h-6 border-2 border-[#FF3EA5] rounded flex items-center justify-center peer-checked:bg-[#FF3EA5] peer-checked:text-white">
                                            @if($lockPending)
                                                <svg class="w-3 h-3 text-[#FF3EA5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            @else
                                                <span class="font-black text-xs" x-show="status == 'pending'">✓</span>
                                            @endif
                                        </div>
                                        <div><span class="block text-sm font-black uppercase text-[#FF3EA5]">1. Menunggu (Pending)</span></div>
                                    </label>

                                    {{-- 2. CONFIRMED --}}
                                    <label class="group relative flex items-center gap-4 p-4 rounded-xl border-2 transition-all
                                        {{ $lockConfirmed ? 'border-[#FF3EA5]/20 bg-pink-50/30 opacity-60 cursor-not-allowed' : 'border-[#FF3EA5] cursor-pointer hover:bg-pink-50' }}">
                                        
                                        <input type="radio" name="status" value="confirmed" x-model="status" class="peer sr-only" {{ $lockConfirmed ? 'disabled' : '' }}>
                                        <div class="w-6 h-6 border-2 border-[#FF3EA5] rounded flex items-center justify-center peer-checked:bg-[#FF3EA5] peer-checked:text-white">
                                            @if($lockConfirmed)
                                                <svg class="w-3 h-3 text-[#FF3EA5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            @else
                                                <span class="font-black text-xs" x-show="status == 'confirmed'">✓</span>
                                            @endif
                                        </div>
                                        <div><span class="block text-sm font-black uppercase text-[#FF3EA5]">2. Konfirmasi & Periksa</span></div>
                                    </label>

                                    {{-- 3. COMPLETED --}}
                                    <label class="group relative flex items-center gap-4 p-4 rounded-xl border-2 transition-all
                                        {{ $isPending ? 'border-[#FF3EA5]/30 bg-pink-50/20 cursor-not-allowed opacity-60' : 'border-[#FF3EA5] cursor-pointer hover:bg-pink-50' }}">
                                        
                                        <input type="radio" name="status" value="completed" x-model="status" class="peer sr-only" {{ $isPending ? 'disabled' : '' }}>
                                        <div class="w-6 h-6 border-2 border-[#FF3EA5] rounded flex items-center justify-center peer-checked:bg-[#FF3EA5] peer-checked:text-white">
                                            @if($isPending)
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            @else
                                                <span class="font-black text-xs" x-show="status == 'completed'">✓</span>
                                            @endif
                                        </div>
                                        <div class="w-full">
                                            <span class="block text-sm font-black uppercase text-[#FF3EA5]">3. Selesai & Input Hasil</span>
                                            @if($isPending)
                                                <p class="text-[10px] font-bold mt-1 text-[#FF3EA5] bg-pink-100 inline-block px-2 rounded">Konfirmasi Dulu!</p>
                                            @endif
                                        </div>
                                    </label>
                                </div>

                                {{-- FORM INPUT HASIL (Completed) --}}
                                <div x-show="status === 'completed'" class="pt-8 border-t-2 border-dashed border-[#FF3EA5] space-y-6">
                                    <div class="bg-[#FF3EA5] text-white px-4 py-2 rounded-lg inline-block text-sm font-black uppercase shadow-sm">Rekam Medis</div>
                                    
                                    {{-- DIAGNOSA: Perhatikan class focus:border-[#FF3EA5] focus:outline-none --}}
                                    <div>
                                        <label class="block text-xs font-black uppercase mb-2 ml-1 text-[#FF3EA5]">Diagnosa <span class="text-xl leading-none">*</span></label>
                                        <textarea 
                                            name="diagnosis" 
                                            rows="3" 
                                            class="w-full bg-white border-2 border-[#FF3EA5] focus:border-[#FF3EA5] focus:outline-none rounded-xl p-4 font-bold text-sm text-[#FF3EA5] focus:text-[#FF3EA5] caret-[#FF3EA5] focus:ring-0 focus:shadow-[4px_4px_0px_0px_#FF3EA5] transition-all placeholder-[#FF3EA5]/40" 
                                            placeholder="Tulis diagnosa...">{{ old('diagnosis', $appointment->diagnosis) }}</textarea>
                                        @error('diagnosis') <p class="mt-2 text-xs font-bold bg-pink-100 px-2 py-1 inline-block rounded text-[#FF3EA5]">! {{ $message }}</p> @enderror
                                    </div>

                                    {{-- RESEP --}}
                                    <div>
                                        <label class="block text-xs font-black uppercase mb-2 ml-1 text-[#FF3EA5]">Resep & Saran</label>
                                        <textarea 
                                            name="prescription" 
                                            rows="3" 
                                            class="w-full bg-white border-2 border-[#FF3EA5] focus:border-[#FF3EA5] focus:outline-none rounded-xl p-4 font-bold text-sm text-[#FF3EA5] focus:text-[#FF3EA5] caret-[#FF3EA5] focus:ring-0 focus:shadow-[4px_4px_0px_0px_#FF3EA5] transition-all placeholder-[#FF3EA5]/40" 
                                            placeholder="Tulis resep...">{{ old('prescription', $appointment->prescription) }}</textarea>
                                    </div>

                                    {{-- UPLOAD GAMBAR (3 SLOT - INSTANT PREVIEW) --}}
<div>
    <label class="block text-xs font-black uppercase mb-2 ml-1 text-[#FF3EA5]">
        Upload USG / Foto (Maks 3)
    </label>

    {{-- Container 3 Slot --}}
    <div class="grid grid-cols-3 gap-4">
        @for ($i = 0; $i < 3; $i++)
            @php
                // 1. Ambil URL Gambar Lama dari Database (Jika Ada)
                $currentImages = [];
                $imageUrl = null; // Default kosong

                if($appointment->image) {
                    $decoded = json_decode($appointment->image, true);
                    // Support format lama (string) atau baru (array)
                    $arr = is_array($decoded) ? $decoded : [$appointment->image];
                    
                    if(isset($arr[$i])) {
                        $imageUrl = asset('storage/' . $arr[$i]);
                    }
                }
            @endphp

            {{-- 
                ALPINE JS LOGIC:
                - photoPreview: Diisi URL database saat load awal.
                - updatePreview(): Fungsi untuk membaca file baru dan mengubah gambar.
            --}}
            <div class="relative group w-full aspect-square" 
                 x-data="{ 
                     photoPreview: '{{ $imageUrl }}', 
                     updatePreview(event) {
                         const file = event.target.files[0];
                         if (file) {
                             const reader = new FileReader();
                             reader.onload = (e) => { this.photoPreview = e.target.result; };
                             reader.readAsDataURL(file);
                         }
                     }
                 }">
                
                {{-- Input File: Transparan menutupi seluruh kotak --}}
                <input type="file" name="images[]" 
                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                       accept="image/*"
                       @change="updatePreview($event)">
                
                {{-- Tampilan Kotak --}}
                <div class="w-full h-full bg-white border-2 border-dashed border-[#FF3EA5] rounded-xl flex flex-col items-center justify-center text-center overflow-hidden relative group-hover:bg-pink-50 transition-colors">
                    
                    {{-- KONDISI 1: Jika ada Preview (Entah dari DB atau Upload Baru) --}}
                    <div x-show="photoPreview" class="w-full h-full absolute inset-0 z-10">
                        <img :src="photoPreview" class="w-full h-full object-cover">
                        
                        {{-- Label Ganti Foto (Muncul saat hover) --}}
                        <div class="absolute inset-0 bg-[#FF3EA5]/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-white font-black text-[10px] uppercase border-2 border-white px-2 py-1 rounded">Ganti</span>
                        </div>
                    </div>

                    {{-- KONDISI 2: Jika KOSONG (Belum ada preview) --}}
                    <div x-show="!photoPreview" class="flex flex-col items-center justify-center">
                        <div class="w-8 h-8 bg-[#FF3EA5]/10 rounded-full flex items-center justify-center mb-1 group-hover:bg-[#FF3EA5] group-hover:text-white transition-colors text-[#FF3EA5]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <span class="text-[8px] font-black uppercase text-[#FF3EA5]">Slot {{ $i+1 }}</span>
                    </div>

                </div>
            </div>
        @endfor
    </div>
    
    <p class="mt-2 text-[10px] font-bold text-[#FF3EA5] opacity-60">
        * Klik kotak untuk menambah/mengganti foto. Preview akan langsung muncul.
    </p>
    @error('images') <p class="mt-1 text-xs font-bold text-[#FF3EA5]">! {{ $message }}</p> @enderror
    @error('images.*') <p class="mt-1 text-xs font-bold text-[#FF3EA5]">! {{ $message }}</p> @enderror
</div>
                                </div>

                                {{-- Tombol Batal Janji --}}
                                @if(!$isCompleted && !$isCancelled)
                                    <div class="mt-6 pt-6 border-t-2 border-dashed border-[#FF3EA5]/30">
                                        <label class="inline-flex items-center gap-2 cursor-pointer opacity-60 hover:opacity-100 transition-opacity">
                                            <input type="radio" name="status" value="cancelled" x-model="status" class="accent-[#FF3EA5]">
                                            <span class="text-xs font-bold uppercase underline decoration-2 text-[#FF3EA5]">Batalkan Janji Temu</span>
                                        </label>
                                    </div>
                                @endif

                            </fieldset>

                            {{-- Footer Action --}}
                            <div class="mt-8 pt-6 flex justify-end">
                                @if($isCancelled)
                                    <a href="{{ route('dokter.reservasi.index') }}" 
                                       class="px-8 py-3 bg-white text-[#FF3EA5] font-black uppercase rounded-xl border-2 border-[#FF3EA5] shadow-[4px_4px_0px_0px_#FF3EA5] hover:shadow-none hover:translate-x-[1px] hover:translate-y-[1px] transition-all">
                                        Kembali
                                    </a>
                                @else
                                    <button type="submit" 
                                            class="px-8 py-3 bg-[#FF3EA5] text-white font-black uppercase rounded-xl border-2 border-[#FF3EA5] shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all">
                                        Simpan Data
                                    </button>
                                @endif
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.app')
@section('title', 'Hitung Tendangan - Mamacare')
@section('content')
<div class="min-h-screen py-6 md:py-10 text-[#FF3EA5] font-sans" x-data="kickCounter()">
    <div class="max-w-4xl mx-auto px-4">
        {{-- Navigasi --}}
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-[#FF3EA5] text-[#FF3EA5] font-black uppercase rounded-xl shadow-[3px_3px_0px_0px_#ff90c8] hover:shadow-[1px_1px_0px_0px_#ff90c8] hover:translate-x-[2px] hover:translate-y-[2px] transition-all text-xs">
                Kembali
            </a>
        </div>

        <div class="mb-10 border-b-2 border-dashed border-[#FF3EA5] pb-6">
            <h2 class="text-3xl md:text-5xl font-black uppercase tracking-tighter">Hitung <span class="text-stroke-pink text-white">Tendangan</span></h2>
            <p class="font-bold opacity-80 mt-2">Hitung tendangan si kecil. Idealnya 10 tendangan dalam 2 jam.</p>
        </div>

        <div class="bg-white border-2 border-[#FF3EA5] rounded-[2rem] p-8 text-center shadow-[4px_4px_0px_0px_#ff90c8]">
            <div class="text-7xl md:text-8xl font-black mb-4" x-text="count">0</div>
            <p class="font-bold mb-8 opacity-60 uppercase tracking-widest text-sm" x-text="statusText">Belum mulai</p>
            
            <button @click="kick()" class="w-40 h-40 md:w-48 md:h-48 rounded-full bg-[#FF3EA5] text-white border-4 border-white outline outline-4 outline-[#FF3EA5] shadow-[8px_8px_0px_0px_#ff90c8] active:shadow-none active:translate-y-2 active:translate-x-2 transition-all flex flex-col items-center justify-center mx-auto mb-8">
                <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-black uppercase text-xl">Tendang!</span>
            </button>

            <div class="flex justify-center gap-4">
                <button @click="start()" x-show="!isStarted" class="px-6 py-3 bg-white text-[#FF3EA5] border-2 border-[#FF3EA5] rounded-xl font-black shadow-[3px_3px_0px_0px_#ff90c8] hover:bg-pink-50">Mulai Sesi</button>
                <button @click="stop()" x-show="isStarted" class="px-6 py-3 bg-[#FF3EA5] text-white border-2 border-[#FF3EA5] rounded-xl font-black shadow-[3px_3px_0px_0px_#ff90c8] hover:opacity-90">Selesai & Simpan</button>
            </div>
        </div>

        {{-- Form Hidden --}}
        <form id="kickForm" action="{{ route('mama.kick-counter.store') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="start_time" x-model="startTime">
            <input type="hidden" name="end_time" x-model="endTime">
            <input type="hidden" name="duration_minutes" x-model="duration">
            <input type="hidden" name="kicks_count" x-model="count">
        </form>

        {{-- History --}}
        <div class="mt-12">
            <h3 class="font-black uppercase text-xl mb-4">Riwayat Terakhir</h3>
            <div class="grid gap-4">
                @forelse($history as $item)
                <div class="bg-pink-50 border-2 border-[#FF3EA5] p-4 rounded-xl flex flex-col sm:flex-row justify-between sm:items-center gap-4 shadow-[3px_3px_0px_0px_#ff90c8]">
                    <div>
                        <p class="font-black text-lg">{{ $item->kicks_count }} Tendangan</p>
                        <p class="text-xs font-bold opacity-60">{{ $item->date }} | {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}</p>
                    </div>
                    <div class="font-black bg-white border-2 border-[#FF3EA5] px-3 py-1 rounded-lg text-center sm:text-left self-start sm:self-auto">
                        {{ $item->duration_minutes }} Menit
                    </div>
                </div>
                @empty
                <p class="opacity-50 italic font-bold">Belum ada data riwayat tendangan.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('kickCounter', () => ({
        count: 0,
        isStarted: false,
        startTimeStr: null,
        startTime: null,
        endTime: null,
        duration: 0,
        
        get statusText() {
            if(!this.isStarted) return 'Ketuk "Mulai Sesi" untuk menghitung';
            return 'Sedang menghitung... (Ketuk tombol tengah!)';
        },

        start() {
            this.isStarted = true;
            this.count = 0;
            const now = new Date();
            this.startTimeStr = now;
            this.startTime = now.toTimeString().split(' ')[0];
        },

        kick() {
            if(this.isStarted) {
                this.count++;
            } else {
                alert('Silakan klik "Mulai Sesi" terlebih dahulu!');
            }
        },

        stop() {
            if(this.count === 0) {
                if(confirm('Anda belum mencatat tendangan apapun. Yakin ingin berhenti?')) {
                    this.isStarted = false;
                }
                return;
            }
            const now = new Date();
            this.endTime = now.toTimeString().split(' ')[0];
            const diffMs = now - this.startTimeStr;
            this.duration = Math.round(diffMs / 60000); // menit
            if(this.duration < 1) this.duration = 1;
            
            setTimeout(() => { document.getElementById('kickForm').submit(); }, 100);
        }
    }))
})
</script>
@endsection

@extends('layouts.app')
@section('title', 'Timer Kontraksi - Mamacare')
@section('content')
<div class="min-h-screen py-6 md:py-10 text-[#FF3EA5] font-sans" x-data="contractionTimer()">
    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-[#FF3EA5] text-[#FF3EA5] font-black uppercase rounded-xl shadow-[3px_3px_0px_0px_#ff90c8]">
                Kembali
            </a>
        </div>
        <div class="mb-10 border-b-2 border-dashed border-[#FF3EA5] pb-6">
            <h2 class="text-3xl md:text-5xl font-black uppercase tracking-tighter">Timer <span class="text-stroke-pink text-white">Kontraksi</span></h2>
            <p class="font-bold opacity-80 mt-2">Ukur durasi dan interval kontraksi Bunda.</p>
        </div>

        <div class="bg-white border-2 border-[#FF3EA5] rounded-[2rem] p-8 text-center shadow-[4px_4px_0px_0px_#ff90c8]">
            <div class="text-6xl md:text-7xl font-black mb-2" x-text="formatTime(currentDuration)">00:00</div>
            <p class="font-bold mb-8 opacity-60 uppercase tracking-widest text-sm" x-text="isContracting ? 'Kontraksi Sedang Berlangsung' : 'Menunggu Kontraksi Datang...'"></p>
            
            <button @click="toggle()" :class="isContracting ? 'bg-white text-[#FF3EA5] outline-[#FF3EA5]' : 'bg-[#FF3EA5] text-white outline-white'" class="w-40 h-40 md:w-48 md:h-48 rounded-full border-4 border-white outline outline-4 shadow-[8px_8px_0px_0px_#ff90c8] active:shadow-none active:translate-y-2 active:translate-x-2 transition-all flex flex-col items-center justify-center mx-auto mb-8">
                <span class="font-black uppercase text-2xl" x-text="isContracting ? 'Berhenti' : 'Mulai!'"></span>
            </button>
        </div>

        <form id="contractionForm" action="{{ route('mama.contraction-timer.store') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="start_time" x-model="startTime">
            <input type="hidden" name="end_time" x-model="endTime">
            <input type="hidden" name="duration_seconds" x-model="lastDuration">
            <input type="hidden" name="interval_seconds" x-model="lastInterval">
            <input type="hidden" name="intensity" value="Sedang">
        </form>

        {{-- History --}}
        <div class="mt-12">
            <h3 class="font-black uppercase text-xl mb-4">Riwayat Kontraksi Terakhir</h3>
            <div class="grid gap-4">
                @forelse($history as $item)
                <div class="bg-white border-2 border-[#FF3EA5] p-4 rounded-xl flex flex-col sm:flex-row justify-between sm:items-center gap-2 shadow-[3px_3px_0px_0px_#ff90c8]">
                    <div>
                        <p class="font-black text-lg">Durasi: {{ $item->duration_seconds }} detik</p>
                        <p class="text-xs font-bold opacity-60">{{ $item->date }} | {{ \Carbon\Carbon::parse($item->start_time)->format('H:i:s') }} - {{ \Carbon\Carbon::parse($item->end_time)->format('H:i:s') }}</p>
                    </div>
                    <div class="text-left sm:text-right mt-2 sm:mt-0">
                        <p class="font-black text-sm {{ $item->interval_seconds < 300 ? 'text-red-500' : 'text-[#FF3EA5]' }}">
                            Jarak: {{ $item->interval_seconds ? floor($item->interval_seconds / 60) . 'm ' . ($item->interval_seconds % 60) . 's' : '-' }}
                        </p>
                    </div>
                </div>
                @empty
                <p class="opacity-50 italic font-bold">Belum ada data kontraksi dicatat.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('contractionTimer', () => ({
        isContracting: false,
        currentDuration: 0,
        timerInterval: null,
        startTimeStr: null,
        startTime: null,
        endTime: null,
        lastDuration: 0,
        lastInterval: 0,
        previousEndTimeStr: null,

        formatTime(seconds) {
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        },

        toggle() {
            if(!this.isContracting) {
                // Mulai Kontraksi
                this.isContracting = true;
                this.currentDuration = 0;
                const now = new Date();
                this.startTimeStr = now;
                this.startTime = now.toTimeString().split(' ')[0];
                
                // Hitung interval jika ada riwayat sesi sebelumnya di halaman ini
                if(this.previousEndTimeStr) {
                    this.lastInterval = Math.round((now - this.previousEndTimeStr) / 1000);
                } else {
                    this.lastInterval = 0; // Kontraksi pertama tidak ada interval
                }

                this.timerInterval = setInterval(() => { 
                    const currentTime = new Date();
                    this.currentDuration = Math.round((currentTime - this.startTimeStr) / 1000);
                }, 1000);
            } else {
                // Stop Kontraksi & Simpan
                this.isContracting = false;
                clearInterval(this.timerInterval);
                const now = new Date();
                this.previousEndTimeStr = now;
                this.endTime = now.toTimeString().split(' ')[0];
                this.lastDuration = Math.round((now - this.startTimeStr) / 1000);
                
                setTimeout(() => { document.getElementById('contractionForm').submit(); }, 200);
            }
        }
    }))
})
</script>
@endsection

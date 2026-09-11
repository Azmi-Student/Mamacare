@extends('layouts.app')

@section('title', 'Reservasi Dokter & Bidan')

@section('content')
    <div class="container mx-auto px-4 pt-6 pb-24" x-data="{
        view: '{{ session('success') ? 'appointments' : 'doctors' }}',
        isModalOpen: false,
        selectedDoctor: null,
        selectedDate: '',
        selectedTime: '',
        selectedService: '',
        isLoading: false,
        bookedSlots: {{ json_encode($bookedSlots) }},
        
        isTimeBooked(time) {
            if(!this.selectedDoctor || !this.selectedDate) return false;
            let key = this.selectedDoctor.id + '_' + this.selectedDate;
            if(this.bookedSlots[key]) {
                return this.bookedSlots[key].includes(time);
            }
            return false;
        },
    
        openBooking(doctor) {
            this.selectedDoctor = doctor;
            this.isModalOpen = true;
            this.selectedTime = '';
            this.selectedDate = '';
            this.selectedService = '';
            document.body.style.overflow = 'hidden';
        },
    
        closeBooking() {
            this.isModalOpen = false;
            document.body.style.overflow = 'auto';
        },
    
        get isFormComplete() {
            return this.selectedDate !== '' && this.selectedTime !== '' && this.selectedService !== '';
        }
    }" @keydown.escape.window="closeBooking()">

        {{-- 1. TOMBOL KEMBALI (Pojok Kiri Atas) --}}
        <div class="mb-6">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-[#FF3EA5] text-[#FF3EA5] font-black uppercase rounded-xl shadow-[3px_3px_0px_0px_#FF3EA5] hover:bg-pink-50 hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all text-[10px] md:text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        {{-- 2. HEADER STATIS (Satu Bagian Saja) --}}
        <div class="mb-8 text-center">
            <h1 class="text-3xl md:text-5xl font-black text-[#FF3EA5] uppercase tracking-tight mb-3 drop-shadow-sm">
                Reservasi & Antrian
            </h1>
            <div class="inline-block bg-white border-2 border-[#FF3EA5] p-3 rounded-2xl shadow-[4px_4px_0px_0px_#FF3EA5]">
                <p class="text-xs md:text-sm font-bold text-[#FF3EA5]">
                    Layanan kesehatan terbaik untuk memantau perkembangan Mama & Si Kecil.
                </p>
            </div>
        </div>

        {{-- 3. TOMBOL SWITCH (Posisi di Bawah Header) --}}
        <div class="mb-10 flex justify-center">
            <div class="flex bg-white border-2 border-[#FF3EA5] p-1 rounded-2xl shadow-[4px_4px_0px_0px_#FF3EA5]">
                <button @click="view = 'doctors'"
                    :class="view === 'doctors' ? 'bg-[#FF3EA5] text-white shadow-[2px_2px_0px_0px_#ff90c8]' :
                        'text-[#FF3EA5] hover:bg-pink-50'"
                    class="px-6 py-2.5 font-black uppercase text-[10px] md:text-xs rounded-xl transition-all duration-200">
                    Daftar Dokter
                </button>
                <button @click="view = 'appointments'"
                    :class="view === 'appointments' ? 'bg-[#FF3EA5] text-white shadow-[2px_2px_0px_0px_#ff90c8]' :
                        'text-[#FF3EA5] hover:bg-pink-50'"
                    class="px-6 py-2.5 font-black uppercase text-[10px] md:text-xs rounded-xl transition-all duration-200">
                    Antrian Saya
                </button>
            </div>
        </div>

        {{-- 4. FLASH MESSAGE (FLOATING TOAST NOTIFICATION) --}}
        @if (session('success'))
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-init="setTimeout(() => show = false, 4000)"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-10"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-10"
                 class="fixed top-10 left-1/2 -translate-x-1/2 z-[150] w-[90%] max-w-md">
                 
                <div class="bg-white border-4 border-[#FF3EA5] p-4 rounded-2xl shadow-[6px_6px_0px_0px_#ff90c8] flex items-center gap-4">
                    <div class="bg-[#FF3EA5] text-white w-10 h-10 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[12px] font-black text-[#FF3EA5] uppercase tracking-widest leading-none">Berhasil!</p>
                        <p class="text-[11px] font-bold text-pink-400 mt-1 leading-tight">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- FLASH MESSAGE ERROR (FLOATING TOAST NOTIFICATION) --}}
        @if (session('error'))
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-10"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-10"
                 class="fixed top-10 left-1/2 -translate-x-1/2 z-[150] w-[90%] max-w-md">
                 
                <div class="bg-white border-4 border-red-500 p-4 rounded-2xl shadow-[6px_6px_0px_0px_#fca5a5] flex items-center gap-4">
                    <div class="bg-red-500 text-white w-10 h-10 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[12px] font-black text-red-500 uppercase tracking-widest leading-none">Gagal!</p>
                        <p class="text-[11px] font-bold text-red-400 mt-1 leading-tight">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- 5. GRID DOKTER (Muncul saat view == doctors) --}}
        <div x-show="view === 'doctors'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @forelse ($doctors as $doctor)
                    <div
                        class="group bg-white rounded-3xl border-2 border-[#FF3EA5] shadow-[6px_6px_0px_0px_#FF3EA5] overflow-hidden hover:-translate-y-1 hover:shadow-[8px_8px_0px_0px_#FF3EA5] transition-all duration-300 flex flex-col h-full text-[#FF3EA5]">

                        <div class="bg-white p-5 flex items-center gap-4 border-b-2 border-[#FF3EA5] border-dashed">
                            <x-avatar :user="$doctor" size="lg" class="shadow-sm" />
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-black uppercase leading-tight truncate mb-1">{{ $doctor->name }}
                                </h3>
                                <span
                                    class="inline-block bg-[#FF3EA5] text-white text-[10px] font-bold px-2 py-0.5 rounded-lg border-2 border-[#FF3EA5]">
                                    {{ $doctor->specialist }}
                                </span>
                            </div>
                        </div>

                        <div class="p-5 flex-1 flex flex-col">
                            <div
                                class="flex items-center gap-2 mb-4 text-xs font-bold bg-white border-2 border-[#FF3EA5] p-3 rounded-xl w-max">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Pengalaman {{ $doctor->experience }} Tahun
                            </div>

                            <div class="mb-6 flex-1">
                                <p class="text-sm font-bold leading-relaxed italic opacity-80">
                                    "{{ $doctor->description }}"
                                </p>
                            </div>

                            <button @click="openBooking({ id: {{ $doctor->id }}, name: '{{ addslashes($doctor->name) }}' })"
                                class="mt-auto w-full py-3 bg-[#FF3EA5] text-white text-sm font-black uppercase tracking-wider rounded-xl border-2 border-[#FF3EA5] shadow-[4px_4px_0px_0px_#ff90c8] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] active:scale-95 transition-all">
                                Buat Janji Temu
                            </button>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full text-center py-12 bg-white rounded-3xl border-2 border-[#FF3EA5] border-dashed">
                        <p class="text-[#FF3EA5] font-bold uppercase opacity-60">Belum ada data dokter tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- 6. GRID ANTRIAN SAYA (Muncul saat view == appointments) --}}
        <div x-show="view === 'appointments'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10 text-[#FF3EA5]">
                @forelse ($userAppointments as $app)
                    <div
                        class="bg-white border-2 border-[#FF3EA5] rounded-[2rem] overflow-hidden shadow-[6px_6px_0px_0px_#FF3EA5] flex flex-col sm:flex-row items-stretch transition-transform hover:scale-[1.01]">

                        <div
                            class="bg-white border-b-2 sm:border-b-0 sm:border-r-2 border-[#FF3EA5] p-6 flex flex-col items-center justify-center min-w-[140px] shrink-0 text-center">
                            <p class="text-[10px] font-black uppercase opacity-60">Waktu</p>
                            <p class="text-2xl font-black leading-none my-1">{{ $app->time }}</p>
                            <p
                                class="text-[10px] font-bold uppercase whitespace-nowrap border-2 border-[#FF3EA5] px-2 py-0.5 rounded-lg">
                                {{ \Carbon\Carbon::parse($app->date)->isoFormat('D MMM YYYY') }}
                            </p>
                        </div>

                        <div class="p-6 flex-1 flex flex-col justify-between gap-4">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest mb-1 opacity-80">Dokter Pemeriksa
                                </p>
                                <h4 class="text-xl font-black uppercase leading-tight">{{ $app->doctor->name }}</h4>
                                <p class="text-xs font-bold opacity-60 mt-1 uppercase italic tracking-tight">
                                    {{ $app->notes }}</p>
                            </div>

                            <div class="flex items-center justify-between mt-2">
                                @php
                                    $statusStyle = match ($app->status) {
                                        'confirmed' => 'bg-[#FF3EA5] text-white border-[#FF3EA5]',
                                        'cancelled' => 'bg-white text-[#FF3EA5] border-[#FF3EA5] opacity-50',
                                        default => 'bg-white text-[#FF3EA5] border-[#FF3EA5] border-dashed',
                                    };
                                    $statusLabel = match ($app->status) {
                                        'confirmed' => 'SIAP DATANG',
                                        'cancelled' => 'DIBATALKAN',
                                        default => 'MENUNGGU',
                                    };
                                @endphp
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black uppercase mb-1 opacity-50">Status Antrian</span>
                                    <span
                                        class="{{ $statusStyle }} px-4 py-1.5 border-2 rounded-full font-black text-[10px] uppercase shadow-[3px_3px_0px_0px_#ff90c8]">
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                <div
                                    class="w-10 h-10 rounded-2xl bg-white flex items-center justify-center text-[#FF3EA5] border-2 border-[#FF3EA5]">
                                    @if ($app->status == 'confirmed')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full bg-white border-2 border-dashed border-[#FF3EA5] rounded-[2rem] p-12 text-center shadow-[4px_4px_0px_0px_#FF3EA5]">
                        <p class="text-sm font-bold opacity-50 uppercase tracking-widest leading-loose text-[#FF3EA5]">
                            Mama belum memiliki antrian aktif.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- 7. MODAL FORM POPUP --}}
        <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="absolute inset-0 bg-[#FF3EA5]/80 backdrop-blur-sm" @click="closeBooking()"></div>

            <div class="bg-white w-full max-w-lg rounded-3xl border-4 border-[#FF3EA5] shadow-2xl relative z-10 flex flex-col max-h-[85vh] text-[#FF3EA5]"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-10 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                <div
                    class="bg-white p-6 border-b-2 border-[#FF3EA5] flex justify-between items-start flex-none rounded-t-3xl">
                    <div>
                        <h2 class="text-xl md:text-2xl font-black uppercase leading-none mb-1">Form Reservasi</h2>
                        <p class="text-[10px] md:text-xs font-bold uppercase opacity-60">Lengkapi data kunjungan</p>
                    </div>
                    <button @click="closeBooking()"
                        class="text-[#FF3EA5] hover:rotate-90 transition-all bg-white p-2 rounded-xl border-2 border-[#FF3EA5] hover:shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto flex-1">
                    <form action="{{ route('mama.reservasi.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="doctor_id" :value="selectedDoctor?.id">

                        <div
                            class="flex items-center gap-3 mb-6 p-3 bg-white border-2 border-[#FF3EA5] rounded-2xl border-dashed">
                            <div
                                class="w-10 h-10 flex-none rounded-full bg-white border-2 border-[#FF3EA5] flex items-center justify-center text-[#FF3EA5]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-bold uppercase opacity-60">Dokter Tujuan</p>
                                <p class="text-sm font-black truncate uppercase" x-text="selectedDoctor?.name"></p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-black uppercase mb-2 ml-1">Tanggal Kunjungan</label>

                            {{-- UPDATE BAGIAN INI --}}
                            <input type="date" name="date" required min="{{ date('Y-m-d') }}"
                                x-model="selectedDate" placeholder="PILIH TANGGAL..."
                                class="appearance-none relative w-full bg-white border-2 border-[#FF3EA5] rounded-xl p-3 font-bold text-sm focus:outline-none focus:ring-2 focus:ring-pink-200 transition-all text-[#FF3EA5]">
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-black uppercase mb-2 ml-1">Layanan</label>
                            <div class="relative">
                                <select name="notes" x-model="selectedService" required
                                    class="w-full bg-white border-2 border-[#FF3EA5] rounded-xl p-3 font-bold text-sm focus:outline-none focus:ring-2 focus:ring-pink-200 transition-all appearance-none cursor-pointer text-[#FF3EA5]">
                                    <option value="" disabled selected class="opacity-50">Pilih Layanan...</option>
                                    <option value="Kontrol Kandungan Rutin">Kontrol Kandungan Rutin</option>
                                    <option value="USG 2D/4D">USG 2D / 4D</option>
                                    <option value="Cek Detak Jantung Janin">Cek Detak Jantung Janin</option>
                                    <option value="Konsultasi Keluhan">Konsultasi Keluhan</option>
                                    <option value="Program Hamil">Program Hamil</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-[#FF3EA5]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-xs font-black uppercase mb-2 ml-1">Pilih Waktu</label>
                            <div class="grid grid-cols-4 gap-2">
                                @foreach (['09:00', '10:00', '11:00', '13:00', '14:00', '16:00', '19:00', '20:00'] as $time)
                                    <label class="group" :class="isTimeBooked('{{ $time }}') ? 'cursor-not-allowed opacity-60' : 'cursor-pointer'">
                                        <input type="radio" name="time" value="{{ $time }}"
                                            class="peer sr-only" x-model="selectedTime"
                                            :disabled="isTimeBooked('{{ $time }}')">
                                        <div
                                            class="text-center py-2.5 border-2 rounded-xl text-xs font-bold transition-all"
                                            :class="isTimeBooked('{{ $time }}') 
                                                ? 'bg-gray-100 border-gray-300 text-gray-500 line-through' 
                                                : 'bg-white border-[#FF3EA5] text-[#FF3EA5] peer-checked:bg-[#FF3EA5] peer-checked:text-white peer-checked:shadow-[2px_2px_0px_0px_#ff90c8] hover:translate-x-[1px] hover:translate-y-[1px]'">
                                            {{ $time }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <button type="button" {{-- LOGIKA: Cek kelengkapan form. Jika lengkap, loading nyala, lalu submit --}}
                            @click="if(isFormComplete) { isLoading = true; $el.closest('form').submit(); }"
                            {{-- Disabled jika form belum lengkap ATAU sedang loading --}} :disabled="!isFormComplete || isLoading" {{-- Styling Kondisional --}}
                            :class="(isFormComplete && !isLoading) ?
                            'bg-[#FF3EA5] text-white shadow-[4px_4px_0px_0px_#ff90c8] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] cursor-pointer' :
                            'bg-white text-[#FF3EA5] border-[#FF3EA5] opacity-50 cursor-not-allowed border-dashed'"
                            class="w-full py-4 font-black uppercase rounded-xl border-2 border-[#FF3EA5] transition-all duration-300 mb-2 flex justify-center items-center gap-2">

                            {{-- Teks Normal (Muncul saat tidak loading) --}}
                            <span x-show="!isLoading"
                                x-text="isFormComplete ? 'Konfirmasi Booking' : 'Lengkapi Data Dulu'">
                            </span>

                            {{-- Animasi Loading (Muncul saat diklik & form valid) --}}
                            <span x-show="isLoading" class="flex items-center gap-2" style="display: none;">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Menjadwalkan...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    /* CSS SUPAYA INPUT TANGGAL ADA TULISANNYA DI HP */
    input[type="date"] {
        position: relative;
    }

    input[type="date"]:invalid::-webkit-datetime-edit {
        color: transparent;
    }

    input[type="date"]:invalid::before {
        content: attr(placeholder);
        /* Munculin teks placeholder */
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #FF3EA5;
        opacity: 0.5;
        font-weight: 700;
        text-transform: uppercase;
        pointer-events: none;
        font-size: 0.875rem;
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 24 24" fill="none" stroke="%23FF3EA5" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>');
        cursor: pointer;
        opacity: 0.6;
    }
</style>

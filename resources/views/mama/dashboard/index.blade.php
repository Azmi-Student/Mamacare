@extends('layouts.app')

@section('title', 'Dashboard Mamacare')

@section('content')
    <div class="container mx-auto pt-2 pb-8" x-data="{ showComingSoon: false, showLainnyaModal: false }">

        {{-- CSS Efek Gelas (Tetap dipakai biar manis) --}}
        <style>
            @keyframes smooth-shine {
                0% {
                    left: -100%;
                    opacity: 0;
                }

                10% {
                    opacity: 1;
                }

                50% {
                    left: 200%;
                    opacity: 1;
                }

                100% {
                    left: 200%;
                    opacity: 0;
                }
            }

            .efek-gelas-premium {
                position: absolute;
                top: 0;
                left: 0;
                width: 50%;
                height: 100%;
                background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.2) 30%, rgba(255, 255, 255, 0.6) 50%, rgba(255, 255, 255, 0.2) 70%, rgba(255, 255, 255, 0) 100%);
                transform: skewX(-25deg);
                animation: smooth-shine 6s infinite ease-in-out;
                pointer-events: none;
                z-index: 30;
            }
        </style>

        {{-- Hero Section: Neo Brutalism Pink --}}
        <section
            class="bg-[#FF3EA5] rounded-xl border-2 border-[#FF3EA5] shadow-[6px_6px_0px_0px_#ff90c8] overflow-hidden flex md:flex-row items-center relative mb-6 isolate">

            {{-- 1. Efek Gelas --}}
            <div class="efek-gelas-premium"></div>

            {{-- 2. Pattern Background --}}
            <div class="absolute inset-0 pointer-events-none opacity-10"
                style="background-image: url('{{ asset('images/puzzle-pattern.png') }}'); background-repeat: repeat; background-size: 100px;">
            </div>

            {{-- KONTEN KIRI --}}
            <div class="w-full md:w-3/5 p-6 md:p-8 z-10 relative">

                {{-- Sapaan --}}
                <h1 class="text-2xl md:text-4xl font-black text-white mb-2 tracking-tight drop-shadow-md leading-none">
                    Selamat Datang, <br>
                    <span
                        class="bg-white text-[#FF3EA5] px-2 rounded-md shadow-[3px_3px_0px_0px_rgba(0,0,0,0.1)] inline-block mt-1 capitalize font-black">
                        {{ Auth::user()->name ?? 'Bunda' }}!
                    </span>
                </h1>

                <p class="text-pink-100 text-sm md:text-base mb-8 font-bold max-w-md border-l-4 border-white pl-3 mt-3">
                    Menemani setiap langkah indah menanti kehadiran sang buah hati.
                </p>

                {{-- Menu Grid: Gaya Tombol Neo Brutal --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 relative">

                    {{-- Tombol 1: KALENDER KEHAMILAN --}}
                    <a href="{{ route('mama.kalender') }}"
                        class="bg-white p-2 rounded-lg border-2 border-white shadow-[3px_3px_0px_0px_#ff90c8] flex flex-col items-center justify-center gap-2 group transition-all hover:-translate-y-1 hover:shadow-[5px_5px_0px_0px_#ff90c8] active:translate-y-0 active:shadow-none h-full">
                        <img src="{{ asset('images/fitur-kalender.png') }}"
                            class="w-8 h-8 object-contain transition group-hover:scale-110">
                        <span
                            class="font-black text-[9px] md:text-[10px] text-[#FF3EA5] uppercase text-center leading-3 max-w-[80px]">
                            Kalender Kehamilan
                        </span>
                    </a>

                    {{-- Tombol 2: RESERVASI DOKTER --}}
                    <a href="{{ route('mama.reservasi') }}"
                        class="bg-white p-2 rounded-lg border-2 border-white shadow-[3px_3px_0px_0px_#ff90c8] flex flex-col items-center justify-center gap-2 group transition-all hover:-translate-y-1 hover:shadow-[5px_5px_0px_0px_#ff90c8] active:translate-y-0 active:shadow-none h-full">
                        <img src="{{ asset('images/fitur-reservasi.png') }}"
                            class="w-8 h-8 object-contain transition group-hover:scale-110">
                        <span
                            class="font-black text-[9px] md:text-[10px] text-[#FF3EA5] uppercase text-center leading-3 max-w-[80px]">
                            Reservasi Dokter
                        </span>
                    </a>

                    {{-- Tombol 3: CHAT MAMA.AI --}}
                    <a href="{{ route('mama.ai') }}"
                        class="bg-white p-2 rounded-lg border-2 border-white shadow-[3px_3px_0px_0px_#ff90c8] flex flex-col items-center justify-center gap-2 group transition-all hover:-translate-y-1 hover:shadow-[5px_5px_0px_0px_#ff90c8] active:translate-y-0 active:shadow-none h-full">
                        <img src="{{ asset('images/fitur-chat.png') }}"
                            class="w-8 h-8 object-contain transition group-hover:scale-110">
                        <span
                            class="font-black text-[9px] md:text-[10px] text-[#FF3EA5] uppercase text-center leading-3 max-w-[80px]">
                            Chat Mama.ai
                        </span>
                    </a>

                    {{-- ==================================================== --}}
                    {{-- Tombol 4: LAINNYA (YANG DIMODIFIKASI) --}}
                    {{-- ==================================================== --}}
                    {{-- Kita tambahkan @click.prevent untuk memicu modal --}}
                    <a href="#" @click.prevent="showLainnyaModal = true"
                        class="bg-white p-2 rounded-lg border-2 border-white shadow-[3px_3px_0px_0px_#ff90c8] flex flex-col items-center justify-center gap-2 group transition-all hover:-translate-y-1 hover:shadow-[5px_5px_0px_0px_#ff90c8] active:translate-y-0 active:shadow-none h-full relative">

                        {{-- IKON SVG BARU (Menggantikan <img>) --}}
                        {{-- Ini adalah ikon kotak menu (squares-2x2) --}}
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-8 h-8 text-[#FF3EA5] transition group-hover:scale-110">
                            <path fill-rule="evenodd"
                                d="M3 6a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3V6ZM3 15.75a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2.25Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3v-2.25Z"
                                clip-rule="evenodd" />
                        </svg>

                        <span class="font-black text-[9px] md:text-[10px] text-[#FF3EA5] uppercase text-center leading-3">
                            Lainnya
                        </span>
                    </a>

                    {{-- ==================================================== --}}
                    {{-- NOTIFIKASI TOAST "COMING SOON" --}}
                    {{-- ==================================================== --}}
                    {{-- Muncul di tengah area tombol ketika showComingSoon bernilai true --}}
                    <div x-show="showComingSoon" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-90"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-2 scale-90"
                        class="absolute inset-0 z-50 flex items-center justify-center pointer-events-none px-4"
                        style="display: none;"> {{-- style display none agar tidak berkedip saat loading --}}

                        <div
                            class="bg-[#FF3EA5] border-2 border-white shadow-[4px_4px_0px_0px_rgba(255,255,255,0.5)] px-4 py-3 rounded-xl flex items-center gap-3 pointer-events-auto">
                             {{-- Ikon Jam Pasir Kecil --}}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-white animate-pulse">
                                <path d="M12 7.5a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" />
                                <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 14.625v-9.75ZM8.25 9.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM18.75 9a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V9.75a.75.75 0 0 0-.75-.75h-.008ZM4.5 9.75A.75.75 0 0 1 5.25 9h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H5.25a.75.75 0 0 1-.75-.75V9.75Z" clip-rule="evenodd" />
                                <path d="M2.25 18a.75.75 0 0 0 0 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 0 0-.75-.75H2.25Z" />
                              </svg>

                            <span class="text-white font-black uppercase text-xs md:text-sm tracking-wider">
                                Fitur Segera Hadir!
                            </span>
                        </div>
                    </div>
                    </div>

                </div>

            {{-- KONTEN KANAN (Gambar) --}}
            <div class="hidden md:flex md:w-2/5 h-auto relative items-center justify-center z-10 pr-6">
                <div
                    class="absolute w-40 h-40 bg-white/20 rounded-full border-2 border-white/30 border-dashed animate-spin-slow">
                </div>
                <img src="{{ asset('images/ibu-hamil.png') }}" alt="Ilustrasi"
                    class="relative z-10 w-3/4 max-w-[220px] object-contain transition duration-500 hover:scale-105"
                    style="filter: drop-shadow(4px 4px 0px rgba(255,255,255,0.4));">
            </div>
        </section>


        <section class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            <div
                class="lg:col-span-2 bg-white rounded-xl p-4 border-2 border-[#FF3EA5] shadow-[4px_4px_0px_0px_#FF3EA5] h-full flex flex-col justify-between">

                {{-- Logika Ambil Data AI --}}
                @php
                    // Ambil data dari relasi user ke kehamilan
                    $dataKehamilan = auth()->user()->kehamilan;
                    $aiData = $dataKehamilan ? json_decode($dataKehamilan->ai_data, true) : null;

                    $harian = $aiData['harian'] ?? [
                        'Istirahat cukup (Tidur 8 jam)',
                        'Minum air putih (2-3 Liter)',
                        'Makan buah & sayur',
                    ];
                    $status = $aiData['checklist_status'] ?? [];

                    // Item 1 sebagai Highlight, sisanya sebagai agenda lainnya
                    $highlight = $harian[0] ?? 'Istirahat Cukup';
                    $agendas = array_slice($harian, 1, 2); // Ambil 2 agenda berikutnya
                @endphp

                {{-- Layout Grid: Kiri (Highlight), Kanan (List) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 flex-1">

                    {{-- 1. KARTU HIGHLIGHT (Tugas Pertama / Index 0) --}}
                    @php $isDoneH = isset($status[0]) && $status[0] == true; @endphp
                    <div x-data="{
                        checked: {{ $isDoneH ? 'true' : 'false' }},
                        toggle() {
                            this.checked = !this.checked;
                            fetch('{{ route('mama.kalender.update_checklist') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content')
                                },
                                body: JSON.stringify({ index: 0, checked: this.checked })
                            });
                        }
                    }" @click="toggle()"
                        class="cursor-pointer rounded-xl p-5 flex flex-col justify-between border-2 border-[#FF3EA5] shadow-[4px_4px_0px_0px_#ff90c8] relative overflow-hidden group hover:-translate-y-1 transition-all duration-200 min-h-[200px]"
                        :class="checked ? 'bg-pink-400 opacity-80' : 'bg-[#FF3EA5]'">

                        {{-- Dekorasi Glossy --}}
                        <div class="absolute right-0 top-0 w-24 h-24 bg-white opacity-10 rounded-full blur-xl -mr-6 -mt-6">
                        </div>

                        <div class="relative z-10 flex flex-col h-full">
                            {{-- Badge Status --}}
                            <div class="mb-4">
                                <span
                                    class="inline-block px-3 py-1 bg-white border-2 border-white rounded-md text-[10px] font-black text-[#FF3EA5] shadow-[2px_2px_0px_0px_rgba(255,255,255,0.5)]">
                                    <span x-text="checked ? 'SELESAI' : 'SELANJUTNYA'"></span>
                                </span>
                            </div>

                            <div class="flex items-start gap-4 flex-1">
                                {{-- Icon Box --}}
                                <div
                                    class="shrink-0 w-12 h-12 bg-white rounded-lg border-2 border-white flex items-center justify-center text-[#FF3EA5] shadow-[3px_3px_0px_0px_rgba(255,255,255,0.3)]">
                                    <template x-if="!checked">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </template>
                                    <template x-if="checked">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </template>
                                </div>

                                {{-- Text Area --}}
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-black text-white text-lg md:text-xl leading-tight mb-1 uppercase tracking-tight line-clamp-3 break-words"
                                        :class="checked ? 'line-through opacity-70' : ''">
                                        {{ $highlight }}
                                    </h4>
                                    <p class="text-[10px] text-pink-100 font-bold italic"
                                        x-text="checked ? 'Hebat, Mama luar biasa!' : 'Ketuk jika sudah dilakukan'"></p>
                                </div>
                            </div>

                            {{-- Footer Kartu --}}
                            <div class="mt-4 pt-3 border-t-2 border-white/30 flex items-center gap-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-[10px] font-black text-white tracking-wider uppercase">Fokus Utama
                                    Mama</span>
                            </div>
                        </div>
                    </div>

                    {{-- 2. KARTU LIST (Agenda Lainnya / Index 1 & 2) --}}
                    <div class="flex flex-col gap-3">
                        <p class="text-[10px] font-black text-[#FF3EA5] uppercase tracking-widest mb-0 ml-1">AGENDA LAINNYA
                        </p>

                        @foreach ($agendas as $index => $tugas)
                            @php
                                $actualIndex = $index + 1; // Karena item pertama adalah index 0
                                $isDone = isset($status[$actualIndex]) && $status[$actualIndex] == true;
                            @endphp
                            <div x-data="{
                                checked: {{ $isDone ? 'true' : 'false' }},
                                toggle() {
                                    this.checked = !this.checked;
                                    fetch('{{ route('mama.kalender.update_checklist') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content')
                                        },
                                        body: JSON.stringify({ index: {{ $actualIndex }}, checked: this.checked })
                                    });
                                }
                            }" @click="toggle()"
                                class="flex items-center gap-3 p-3 rounded-xl border-2 border-[#FF3EA5] transition-all cursor-pointer active:translate-y-0.5 active:shadow-none"
                                :class="checked ? 'bg-pink-50 opacity-60 border-dashed' :
                                    'bg-white hover:bg-pink-50 hover:shadow-[2px_2px_0px_0px_#FF3EA5]'">

                                <div class="w-10 h-10 shrink-0 rounded-lg border-2 flex items-center justify-center transition-all"
                                    :class="checked ? 'bg-[#FF3EA5] border-[#FF3EA5] text-white' :
                                        'bg-white border-[#FF3EA5] text-[#FF3EA5]'">
                                    <template x-if="!checked">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M7 17l9.2-9.2M17 17V7H7" />
                                        </svg>
                                    </template>
                                    <template x-if="checked">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7">
                                            </path>
                                        </svg>
                                    </template>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h4 class="font-black text-xs text-[#FF3EA5] uppercase truncate"
                                        :class="checked ? 'line-through' : ''">
                                        {{ $tugas }}
                                    </h4>
                                    <p class="text-[10px] font-bold text-pink-400 truncate"
                                        x-text="checked ? 'Selesai' : 'Belum selesai'"></p>
                                </div>
                            </div>
                        @endforeach

                        {{-- Link Lihat Semua --}}
                        <div class="mt-auto text-right">
                            <a href="{{ route('mama.kalender') }}"
                                class="inline-block px-3 py-1 rounded-md border-2 border-[#FF3EA5] text-[10px] font-black text-[#FF3EA5] hover:bg-[#FF3EA5] hover:text-white transition-colors uppercase tracking-wide">
                                LIHAT SEMUA &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BAGIAN KANAN: Kalender --}}
            <div class="lg:col-span-1 h-full">
                {{-- Memanggil file components/calendar-widget.blade.php dengan data reservasi --}}
                <x-calendar-widget :appointments="$appointments" />
            </div>

        </section>

        {{-- Modal Fitur Ekstra (Dipindah ke luar agar tidak terkena z-index clipping) --}}
        <div x-show="showLainnyaModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-white/80 backdrop-blur-sm px-4" style="display: none;" x-transition>
            <div class="bg-white p-6 md:p-8 rounded-3xl border-2 border-[#FF3EA5] shadow-[8px_8px_0px_0px_#ff90c8] w-full max-w-lg relative" @click.away="showLainnyaModal = false">
                <button @click="showLainnyaModal = false" class="absolute top-4 right-4 text-[#FF3EA5] font-black text-xl hover:rotate-90 transition-all">X</button>
                <h3 class="font-black text-2xl uppercase mb-6 text-[#FF3EA5] text-center">Menu Ekstra</h3>
                
                <div class="grid grid-cols-2 gap-3">
                    {{-- TOMBOL HIGHLIGHT: AI FOOD SCANNER --}}
                    <a href="{{ route('mama.food-scanner') }}" class="col-span-2 bg-pink-50 p-4 rounded-xl border-2 border-[#FF3EA5] shadow-[3px_3px_0px_0px_#ff90c8] flex flex-row items-center gap-4 group hover:-translate-y-1 active:translate-y-0 active:shadow-none transition-all">
                        <span class="text-4xl group-hover:scale-110 transition-transform">📸</span>
                        <div class="text-left">
                            <span class="font-black text-sm md:text-base text-[#FF3EA5] uppercase block flex items-center gap-2">
                                AI Scanner Gizi
                            </span>
                            <span class="font-bold text-xs text-[#FF3EA5] opacity-70">Jepret makanan, cek gizinya!</span>
                        </div>
                    </a>

                    <a href="{{ route('mama.kick-counter') }}" class="bg-white p-3 rounded-xl border-2 border-[#FF3EA5] shadow-[3px_3px_0px_0px_#ff90c8] hover:bg-pink-50 flex flex-col items-center justify-center gap-2 group hover:-translate-y-1 active:translate-y-0 active:shadow-none transition-all text-center">
                        <span class="text-3xl group-hover:scale-110 transition-transform">🦶</span>
                        <span class="font-black text-[10px] md:text-xs text-[#FF3EA5] uppercase leading-tight mt-1">Hitung Tendangan</span>
                    </a>
                    <a href="{{ route('mama.contraction-timer') }}" class="bg-white p-3 rounded-xl border-2 border-[#FF3EA5] shadow-[3px_3px_0px_0px_#ff90c8] hover:bg-pink-50 flex flex-col items-center justify-center gap-2 group hover:-translate-y-1 active:translate-y-0 active:shadow-none transition-all text-center">
                        <span class="text-3xl group-hover:scale-110 transition-transform">⏱️</span>
                        <span class="font-black text-[10px] md:text-xs text-[#FF3EA5] uppercase leading-tight mt-1">Timer Kontraksi</span>
                    </a>
                    <a href="{{ route('mama.weight-tracker') }}" class="bg-white p-3 rounded-xl border-2 border-[#FF3EA5] shadow-[3px_3px_0px_0px_#ff90c8] hover:bg-pink-50 flex flex-col items-center justify-center gap-2 group hover:-translate-y-1 active:translate-y-0 active:shadow-none transition-all text-center">
                        <span class="text-3xl group-hover:scale-110 transition-transform">📈</span>
                        <span class="font-black text-[10px] md:text-xs text-[#FF3EA5] uppercase leading-tight mt-1">Pantau Berat Badan</span>
                    </a>
                    <a href="{{ route('mama.baby-size') }}" class="bg-white p-3 rounded-xl border-2 border-[#FF3EA5] shadow-[3px_3px_0px_0px_#ff90c8] hover:bg-pink-50 flex flex-col items-center justify-center gap-2 group hover:-translate-y-1 active:translate-y-0 active:shadow-none transition-all text-center">
                        <span class="text-3xl group-hover:scale-110 transition-transform">🍉</span>
                        <span class="font-black text-[10px] md:text-xs text-[#FF3EA5] uppercase leading-tight mt-1">Ukuran Janin</span>
                    </a>
                    
                </div>
            </div>
        </div>

    </div>
@endsection

@extends('layouts.app')

@section('title', 'Daftar Pasien - Mamacare')

@section('content')
    {{-- Background Putih Bersih --}}
    <div class="min-h-screen py-8 bg-white text-[#FF3EA5] font-sans selection:bg-[#FF3EA5] selection:text-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            {{-- 1. HEADER SECTION (Clean Style) --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-6 border-b-2 border-dashed border-[#FF3EA5] pb-6">
                <div>
                    <div class="inline-block bg-pink-50 border border-[#FF3EA5] px-3 py-1 rounded-lg mb-2">
                        <span class="text-[10px] font-black uppercase tracking-widest text-[#FF3EA5]">
                            Area Dokter
                        </span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tight leading-none">
                        Daftar Pasien
                    </h2>
                    <p class="text-xs font-bold mt-1 opacity-60">
                        Kelola antrian dan status pemeriksaan hari ini.
                    </p>
                </div>

                {{-- Statistik Box (Simple) --}}
                <div class="flex items-center gap-4 bg-white border-2 border-[#FF3EA5] px-5 py-3 rounded-xl shadow-[4px_4px_0px_0px_#FF3EA5]">
                    <div class="bg-[#FF3EA5] text-white w-10 h-10 rounded-lg flex items-center justify-center font-black text-lg shadow-sm">
                        #
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest opacity-60">Total Pasien</p>
                        <p class="text-2xl font-black leading-none">{{ $appointments->total() }}</p>
                    </div>
                </div>
            </div>

            {{-- 2. TABLE CONTAINER (Clean Card) --}}
            <div class="border-2 border-[#FF3EA5] bg-white rounded-2xl overflow-hidden shadow-[5px_5px_0px_0px_#FF3EA5] mb-10">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        
                        {{-- Table Head --}}
                        <thead class="bg-pink-50 border-b-2 border-[#FF3EA5]">
                            <tr>
                                <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px] text-center w-16">No</th>
                                <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px]">Waktu</th>
                                <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px]">Nama Pasien</th>
                                <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px]">Keluhan</th>
                                <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px] text-center">Status</th>
                                <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px] text-center">Aksi</th>
                            </tr>
                        </thead>

                        {{-- Table Body --}}
                        <tbody class="divide-y-2 divide-[#FF3EA5]/10">
                            @forelse ($appointments as $appointment)
                                <tr class="hover:bg-pink-50/50 transition-colors group">
                                    
                                    {{-- Kolom No --}}
                                    <td class="px-6 py-4 text-center font-bold text-sm">
                                        {{ ($appointments->currentPage() - 1) * $appointments->perPage() + $loop->iteration }}
                                    </td>

                                    {{-- Kolom Waktu --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-xl font-black leading-none">{{ $appointment->time }}</span>
                                            <span class="text-[10px] font-bold opacity-60 uppercase mt-1">
                                                {{ \Carbon\Carbon::parse($appointment->date)->isoFormat('D MMM') }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Kolom Pasien --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <x-avatar :user="$appointment->user" size="sm" class="w-10 h-10" />
                                            <div>
                                                <div class="font-black text-sm uppercase leading-tight">{{ $appointment->user->name }}</div>
                                                <div class="text-[10px] font-bold opacity-50 uppercase">{{ $appointment->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kolom Catatan --}}
                                    <td class="px-6 py-4 min-w-[200px]">
                                        @if ($appointment->notes)
                                            <div class="text-xs font-medium italic opacity-80 border-l-2 border-[#FF3EA5] pl-3 py-1">
                                                "{{ Str::limit($appointment->notes, 30) }}"
                                            </div>
                                        @else
                                            <span class="text-[10px] font-black opacity-30 uppercase tracking-widest">- Kosong -</span>
                                        @endif
                                    </td>

                                    {{-- Kolom Status --}}
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $statusClass = match ($appointment->status) {
                                                'completed' => 'bg-[#FF3EA5] text-white border-[#FF3EA5]',
                                                'confirmed' => 'bg-white text-[#FF3EA5] border-[#FF3EA5]',
                                                'cancelled' => 'bg-gray-100 text-gray-400 border-gray-300',
                                                default     => 'bg-white text-[#FF3EA5] border-[#FF3EA5] border-dashed', // pending
                                            };
                                            
                                            $statusLabel = match ($appointment->status) {
                                                'pending' => 'Menunggu',
                                                default => $appointment->status
                                            };
                                        @endphp
                                        <span class="px-3 py-1.5 rounded-lg border-2 text-[10px] font-black uppercase tracking-wide inline-block min-w-[80px] {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                    {{-- Kolom Aksi --}}
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('dokter.reservasi.edit', $appointment->id) }}"
                                           class="inline-flex items-center gap-2 bg-[#FF3EA5] text-white border-2 border-[#FF3EA5] px-4 py-2 font-black uppercase text-[10px] rounded-lg shadow-[3px_3px_0px_0px_rgba(0,0,0,0.1)] hover:shadow-none hover:translate-x-[1px] hover:translate-y-[1px] transition-all">
                                            Periksa
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center opacity-50">
                                            <div class="w-16 h-16 border-2 border-dashed border-[#FF3EA5] rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <p class="text-lg font-black uppercase tracking-tight">Tidak Ada Pasien</p>
                                            <p class="text-xs font-bold uppercase tracking-widest mt-1">Jadwal hari ini kosong.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 3. PAGINATION (Strictly Pink & White) --}}
            <div class="mt-8 custom-pagination flex justify-center">
                {{ $appointments->links() }}
            </div>

        </div>
    </div>

    <style>
        /* Pagination Styling */
        .custom-pagination nav svg { width: 14px; height: 14px; display: inline; }
        .custom-pagination nav div div flex,
        .custom-pagination nav div div span, 
        .custom-pagination nav a {
            background-color: white !important;
            border: 2px solid #FF3EA5 !important;
            color: #FF3EA5 !important;
            border-radius: 8px !important;
            font-size: 10px !important;
            font-weight: 900 !important;
            padding: 6px 12px !important;
            margin: 0 2px !important;
            box-shadow: 2px 2px 0px 0px #FF3EA5 !important;
        }
        .custom-pagination nav a:hover {
            background-color: #FF3EA5 !important;
            color: white !important;
            box-shadow: none !important;
            transform: translate(1px, 1px) !important;
        }
        .custom-pagination nav span[aria-current="page"] span {
            background-color: #FF3EA5 !important;
            color: white !important;
            box-shadow: none !important;
            transform: translate(1px, 1px) !important;
        }
        .custom-pagination nav p { display: none; }
    </style>
@endsection
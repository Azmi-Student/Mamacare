@extends('layouts.app')
@section('title', 'Pantau Berat Badan - Mamacare')
@section('content')
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="min-h-screen py-6 md:py-10 text-[#FF3EA5] font-sans">
    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-[#FF3EA5] text-[#FF3EA5] font-black uppercase rounded-xl shadow-[3px_3px_0px_0px_#ff90c8]">
                Kembali
            </a>
        </div>
        <div class="mb-10 border-b-2 border-dashed border-[#FF3EA5] pb-6 flex flex-col md:flex-row justify-between items-start gap-4">
            <div>
                <h2 class="text-3xl md:text-5xl font-black uppercase tracking-tighter">Pantau <span class="text-stroke-pink text-white">Berat</span></h2>
                <p class="font-bold opacity-80 mt-2">Pantau kenaikan berat badan selama kehamilan.</p>
            </div>
            
            <div x-data="{ modalOpen: false }">
                <button @click="modalOpen = true" class="px-6 py-3 bg-[#FF3EA5] text-white font-black uppercase rounded-xl border-2 border-[#FF3EA5] shadow-[4px_4px_0px_0px_#ff90c8] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all">
                    + Catat Berat
                </button>

                <!-- Modal -->
                <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-white/80 backdrop-blur-sm px-4" style="display: none;">
                    <div class="bg-white border-2 border-[#FF3EA5] p-6 rounded-3xl shadow-[8px_8px_0px_0px_#ff90c8] w-full max-w-md relative" @click.away="modalOpen = false">
                        <button @click="modalOpen = false" class="absolute top-4 right-4 font-black text-xl hover:rotate-90 transition-all">X</button>
                        <h3 class="font-black text-2xl uppercase mb-6 text-[#FF3EA5]">Catat Berat Badan</h3>
                        
                        <form action="{{ route('mama.weight-tracker.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block font-black text-sm uppercase mb-2">Berat Badan (Kg)</label>
                                <input type="number" step="0.1" name="weight" required class="w-full bg-pink-50 border-2 border-[#FF3EA5] rounded-xl px-4 py-3 font-bold focus:outline-none focus:ring-4 focus:ring-pink-200">
                            </div>
                            <div class="mb-4">
                                <label class="block font-black text-sm uppercase mb-2">Usia Kehamilan (Minggu Ke-)</label>
                                <input type="number" name="week_number" required class="w-full bg-pink-50 border-2 border-[#FF3EA5] rounded-xl px-4 py-3 font-bold focus:outline-none focus:ring-4 focus:ring-pink-200">
                            </div>
                            <div class="mb-6">
                                <label class="block font-black text-sm uppercase mb-2">Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" rows="2" class="w-full bg-pink-50 border-2 border-[#FF3EA5] rounded-xl px-4 py-3 font-bold focus:outline-none focus:ring-4 focus:ring-pink-200"></textarea>
                            </div>
                            <button type="submit" class="w-full px-6 py-3 bg-[#FF3EA5] text-white font-black uppercase rounded-xl shadow-[4px_4px_0px_0px_#ff90c8] hover:translate-y-1 active:shadow-none transition-all">
                                Simpan Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grafik Chart.js --}}
        <div class="bg-white border-2 border-[#FF3EA5] rounded-[2rem] p-4 md:p-8 shadow-[4px_4px_0px_0px_#ff90c8] mb-10">
            @if(count($data) > 0)
                <canvas id="weightChart" class="w-full h-64 md:h-96"></canvas>
            @else
                <div class="text-center py-20 opacity-50">
                    <p class="font-black uppercase italic">Belum ada data berat badan.</p>
                </div>
            @endif
        </div>

        {{-- Riwayat Tabel --}}
        <div>
            <h3 class="font-black uppercase text-xl mb-4">Riwayat Log</h3>
            <div class="grid gap-4">
                @forelse($logs as $log)
                <div class="bg-pink-50 border-2 border-[#FF3EA5] p-4 rounded-xl flex flex-col sm:flex-row justify-between sm:items-center gap-2 shadow-[3px_3px_0px_0px_#ff90c8]">
                    <div>
                        <p class="font-black text-lg">{{ $log->weight }} Kg</p>
                        <p class="text-xs font-bold opacity-60">Minggu ke-{{ $log->week_number }} | {{ $log->date }}</p>
                        @if($log->notes)
                            <p class="text-xs italic mt-1 font-semibold text-[#FF3EA5] opacity-80">Catatan: {{ $log->notes }}</p>
                        @endif
                    </div>
                </div>
                @empty
                <p class="opacity-50 italic font-bold">Belum ada history dicatat.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@if(count($data) > 0)
<script>
    const ctx = document.getElementById('weightChart').getContext('2d');
    
    // Konfigurasi garis desain Neo-brutalism
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.font.weight = 'bold';
    Chart.defaults.color = '#FF3EA5';

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Berat Badan (Kg)',
                data: {!! json_encode($data) !!},
                borderColor: '#FF3EA5',
                backgroundColor: 'rgba(255, 62, 165, 0.2)',
                borderWidth: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#FF3EA5',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    grid: { color: 'rgba(255, 62, 165, 0.1)', borderDash: [5, 5] },
                    ticks: { font: { weight: '900' } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { weight: '900' } }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#FF3EA5',
                    titleFont: { size: 14, weight: '900' },
                    bodyFont: { size: 16, weight: 'bold' },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false
                }
            }
        }
    });
</script>
@endif
@endsection

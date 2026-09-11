@props(['appointments' => []])
<div x-data="appCalendar({{ json_encode($appointments) }})" x-init="initDate()" x-cloak class="h-full">
    {{-- Container Utama --}}
    <div
        class="bg-white rounded-xl p-3 border-2 border-[#FF3EA5] shadow-[4px_4px_0px_0px_#FF3EA5] h-full flex flex-col transition-transform hover:-translate-y-1">

        {{-- Header Mini: Navigasi Bulan --}}
        <div class="flex items-center justify-between mb-3">
            {{-- Tombol: Mobile/Tablet Besar (w-8), Desktop Kecil (w-6) --}}
            <button @click="changeMonth(-1)"
                class="w-8 h-8 lg:w-6 lg:h-6 flex items-center justify-center rounded-md border-2 border-[#FF3EA5] text-[#FF3EA5] hover:bg-[#FF3EA5] hover:text-white hover:shadow-[1px_1px_0px_0px_#FF3EA5] active:translate-y-0.5 active:shadow-none transition-all">
                <svg class="w-4 h-4 lg:w-3 lg:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            {{-- Tulisan Bulan: Tablet diperkecil sedikit biar gak makan tempat --}}
            <span class="font-black text-sm md:text-xs lg:text-xs text-[#FF3EA5] uppercase tracking-widest min-w-[100px] text-center"
                x-text="monthNames[month] + ' ' + year">
            </span>

            <button @click="changeMonth(1)"
                class="w-8 h-8 lg:w-6 lg:h-6 flex items-center justify-center rounded-md border-2 border-[#FF3EA5] text-[#FF3EA5] hover:bg-[#FF3EA5] hover:text-white hover:shadow-[1px_1px_0px_0px_#FF3EA5] active:translate-y-0.5 active:shadow-none transition-all">
                <svg class="w-4 h-4 lg:w-3 lg:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>

        {{-- Nama Hari --}}
        <div class="grid grid-cols-7 text-center mb-1 gap-1">
            <template x-for="day in days" :key="day">
                {{-- Font: Mobile (10px), Tablet/Desktop (8px) --}}
                <div class="text-[10px] md:text-[8px] lg:text-[8px] font-black text-white bg-[#FF3EA5] rounded py-1 lg:py-0.5 border-2 border-[#FF3EA5] shadow-[1.5px_1.5px_0px_0px_#ff90c8]"
                    x-text="day"></div>
            </template>
        </div>

        {{-- Grid Tanggal --}}
        {{-- Gap: Mobile (gap-2), Tablet (gap-1), Desktop (gap-0.5) --}}
        <div class="grid grid-cols-7 gap-2 md:gap-1 lg:gap-y-1 lg:gap-x-0.5 text-center flex-1 place-content-start mt-2">
            
            {{-- Kotak Kosong (Mengikuti ukuran Grid) --}}
            <template x-for="blank in blankdays">
                <div class="w-full aspect-square md:w-10 md:h-10 lg:w-6 lg:h-6"></div>
            </template>

            {{-- Kotak Angka --}}
            <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                
                {{-- LOGIKA BARU DI SINI: --}}
                {{-- 1. Mobile (Default): w-full aspect-square (Full Kotak) --}}
                {{-- 2. Tablet (md): w-10 h-10 (Fix 40px, ditengahin mx-auto) --}}
                {{-- 3. Desktop (lg): w-6 h-6 (Fix 24px) --}}
                <div class="relative flex items-center justify-center rounded cursor-pointer mx-auto font-black border-2 transition-all duration-100
                            w-full aspect-square 
                            md:w-10 md:h-10 
                            lg:w-6 lg:h-6 
                            text-sm md:text-xs lg:text-[10px]" 
                    :class="isToday(date) ?
                        'bg-[#FF3EA5] text-white border-[#FF3EA5] shadow-[2px_2px_0px_0px_#ff90c8] -translate-y-0.5' :
                        'text-[#FF3EA5] border-transparent hover:border-[#FF3EA5] hover:bg-pink-50'">
                    <span x-text="date"></span>
                    
                    {{-- DOT Indikator Reservasi --}}
                    <template x-if="hasAppointment(date)">
                        <div class="absolute bottom-1 w-1.5 h-1.5 rounded-full"
                             :class="isToday(date) ? 'bg-white' : 'bg-pink-400'">
                        </div>
                    </template>
                </div>
            </template>
        </div>

        {{-- Footer --}}
        <div class="mt-auto pt-2 border-t-2 border-[#FF3EA5]">
            <div class="flex items-center gap-2">
                <div
                    class="w-8 h-8 lg:w-6 lg:h-6 rounded bg-[#FF3EA5] border-2 border-[#FF3EA5] flex items-center justify-center text-white shadow-[1.5px_1.5px_0px_0px_#ff90c8]">
                    <span class="font-black text-xs lg:text-[9px]" x-text="new Date().getDate()"></span>
                </div>
                <div class="min-w-0">
                    <p class="text-[9px] lg:text-[8px] font-bold text-pink-300 uppercase leading-none mb-0.5">Hari ini</p>
                    <p class="text-[10px] lg:text-[9px] font-black text-[#FF3EA5] truncate leading-none">CEK JADWAL</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Script JavaScript (TETAP SAMA) --}}
    <script>
        function appCalendar(appointments = []) {
            return {
                appointments: appointments, // Simpan jadwal reservasi
                month: '',
                year: '',
                no_of_days: [],
                blankdays: [],
                days: ['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB'],
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                    'Oktober', 'November', 'Desember'
                ],

                initDate() {
                    let today = new Date();
                    this.month = today.getMonth();
                    this.year = today.getFullYear();
                    this.getNoOfDays();
                },

                isToday(date) {
                    const today = new Date();
                    const d = new Date(this.year, this.month, date);
                    return today.toDateString() === d.toDateString();
                },

                // Fungsi baru untuk ngecek apakah tanggal ini ada jadwal reservasi
                hasAppointment(date) {
                    // Format tanggal di Alpine JS sama dengan format Laravel: YYYY-MM-DD
                    const d = new Date(this.year, this.month, date);
                    // Sesuaikan ke timezone lokal tanpa tergeser UTC
                    const offset = d.getTimezoneOffset();
                    const localDate = new Date(d.getTime() - (offset*60*1000));
                    const dateStr = localDate.toISOString().split('T')[0];
                    return this.appointments.includes(dateStr);
                },

                changeMonth(val) {
                    this.month += val;
                    if (this.month > 11) {
                        this.month = 0;
                        this.year++;
                    } else if (this.month < 0) {
                        this.month = 11;
                        this.year--;
                    }
                    this.getNoOfDays();
                },

                getNoOfDays() {
                    let firstDayOfWeek = new Date(this.year, this.month, 1).getDay();
                    let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                    this.blankdays = new Array(firstDayOfWeek).fill(null);
                    this.no_of_days = Array.from({
                        length: daysInMonth
                    }, (_, i) => i + 1);
                }
            }
        }
    </script>
</div>
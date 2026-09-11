@extends('layouts.app')

{{-- Hide Header Mobile agar Chat Full Screen mentok atas --}}
@section('hideMobileHeader', true)

@section('title', 'Chat Pasien - Area Dokter')

@section('content')

    {{-- 
        CONTAINER UTAMA (FULL LAYOUT FIX - HEIGHT ADJUSTED)
        ---------------------------------------------------
        1. MOBILE: 'fixed inset-0 z-40' (Tetap Full Screen).
        2. TABLET/DESKTOP (md): 
           - 'md:h-[calc(100vh-9rem)]': Tinggi dikurangi lebih banyak (9rem) agar kotak lebih kecil 
             dan pas di tengah layar tanpa bikin body scrolling.
    --}}
    <div class="fixed inset-0 z-40 bg-white text-[#FF3EA5] font-sans flex flex-col md:static md:flex-row md:h-[calc(100vh-9rem)] md:border-2 md:border-[#FF3EA5] md:rounded-[2rem] md:shadow-[8px_8px_0px_0px_#FF3EA5] md:overflow-hidden"
         x-data="{ 
            view: 'list', 
            activePatient: null,
            patients: {{ json_encode($patients) }}, 
            messageInput: '',
            messages: [],
            chatInterval: null,
            
            // PILIH PASIEN
            selectChat(patient) {
                this.activePatient = patient;
                this.view = 'chat'; 
                this.messages = [];
                
                // Reset visual unread
                patient.unread = 0;

                this.fetchMessages();

                if (this.chatInterval) clearInterval(this.chatInterval);
                this.chatInterval = setInterval(() => {
                    this.fetchMessages();
                }, 3000);
            },

            // FETCH PESAN
            fetchMessages() {
                if(!this.activePatient) return;

                fetch(`/dokter/jawab-pasien/messages/${this.activePatient.id}`)
                    .then(res => res.json())
                    .then(data => {
                        if (JSON.stringify(data) !== JSON.stringify(this.messages)) {
                            this.messages = data;
                            this.$nextTick(() => this.scrollToBottom());
                        }
                    })
                    .catch(err => console.error(err));
            },

            // BACK TO LIST (MOBILE)
            backToList() {
                this.view = 'list';
                this.activePatient = null;
                if (this.chatInterval) clearInterval(this.chatInterval);
            },

            // KIRIM PESAN
            sendMessage() {
                if (this.messageInput.trim() === '') return;
                
                const textToSend = this.messageInput;
                const userId = this.activePatient.id;

                this.messages.push({ text: textToSend, sender: 'doctor', time: '...' });
                
                this.messageInput = '';
                this.$refs.input.style.height = 'auto'; 
                this.$nextTick(() => this.scrollToBottom());

                fetch('/dokter/jawab-pasien/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        message: textToSend
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.fetchMessages(); 
                })
                .catch(err => {
                    console.error('Gagal kirim:', err);
                    alert('Gagal mengirim. Cek koneksi.');
                });
            },

            scrollToBottom() {
                const container = document.getElementById('chat-scroll-area');
                if(container) container.scrollTop = container.scrollHeight;
            },

            resize() {
                $refs.input.style.height = 'auto'; 
                $refs.input.style.height = $refs.input.scrollHeight + 'px';
            },

            sendAndReset() {
                if (this.messageInput.trim() === '') return;
                this.sendMessage();
            }
         }">

        {{-- =============================================================== --}}
        {{-- KOLOM KIRI: LIST PASIEN (SIDEBAR) --}}
        {{-- =============================================================== --}}
        {{-- Mobile: pb-20 (biar list paling bawah ga ketutup navbar) --}}
        <div class="w-full md:w-80 lg:w-96 bg-white flex flex-col md:border-r-2 md:border-[#FF3EA5] h-full pb-20 md:pb-0"
             :class="view === 'chat' ? 'hidden md:flex' : 'flex'">
            
            {{-- Header --}}
            <div class="p-6 border-b-2 border-dashed border-[#FF3EA5] shrink-0">
                <h2 class="text-2xl font-black uppercase tracking-tight mb-4">Kotak Masuk</h2>
                <div class="relative">
                    <input type="text" placeholder="Cari Pasien..." 
                        class="w-full bg-pink-50 border-2 border-[#FF3EA5] rounded-xl pl-10 pr-4 py-3 text-xs font-bold text-[#FF3EA5] placeholder-[#FF3EA5]/40 focus:outline-none focus:shadow-[2px_2px_0px_0px_#FF3EA5] transition-all">
                    <div class="absolute left-3 top-3 text-[#FF3EA5]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </div>

            {{-- List --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-2">
                <template x-if="patients.length === 0">
                    <div class="text-center py-10 opacity-50">
                        <p class="text-xs font-bold uppercase">Belum ada pesan masuk.</p>
                    </div>
                </template>

                <template x-for="p in patients" :key="p.id">
                    <div @click="selectChat(p)" 
                         class="group p-4 rounded-2xl cursor-pointer border-2 transition-all duration-200 relative overflow-hidden"
                         :class="activePatient && activePatient.id === p.id 
                            ? 'bg-[#FF3EA5] border-[#FF3EA5] text-white shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)]' 
                            : 'bg-white border-transparent hover:border-[#FF3EA5] hover:bg-pink-50 text-[#FF3EA5]'">
                        
                        <div class="flex items-start gap-4 relative z-10">
                            {{-- Avatar --}}
                            <div class="w-12 h-12 rounded-full border-2 flex items-center justify-center font-black text-lg shrink-0"
                                 :class="activePatient && activePatient.id === p.id ? 'bg-white text-[#FF3EA5] border-white' : 'bg-pink-100 border-[#FF3EA5]'">
                                <span x-text="p.avatar"></span>
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-black text-sm uppercase truncate" x-text="p.name"></h4>
                                    <span class="text-[10px] font-bold opacity-70" x-text="p.time"></span>
                                </div>
                                <p class="text-xs font-medium truncate mt-1 opacity-80" x-text="p.last_msg"></p>
                            </div>
                        </div>

                        {{-- Unread Badge --}}
                        <template x-if="p.unread > 0">
                            <div class="absolute top-4 right-4 w-3 h-3 bg-red-500 rounded-full border-2 border-white animate-pulse"
                                 :class="activePatient && activePatient.id === p.id ? 'border-[#FF3EA5]' : ''"></div>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        {{-- =============================================================== --}}
        {{-- KOLOM KANAN: CHAT ROOM (MAIN AREA) --}}
        {{-- =============================================================== --}}
        <div class="flex-1 bg-pink-50/30 flex flex-col h-full relative"
             :class="view === 'list' ? 'hidden md:flex' : 'flex'">
            
            {{-- EMPTY STATE (Desktop Only) --}}
            <template x-if="!activePatient">
                <div class="hidden md:flex flex-col items-center justify-center h-full text-[#FF3EA5] opacity-50">
                    <div class="w-24 h-24 bg-white border-4 border-dashed border-[#FF3EA5] rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <p class="font-black text-lg uppercase tracking-widest">Pilih pasien untuk memulai chat</p>
                </div>
            </template>

            {{-- CHAT CONTENT --}}
            <template x-if="activePatient">
                <div class="flex flex-col h-full relative">
                    
                    {{-- Header Chat --}}
                    <div class="h-20 bg-white border-b-2 border-[#FF3EA5] flex items-center px-6 shrink-0 justify-between shadow-sm z-20">
                        <div class="flex items-center gap-4">
                            <button @click="backToList" class="md:hidden p-2 -ml-2 text-[#FF3EA5]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                            </button>

                            <div class="w-10 h-10 rounded-full bg-[#FF3EA5] border-2 border-[#FF3EA5] flex items-center justify-center text-white font-black">
                                <span x-text="activePatient.avatar"></span>
                            </div>
                            <div>
                                <h3 class="font-black text-lg uppercase leading-none" x-text="activePatient.name"></h3>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pasien</span>
                            </div>
                        </div>
                    </div>

                    {{-- Messages Area --}}
                    <div id="chat-scroll-area" class="flex-1 overflow-y-auto p-6 space-y-6">
                        <template x-for="msg in messages">
                            <div class="flex flex-col w-full" :class="msg.sender === 'doctor' ? 'items-end' : 'items-start'">
                                <div class="max-w-[85%] md:max-w-[60%] px-5 py-3 rounded-2xl font-bold text-sm shadow-[2px_2px_0px_0px_rgba(0,0,0,0.05)] relative group"
                                     :class="msg.sender === 'doctor' 
                                        ? 'bg-[#FF3EA5] text-white rounded-tr-none' 
                                        : 'bg-white text-[#FF3EA5] border-2 border-[#FF3EA5] rounded-tl-none'">
                                    <span x-text="msg.text" class="leading-relaxed"></span>
                                </div>
                                <span class="text-[10px] text-gray-400 mt-1.5 font-bold uppercase tracking-wide px-1" x-text="msg.time"></span>
                            </div>
                        </template>
                    </div>

                    {{-- Input Area --}}
                    {{-- Mobile: pb-24 (Space untuk navbar bawah). Desktop: lg:pb-6 --}}
                    <div class="bg-white border-t-2 border-dashed border-[#FF3EA5] p-4 md:p-6 shrink-0 pb-24 lg:pb-6">
                        <div class="flex items-end gap-3">
                            <div class="flex-1 relative">
                                <textarea 
                                    x-model="messageInput"
                                    x-ref="input"
                                    rows="1"
                                    @input="resize()"
                                    @keydown.enter.exact.prevent="sendAndReset()"
                                    class="w-full bg-pink-50 border-2 border-[#FF3EA5] rounded-xl px-5 py-3 text-sm font-bold text-[#FF3EA5] placeholder-[#FF3EA5]/50 focus:outline-none focus:shadow-[4px_4px_0px_0px_#FF3EA5] transition-all resize-none overflow-hidden max-h-32 block leading-relaxed"
                                    placeholder="Tulis balasan..."></textarea>
                            </div>
                            <button type="button" @click="sendAndReset()"
                                class="bg-[#FF3EA5] text-white p-3 md:px-6 md:py-3 rounded-xl border-2 border-[#FF3EA5] shadow-[3px_3px_0px_0px_rgba(0,0,0,0.1)] hover:shadow-none hover:translate-x-[1px] hover:translate-y-[1px] transition-all flex items-center gap-2 h-[46px]">
                                <span class="hidden md:inline font-black uppercase text-xs">Kirim</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </div>
                    </div>

                </div>
            </template>
        </div>

    </div>
@endsection
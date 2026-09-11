@extends('layouts.app')

@section('hideMobileHeader', true)
@section('hideMobileNav', true)

@section('content')
    <style>
        /* 1. KUNCI LAYAR: Memaksa area konten pas dengan tinggi layar */
        .chat-container-neo {
            /* Menghitung tinggi: Layar penuh dikurangi padding main layout (jika ada) */
            height: calc(100vh - 40px);
            height: calc(100dvh - 40px);
            display: flex;
            flex-direction: column;
            background-color: white;
            border: 4px solid #FF3EA5;
            border-radius: 2rem;
            box-shadow: 8px 8px 0px 0px #FF3EA5;
            overflow: hidden;
            /* Mencegah container ini meluber */
            position: relative;
        }

        /* Khusus Mobile: Tetap Full Screen total */
        @media (max-width: 768px) {
            .chat-container-neo {
                position: fixed;
                inset: 0;
                height: 100vh;
                height: 100dvh;
                border: none;
                border-radius: 0;
                box-shadow: none;
                z-index: 50;
            }

            body {
                overflow: hidden !important;
            }
        }

        /* 2. AREA PESAN: Ini yang memegang kendali Scroll */
        #chat-scroll-area {
            flex: 1;
            /* Mengambil semua sisa ruang yang tersedia */
            overflow-y: auto;
            padding: 20px;
            background-color: #fdf2f8;
            /* Background chat */
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Gaya Scrollbar Pink */
        #chat-scroll-area::-webkit-scrollbar {
            width: 6px;
        }

        #chat-scroll-area::-webkit-scrollbar-thumb {
            background: #FF3EA5;
            border-radius: 10px;
        }

        /* Bubble Chat Lebar */
        .bubble-chat {
            word-wrap: break-word;
            max-width: 75%;
            /* Supaya tidak terlalu rapat ke pinggir lawan */
            line-height: 1.5;
        }

        /* Mematikan scroll halaman utama agar fokus ke area chat */
        body {
            overflow: hidden !important;
        }
    </style>

    <div class="chat-container-neo" x-data="chatSystem()" x-init="initChat({{ $activeDoctor->id }})">

        {{-- HEADER --}}
        <div class="h-16 md:h-20 bg-white border-b-4 border-[#FF3EA5] flex items-center justify-between px-6 shrink-0 z-20">
            <div class="flex items-center gap-4 min-w-0">
                <a href="{{ route('mama.tanya-dokter') }}"
                    class="text-[#FF3EA5] bg-white border-2 border-[#FF3EA5] p-2 rounded-xl shadow-[3px_3px_0px_0px_#FF3EA5] active:translate-x-[1px] active:translate-y-[1px] active:shadow-none transition-all shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div class="shrink-0">
                    <x-avatar :user="$activeDoctor" size="md" class="w-10 h-10 md:w-12 md:h-12 shadow-sm" />
                </div>
                <div class="min-w-0">
                    {{-- Nama Dokter --}}
                    <h4 class="font-black text-sm md:text-xl uppercase leading-tight text-[#FF3EA5] truncate">
                        {{ $activeDoctor->name }}
                    </h4>
                    <div class="mt-0.5">
                        {{-- GANTI DISINI: Panggil Spesialis dari database --}}
                        <p class="text-[10px] font-black text-[#FF3EA5] uppercase opacity-60 truncate">
                            {{ $activeDoctor->specialist }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- MESSAGES (SCROLL DI SINI) --}}
        <div id="chat-scroll-area">
            <template x-for="msg in messages">
                <div class="flex flex-col w-full animate-fadeIn"
                    :class="msg.sender === 'user' ? 'items-end' : 'items-start'">
                    <div class="bubble-chat px-5 py-3 rounded-[1.5rem] font-bold text-sm border-2 shadow-[4px_4px_0px_0px_rgba(255,62,165,0.1)]"
                        :class="msg.sender === 'user' ?
                            'bg-[#FF3EA5] text-white border-[#FF3EA5] rounded-tr-none' :
                            'bg-white text-[#FF3EA5] border-[#FF3EA5] rounded-tl-none'">
                        <span x-text="msg.text" class="whitespace-pre-wrap"></span>
                    </div>
                    <span class="text-[9px] text-[#FF3EA5] mt-1.5 font-black uppercase opacity-40 px-2"
                        x-text="msg.time"></span>
                </div>
            </template>
        </div>

        {{-- FOOTER INPUT --}}
        <div class="p-4 md:p-6 bg-white border-t-4 border-[#FF3EA5] shrink-0">
            <div class="flex items-end gap-4 max-w-7xl mx-auto">
                <div class="flex-1">
                    <textarea x-model="messageInput" x-ref="input" rows="1" @input="resizeInput"
                        @keydown.enter.exact.prevent="sendAndReset()"
                        class="w-full bg-pink-50 border-2 border-[#FF3EA5] rounded-2xl px-5 py-4 text-sm font-bold text-[#FF3EA5] focus:outline-none focus:shadow-[4px_4px_0px_0px_#FF3EA5] transition-all resize-none max-h-32"
                        placeholder="Ketik pesan untuk dokter di sini..."></textarea>
                </div>
                <button type="button" @click="sendAndReset()"
                    class="bg-[#FF3EA5] text-white p-4 rounded-2xl border-2 border-[#FF3EA5] shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)] active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all shrink-0">
                    <svg class="w-6 h-6 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>
            <div class="h-2 md:hidden"></div>
        </div>
    </div>

    <script>
        function chatSystem() {
            return {
                messageInput: '',
                messages: [],
                doctorId: null,
                initChat(docId) {
                    this.doctorId = docId;
                    this.fetchMessages();
                    setInterval(() => this.fetchMessages(), 3000);
                },
                fetchMessages() {
                    fetch(`/tanya-dokter/messages/${this.doctorId}`)
                        .then(res => res.json())
                        .then(data => {
                            if (JSON.stringify(data) !== JSON.stringify(this.messages)) {
                                this.messages = data;
                                this.$nextTick(() => this.scrollToBottom());
                            }
                        });
                },
                sendMessage() {
                    if (this.messageInput.trim() === '') return;
                    const text = this.messageInput;
                    this.messages.push({
                        text: text,
                        sender: 'user',
                        time: '...'
                    });
                    this.messageInput = '';
                    this.resizeInput();
                    this.$nextTick(() => this.scrollToBottom());
                    fetch('/tanya-dokter/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            doctor_id: this.doctorId,
                            message: text
                        })
                    }).then(() => this.fetchMessages());
                },
                sendAndReset() {
                    this.sendMessage();
                },
                resizeInput() {
                    this.$refs.input.style.height = 'auto';
                    this.$refs.input.style.height = this.$refs.input.scrollHeight + 'px';
                },
                scrollToBottom() {
                    const el = document.getElementById('chat-scroll-area');
                    el.scrollTop = el.scrollHeight;
                }
            }
        }
    </script>
@endsection

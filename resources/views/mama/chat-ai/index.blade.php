@extends('layouts.app')

@section('title', 'Mama AI - Teman Curhat Mama')

@section('hideMobileHeader', true)
@section('hideMobileNav', true)

@section('content')
<style>
    .ai-wrapper {
        position: fixed;
        inset: 0;
        height: 100vh;
        height: 100dvh;
        display: flex;
        flex-direction: column;
        background-color: #FFEFF8;
        overflow: hidden;
        z-index: 50;
    }

    #chat-window {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem 1rem;
        scroll-behavior: smooth;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    @media (min-width: 768px) {
        .ai-wrapper {
            position: relative;
            height: 80vh; 
            max-height: 720px;
            margin: 30px auto;
            max-width: 750px;
            border-radius: 2.5rem;
            border: 2px solid #FF3EA5;
            box-shadow: 10px 10px 0px 0px #FF3EA5;
        }
        body { overflow: auto !important; background-color: #FFEFF8; }
    }

    .bubble-ai { 
        word-wrap: break-word; 
        max-width: 85%; 
        line-height: 1.5;
    }

    #chat-window::-webkit-scrollbar { width: 4px; }
    #chat-window::-webkit-scrollbar-thumb { background: #FF3EA5; border-radius: 10px; }
</style>

<div class="ai-wrapper bg-white" x-data="chatSystem()">
    
    {{-- Header --}}
    <div class="h-16 md:h-20 bg-white border-b-2 border-[#FF3EA5] flex items-center px-4 md:px-6 shrink-0 z-20">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" @click.stop
                class="bg-white border-2 border-[#FF3EA5] p-2 rounded-xl shadow-[3px_3px_0px_0px_#FF3EA5] active:translate-x-0.5 active:translate-y-0.5 active:shadow-none transition-all">
                <svg class="w-5 h-5 text-[#FF3EA5]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                    <path d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-pink-50 rounded-xl flex items-center justify-center border-2 border-[#FF3EA5] shrink-0 overflow-hidden shadow-[2px_2px_0px_0px_#FF3EA5]">
                    <img src="{{ asset('images/fitur-chat.png') }}" alt="Mama AI" class="w-full h-full object-cover">
                </div>
                <div class="min-w-0">
                    <h2 class="text-base md:text-lg font-black text-[#FF3EA5] uppercase italic leading-none truncate">Mama AI</h2>
                    <p class="text-[8px] md:text-[9px] font-bold text-[#FF3EA5] opacity-60 uppercase tracking-widest mt-1">Chatbot Pintar Mama</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Area Pesan --}}
    <div id="chat-window" class="bg-pink-50/20">
        {{-- Welcome Message: Update Kalimat & Desain --}}
    <div class="flex justify-start pt-2">
        <div class="bg-white border-2 border-[#FF3EA5] p-3 md:p-4 rounded-2xl rounded-bl-none shadow-[4px_4px_0px_0px_#FF3EA5] bubble-ai text-xs md:text-sm font-bold text-[#FF3EA5] leading-relaxed">
            Halo Mama! Ada yang bisa Mama AI bantu hari ini? Mau tanya soal nutrisi, perkembangan si kecil, atau sekadar curhat? Mama AI siap dengerin! 😊
        </div>
    </div>

        <template x-for="(chat, index) in messages" :key="index">
            <div :class="chat.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                <div :class="chat.role === 'user' ?
                    'bg-[#FF3EA5] text-white p-3 md:p-4 rounded-2xl rounded-br-none shadow-[-4px_4px_0px_0px_rgba(255,62,165,0.2)] border-2 border-[#FF3EA5]' :
                    'bg-white border-2 border-[#FF3EA5] text-[#FF3EA5] p-3 md:p-4 rounded-2xl rounded-bl-none shadow-[4px_4px_0px_0px_#FF3EA5]'"
                    class="bubble-ai text-xs md:text-sm font-bold whitespace-pre-line"
                    x-text="chat.text">
                </div>
            </div>
        </template>

        {{-- EFEK TYPING: 3 Titik Bounce Lengkap --}}
        <div x-show="isLoading" class="flex justify-start animate-fadeIn">
            <div class="bg-white border-2 border-[#FF3EA5] p-3 md:p-4 rounded-2xl rounded-bl-none shadow-[3px_3px_0px_0px_#FF3EA5] flex items-center gap-3">
                <div class="flex gap-1.5">
                    <span class="w-2 h-2 bg-[#FF3EA5] rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                    <span class="w-2 h-2 bg-[#FF3EA5] rounded-full animate-bounce" style="animation-delay: 200ms"></span>
                    <span class="w-2 h-2 bg-[#FF3EA5] rounded-full animate-bounce" style="animation-delay: 400ms"></span>
                </div>
                <span class="text-[10px] font-black text-[#FF3EA5] uppercase italic tracking-wider">Mama AI mengetik...</span>
            </div>
        </div>
    </div>

    {{-- Input Area --}}
    <div class="p-3 md:p-6 bg-white border-t-2 border-[#FF3EA5] shrink-0">
        <form @submit.prevent.stop="sendMessage" class="flex gap-2 md:gap-3 max-w-4xl mx-auto items-center">
            <input type="text" x-model="userInput" 
                placeholder="Tanya Mama AI..."
                class="flex-1 border-2 border-[#FF3EA5] p-3 md:p-4 rounded-xl font-bold text-[#FF3EA5] outline-none focus:shadow-[4px_4px_0px_0px_#FF3EA5] transition-all text-xs md:text-sm"
                :disabled="isLoading">
            
            <button type="submit" :disabled="isLoading || !userInput.trim()" @click.stop
                class="bg-[#FF3EA5] text-white p-3 md:px-5 md:py-3.5 rounded-xl border-2 border-[#FF3EA5] shadow-[3px_3px_0px_0px_#ff90c8] active:translate-x-0.5 active:translate-y-0.5 active:shadow-none disabled:opacity-40 transition-all shrink-0">
                <div class="flex items-center gap-2">
                    <span class="hidden md:inline font-black text-xs uppercase tracking-wider">Kirim</span>
                    <svg class="w-5 h-5 md:w-6 md:h-6 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </div>
            </button>
        </form>
        <div class="h-2 md:hidden"></div>
    </div>
</div>

<script>
    function chatSystem() {
        return {
            userInput: '',
            messages: [],
            isLoading: false,
            sendMessage() {
                if (!this.userInput.trim() || this.isLoading) return;
                const userText = this.userInput;
                const chatHistory = JSON.parse(JSON.stringify(this.messages));
                this.messages.push({ role: 'user', text: userText });
                this.userInput = '';
                this.isLoading = true;
                this.scrollToBottom();

                fetch("{{ route('mama.ai.send') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: JSON.stringify({ message: userText, history: chatHistory })
                })  
                .then(res => res.json())
                .then(data => {
                    let cleanReply = data.reply.replace(/[*]/g, '').trim();
                    this.messages.push({ role: 'ai', text: cleanReply });
                })
                .catch(() => {
                    this.messages.push({ role: 'ai', text: "Maaf Ma, koneksi lagi kurang stabil. Coba tanya lagi ya!" });
                })
                .finally(() => {
                    this.isLoading = false;
                    this.scrollToBottom();
                });
            },
            scrollToBottom() {
                this.$nextTick(() => {
                    const el = document.getElementById('chat-window');
                    if(el) el.scrollTop = el.scrollHeight;
                });
            }
        }
    }
</script>
@endsection
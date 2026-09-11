@extends('layouts.guest')

@section('title', 'Verifikasi Email - Mamacare')

@section('content')
    <div class="w-full min-h-screen flex items-center justify-center bg-white p-6 relative">
        
        {{-- Card Tengah --}}
        <div class="w-full max-w-md text-center">
            
            {{-- Ikon Email --}}
            <div class="flex justify-center mb-8">
                <div class="w-24 h-24 bg-pink-50 border-2 border-primary rounded-[2.5rem] flex items-center justify-center shadow-[6px_6px_0px_0px_#ff90c8]">
                    <span class="text-5xl text-primary animate-bounce">📧</span>
                </div>
            </div>

            {{-- Welcome & Instructions --}}
            <div class="mb-8">
                <h2 class="text-3xl font-black text-primary uppercase tracking-tighter leading-none mb-4">Verifikasi Email</h2>
                <div class="bg-pink-50 p-6 rounded-[2rem] border-2 border-dashed border-primary">
                    <p class="text-[11px] font-bold text-primary uppercase tracking-widest leading-relaxed">
                        Terima kasih sudah mendaftar! Sebelum mulai, silakan klik link verifikasi yang baru saja kami kirim ke email Mama ya.
                    </p>
                </div>
            </div>

            {{-- Pesan Sukses Kirim Ulang --}}
            @if (session('status') == 'verification-link-sent')
                <div class="mb-8 p-4 bg-primary text-white rounded-2xl shadow-[4px_4px_0px_0px_#ff90c8] text-[10px] font-black uppercase tracking-[0.15em]">
                    Link verifikasi baru telah dikirim ke email Mama! ✨
                </div>
            @endif

            {{-- Actions --}}
            <div class="space-y-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" 
                        class="w-full bg-primary text-white font-black py-5 rounded-2xl uppercase text-[12px] tracking-[0.2em] shadow-[6px_6px_0px_0px_#ff90c8] hover:shadow-none hover:translate-x-1 hover:translate-y-1 active:scale-95 transition-all">
                        Kirim Ulang Email Verifikasi 🚀
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button type="submit" class="text-[10px] font-black text-pink-300 uppercase tracking-widest hover:text-primary underline decoration-2 underline-offset-8 transition-colors">
                        Keluar Akun (Log Out)
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
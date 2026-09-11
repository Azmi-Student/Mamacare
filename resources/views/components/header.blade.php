<div class="flex items-center gap-4">
    
    <div x-data="{ open: false }" class="relative">
        
        <button @click="open = !open" class="flex items-center gap-2 focus:outline-none group">
            {{-- Avatar dari komponen x-avatar --}}
            <x-avatar :user="auth()->user()" size="sm" class="w-9 h-9" />
        </button>

        <div x-show="open" 
             @click.away="open = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
             class="absolute right-0 mt-3 w-52 bg-white border-2 border-[#FF3EA5] rounded-2xl shadow-[4px_4px_0px_0px_#ff90c8] overflow-hidden z-50"
             style="display: none;">
            
            {{-- Info User --}}
            <div class="px-4 py-3 border-b-2 border-dashed border-pink-200 flex items-center gap-3">
                <x-avatar :user="auth()->user()" size="sm" class="w-8 h-8" />
                <div class="min-w-0">
                    <p class="text-xs font-black text-[#FF3EA5] uppercase truncate">{{ Auth::user()->name ?? 'Guest' }}</p>
                    <p class="text-[9px] font-bold text-pink-300 uppercase">{{ ucfirst(Auth::user()->role ?? 'Guest') }}</p>
                </div>
            </div>

            {{-- Menu Items --}}
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-black text-[#FF3EA5] uppercase tracking-wide hover:bg-pink-50 transition-colors">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                Pengaturan
            </a>
            
            <div class="h-0.5 bg-pink-100 mx-3"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-xs font-black text-red-500 uppercase tracking-wide hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>
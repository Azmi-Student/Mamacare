<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        {{-- Password Saat Ini --}}
        <div class="space-y-2">
            <label for="current_password" class="block text-[10px] font-black text-[#FF3EA5] uppercase tracking-[0.2em] ml-1">
                Kata Sandi Saat Ini
            </label>
            <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                class="w-full border-2 border-[#FF3EA5] p-3.5 rounded-2xl font-bold text-[#FF3EA5] bg-white shadow-[4px_4px_0px_0px_#ff90c8] focus:ring-0 focus:border-[#FF3EA5] focus:shadow-none focus:translate-x-0.5 focus:translate-y-0.5 outline-none transition-all">
            @error('current_password', 'updatePassword')
                <p class="text-[9px] text-red-400 font-bold mt-2 ml-1 uppercase tracking-tight">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password Baru --}}
        <div class="space-y-2">
            <label for="password" class="block text-[10px] font-black text-[#FF3EA5] uppercase tracking-[0.2em] ml-1">
                Kata Sandi Baru
            </label>
            <input id="password" name="password" type="password" autocomplete="new-password"
                class="w-full border-2 border-[#FF3EA5] p-3.5 rounded-2xl font-bold text-[#FF3EA5] bg-white shadow-[4px_4px_0px_0px_#ff90c8] focus:ring-0 focus:border-[#FF3EA5] focus:shadow-none focus:translate-x-0.5 focus:translate-y-0.5 outline-none transition-all">
            @error('password', 'updatePassword')
                <p class="text-[9px] text-red-400 font-bold mt-2 ml-1 uppercase tracking-tight">{{ $message }}</p>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="space-y-2">
            <label for="password_confirmation" class="block text-[10px] font-black text-[#FF3EA5] uppercase tracking-[0.2em] ml-1">
                Konfirmasi Kata Sandi Baru
            </label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                class="w-full border-2 border-[#FF3EA5] p-3.5 rounded-2xl font-bold text-[#FF3EA5] bg-white shadow-[4px_4px_0px_0px_#ff90c8] focus:ring-0 focus:border-[#FF3EA5] focus:shadow-none focus:translate-x-0.5 focus:translate-y-0.5 outline-none transition-all">
            @error('password_confirmation', 'updatePassword')
                <p class="text-[9px] text-red-400 font-bold mt-2 ml-1 uppercase tracking-tight">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Ganti Password --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit" 
                class="bg-[#FF3EA5] text-white font-black px-8 py-3.5 rounded-2xl uppercase text-[10px] tracking-widest shadow-[4px_4px_0px_0px_#ff90c8] hover:shadow-none hover:translate-x-0.5 hover:translate-y-0.5 active:scale-95 transition-all">
                Perbarui Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" 
                   class="flex items-center gap-2 text-[10px] font-black text-[#FF3EA5] uppercase tracking-widest">
                   <span>✓ Berhasil Diperbarui! 🔐</span>
                </div>
            @endif
        </div>
    </form>
</section>
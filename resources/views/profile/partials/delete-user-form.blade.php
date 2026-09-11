<section class="space-y-6">
    <header>
        <p class="text-[10px] font-black text-red-500 uppercase tracking-[0.2em] mb-2">
            Peringatan Keamanan
        </p>
        <p class="text-[11px] font-bold text-red-400 leading-relaxed uppercase tracking-tight">
            Setelah akun dihapus, semua data Anda dalam sistem akan dihapus secara permanen dan tidak dapat dikembalikan.
        </p>
    </header>

    {{-- Tombol Pemicu Modal --}}
    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-500 text-white font-black px-6 py-3 rounded-2xl uppercase text-[10px] tracking-widest shadow-[4px_4px_0px_0px_#fca5a5] hover:shadow-none hover:translate-x-0.5 hover:translate-y-0.5 active:scale-95 transition-all"
    >
        Hapus Akun Permanen
    </button>

    {{-- Modal Konfirmasi --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 bg-white border-4 border-red-500 rounded-[2.5rem]">
            @csrf
            @method('delete')

            <h2 class="text-xl font-black text-red-500 uppercase tracking-tighter mb-4">
                Konfirmasi Penghapusan Akun
            </h2>

            <p class="text-[11px] font-bold text-gray-500 uppercase leading-relaxed tracking-tight mb-6">
                Silakan masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda benar-benar ingin menghapus akun ini secara permanen.
            </p>

            <div class="space-y-2">
                <label for="password" class="sr-only">Kata Sandi</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Masukkan Kata Sandi Anda"
                    class="w-full border-2 border-red-500 p-3.5 rounded-2xl font-bold text-red-500 bg-white shadow-[4px_4px_0px_0px_#fee2e2] focus:ring-0 focus:border-red-500 outline-none transition-all placeholder:text-red-200"
                />
                @error('password', 'userDeletion') 
                    <p class="text-[9px] text-red-500 font-bold mt-2 ml-1 uppercase">{{ $message }}</p> 
                @enderror
            </div>

            <div class="mt-8 flex flex-col md:flex-row justify-end gap-3">
                {{-- Tombol Batal --}}
                <button 
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="bg-white border-2 border-[#FF3EA5] text-[#FF3EA5] font-black px-6 py-3 rounded-2xl uppercase text-[10px] tracking-widest shadow-[3px_3px_0px_0px_#ff90c8] active:shadow-none active:translate-x-0.5 active:translate-y-0.5 transition-all">
                    Batalkan
                </button>

                {{-- Tombol Hapus Fix --}}
                <button 
                    type="submit"
                    class="bg-red-500 text-white font-black px-6 py-3 rounded-2xl uppercase text-[10px] tracking-widest shadow-[4px_4px_0px_0px_#fca5a5] active:shadow-none active:translate-x-0.5 active:translate-y-0.5 transition-all">
                    Ya, Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>
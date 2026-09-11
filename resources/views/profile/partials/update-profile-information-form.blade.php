<section x-data="{ 
    avatarPreview: null,
    handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => this.avatarPreview = e.target.result;
            reader.readAsDataURL(file);
        }
    }
}">
    <form method="post" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- Avatar Upload --}}
        <div class="flex flex-col items-center gap-4 pb-6 border-b-2 border-dashed border-pink-200">
            <div class="relative group cursor-pointer" @click="$refs.avatarInput.click()">
                {{-- Preview / Current Avatar --}}
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full overflow-hidden border-4 border-[#FF3EA5] shadow-[4px_4px_0px_0px_#ff90c8] transition-transform group-hover:scale-105">
                    <template x-if="avatarPreview">
                        <img :src="avatarPreview" alt="Preview" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!avatarPreview">
                        @if($user->avatar)
                            <img src="{{ asset($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-pink-100 flex items-center justify-center">
                                <svg class="w-14 h-14 sm:w-16 sm:h-16 text-[#FF3EA5]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            </div>
                        @endif
                    </template>
                </div>
                
                {{-- Overlay Ikon Kamera --}}
                <div class="absolute inset-0 rounded-full bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>

                {{-- Badge --}}
                <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-[#FF3EA5] rounded-full border-2 border-white flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                </div>
            </div>

            <input x-ref="avatarInput" type="file" name="avatar" accept="image/*" class="hidden" @change="handleFileSelect($event)">
            
            <div class="text-center">
                <p class="text-[10px] font-black text-[#FF3EA5] uppercase tracking-widest">Ganti Foto Profil</p>
                <p class="text-[8px] font-bold text-pink-300 uppercase mt-1">JPG, PNG, GIF — Maks. 5MB</p>
            </div>

            {{-- Tombol Hapus Avatar (hanya muncul jika ada avatar) --}}
            @if($user->avatar)
            <div x-data="{ confirmDelete: false }">
                <button type="button" @click="confirmDelete = true" 
                        x-show="!confirmDelete"
                        class="text-[9px] font-black text-red-400 uppercase tracking-widest hover:text-red-600 transition-colors underline underline-offset-2 decoration-dashed">
                    Hapus Foto
                </button>
                <div x-show="confirmDelete" x-transition class="flex items-center gap-2">
                    <span class="text-[9px] font-bold text-red-400 uppercase">Yakin?</span>
                    
                    <button type="button" @click="document.getElementById('delete-avatar-form').submit()" class="text-[9px] font-black text-white bg-red-500 px-3 py-1 rounded-lg uppercase hover:bg-red-600 transition-colors">Ya, Hapus</button>
                    
                    <button type="button" @click="confirmDelete = false" class="text-[9px] font-black text-gray-400 px-2 py-1 rounded-lg uppercase hover:text-gray-600 transition-colors">Batal</button>
                </div>
            </div>
            @endif

            @error('avatar') 
                <p class="text-[9px] text-red-400 font-bold mt-1 uppercase tracking-tight">{{ $message }}</p> 
            @enderror
        </div>

        {{-- Nama Lengkap --}}
        <div class="space-y-2">
            <label for="name" class="block text-[10px] font-black text-[#FF3EA5] uppercase tracking-[0.2em] ml-1">
                Nama Lengkap
            </label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                class="w-full border-2 border-[#FF3EA5] p-3.5 rounded-2xl font-bold text-[#FF3EA5] bg-white shadow-[4px_4px_0px_0px_#ff90c8] focus:ring-0 focus:border-[#FF3EA5] focus:shadow-none focus:translate-x-0.5 focus:translate-y-0.5 outline-none transition-all placeholder:text-pink-200">
            @error('name') 
                <p class="text-[9px] text-red-400 font-bold mt-2 ml-1 uppercase tracking-tight">{{ $message }}</p> 
            @enderror
        </div>

        {{-- Alamat Email --}}
        <div class="space-y-2">
            <label for="email" class="block text-[10px] font-black text-[#FF3EA5] uppercase tracking-[0.2em] ml-1">
                Alamat Email
            </label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                class="w-full border-2 border-[#FF3EA5] p-3.5 rounded-2xl font-bold text-[#FF3EA5] bg-white shadow-[4px_4px_0px_0px_#ff90c8] focus:ring-0 focus:border-[#FF3EA5] focus:shadow-none focus:translate-x-0.5 focus:translate-y-0.5 outline-none transition-all placeholder:text-pink-200">
            @error('email') 
                <p class="text-[9px] text-red-400 font-bold mt-2 ml-1 uppercase tracking-tight">{{ $message }}</p> 
            @enderror

            {{-- Verifikasi Email --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-pink-50 rounded-2xl border-2 border-dashed border-[#FF3EA5]">
                    <p class="text-[10px] font-bold text-[#FF3EA5] uppercase leading-relaxed">
                        Alamat email Anda belum diverifikasi.
                        <button form="send-verification" class="underline decoration-2 underline-offset-4 hover:text-pink-600">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </p>
                </div>
            @endif
        </div>

        {{-- Tombol Simpan --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit" 
                class="bg-[#FF3EA5] text-white font-black px-8 py-3.5 rounded-2xl uppercase text-[10px] tracking-widest shadow-[4px_4px_0px_0px_#ff90c8] hover:shadow-none hover:translate-x-0.5 hover:translate-y-0.5 active:scale-95 transition-all">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" 
                   class="flex items-center gap-2 text-[10px] font-black text-[#FF3EA5] uppercase tracking-widest">
                   <span>✓ Tersimpan</span>
                </div>
            @endif
        </div>
    </form>

    {{-- Form tersembunyi untuk menghapus avatar, harus di luar form utama --}}
    <form id="delete-avatar-form" method="POST" action="{{ route('profile.avatar.destroy') }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</section>
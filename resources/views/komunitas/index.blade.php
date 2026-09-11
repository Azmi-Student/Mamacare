@extends(auth()->user()->role === 'dokter' ? 'layouts.doctor' : 'layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        
        {{-- BAGIAN KIRI: Kolom Utama (Feed & Compose) --}}
        <div class="lg:col-span-2 space-y-6 sm:space-y-8">
            
            {{-- Header --}}
            <div class="flex items-center gap-3 sm:gap-4 mb-2 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-[#FF3EA5] rounded-xl flex items-center justify-center border-2 border-[#FF3EA5] shadow-[3px_3px_0px_0px_#ff90c8] sm:shadow-[4px_4px_0px_0px_#ff90c8] shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black uppercase tracking-tight text-[#FF3EA5]">Ruang Komunitas</h1>
                    <p class="text-xs sm:text-sm font-bold opacity-60 uppercase tracking-widest mt-0.5 sm:mt-1">Tempat berbagi sesama Mama</p>
                </div>
            </div>

            @if(session('success'))
            <div class="bg-green-100 border-2 border-green-500 text-green-700 p-3 sm:p-4 rounded-xl shadow-[4px_4px_0px_0px_#22c55e] font-bold text-sm sm:text-base">
                {{ session('success') }}
            </div>
            @endif

            {{-- Container Form (Desktop & Mobile) --}}
            <div x-data="{ formOpen: false }">
                
                {{-- DESKTOP: Trigger (Collapsed State) --}}
                <div class="hidden sm:flex bg-white border-4 border-[#FF3EA5] rounded-[2rem] p-4 shadow-[8px_8px_0px_0px_#ff90c8] items-center gap-4 cursor-text transition-transform hover:-translate-y-1" 
                     x-show="!formOpen" 
                     @click="formOpen = true; $nextTick(() => $refs.titleInput.focus())">
                    <x-avatar :user="auth()->user()" size="md" />
                    <div class="flex-1 text-[#FF3EA5] font-black text-lg opacity-60">
                        Ada cerita apa hari ini, Bunda?
                    </div>
                </div>

                {{-- MOBILE: Floating Action Button (FAB) --}}
                <button @click="formOpen = true" 
                        class="sm:hidden fixed bottom-24 right-4 z-[45] w-14 h-14 bg-[#FF3EA5] rounded-full border-2 border-[#FF3EA5] flex items-center justify-center text-white shadow-[4px_4px_0px_0px_#ff90c8] active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all hover:bg-pink-600">
                    <svg class="w-8 h-8 stroke-[3px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                </button>

                {{-- THE FORM: Modal di Mobile / Expanded di Desktop --}}
                <div x-show="formOpen" style="display: none;"
                     class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm sm:static sm:z-auto sm:block sm:bg-transparent sm:backdrop-blur-none sm:p-0"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                     
                     {{-- Modal Click Away area (Hanya berlaku kalau di Mobile) --}}
                     <div class="w-full max-w-lg sm:max-w-none" @click.away="window.innerWidth < 640 ? formOpen = false : null">
                          <div class="bg-white border-2 sm:border-4 border-[#FF3EA5] rounded-2xl sm:rounded-[2rem] p-4 sm:p-6 shadow-[4px_4px_0px_0px_#ff90c8] sm:shadow-[8px_8px_0px_0px_#ff90c8]">
                               
                               {{-- Header Modal Khusus HP --}}
                               <div class="flex sm:hidden justify-between items-center mb-4 pb-3 border-b-2 border-dashed border-pink-200">
                                    <h3 class="font-black text-[#FF3EA5] uppercase tracking-wide text-sm">Buat Postingan</h3>
                                    <button @click="formOpen = false" type="button" class="w-8 h-8 flex items-center justify-center rounded-full bg-pink-100 text-[#FF3EA5] hover:bg-[#FF3EA5] hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                               </div>

                               <form action="{{ route('komunitas.store') }}" method="POST" enctype="multipart/form-data" class="no-loader">
                                    @csrf
                                    <div class="flex gap-3 sm:gap-4">
                                        <div class="hidden sm:flex">
                                            <x-avatar :user="auth()->user()" size="md" />
                                        </div>
                                        <div class="flex-1 space-y-3 sm:space-y-4">
                                            <div>
                                                <input x-ref="titleInput" type="text" name="title" required class="w-full bg-pink-50 border-2 border-[#FF3EA5] rounded-xl px-3 py-2.5 sm:px-4 sm:py-3 text-sm font-bold text-[#FF3EA5] focus:outline-none focus:shadow-[4px_4px_0px_0px_#FF3EA5] transition-all" placeholder="Judul Cerita / Topik...">
                                            </div>
                                            <div>
                                                <textarea name="content" required rows="3" class="w-full bg-pink-50 border-2 border-[#FF3EA5] rounded-xl px-3 py-2.5 sm:px-4 sm:py-3 text-sm font-bold text-[#FF3EA5] focus:outline-none focus:shadow-[4px_4px_0px_0px_#FF3EA5] transition-all" placeholder="Ceritakan selengkapnya di sini..."></textarea>
                                            </div>
                                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between border-t-2 border-dashed border-pink-200 pt-3 sm:pt-4 gap-3">
                                                <label class="cursor-pointer group flex items-center justify-center sm:justify-start gap-2 px-3 py-2 rounded-xl bg-pink-50 sm:bg-transparent hover:bg-pink-100 transition-colors border-2 sm:border-transparent border-pink-200 hover:border-[#FF3EA5]">
                                                    <svg class="w-5 h-5 text-[#FF3EA5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="text-xs font-black text-[#FF3EA5] uppercase tracking-wider">Lampirkan Foto</span>
                                                    <input type="file" name="image" accept="image/*" class="hidden">
                                                </label>
                                                
                                                <div class="flex gap-2">
                                                    {{-- Tombol Batal --}}
                                                    <button type="button" @click="formOpen = false" class="hidden sm:block px-4 py-2 rounded-xl border-2 border-gray-300 text-gray-500 font-black uppercase tracking-wider text-sm hover:bg-gray-50 transition-all">Batal</button>
                                                    
                                                    <button type="submit" class="flex-1 sm:flex-none bg-[#FF3EA5] text-white px-6 py-2.5 sm:py-2 rounded-xl border-2 border-[#FF3EA5] font-black uppercase tracking-wider text-sm shadow-[3px_3px_0px_0px_rgba(0,0,0,0.1)] active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all hover:bg-pink-600">
                                                        Posting
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                               </form>
                          </div>
                     </div>
                </div>
            </div>

            {{-- Feed List --}}
            <div class="space-y-4 sm:space-y-6">
                @forelse($posts as $post)
                <div class="bg-white border-2 sm:border-4 border-[#FF3EA5] rounded-2xl sm:rounded-[2rem] p-4 sm:p-6 shadow-[4px_4px_0px_0px_#ff90c8] sm:shadow-[6px_6px_0px_0px_#ff90c8] transition-transform hover:-translate-y-1" x-data="{ showComments: false, showMenu: false, editing: false }">
                    {{-- User Info --}}
                    <div class="flex gap-3 sm:gap-4">
                        <x-avatar :user="$post->user" size="md" />
                        
                        <div class="flex-1 min-w-0">
                            {{-- Header --}}
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex flex-wrap items-center gap-1 sm:gap-2">
                                    <h3 class="font-black text-xs sm:text-sm uppercase text-[#FF3EA5] truncate">{{ $post->user->name }}</h3>
                                    @if($post->user->role === 'dokter')
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-blue-500 fill-current" viewBox="0 0 24 24" title="Verified"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                    @endif
                                    <span class="hidden sm:inline text-xs font-bold text-gray-400">·</span>
                                    <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider w-full sm:w-auto mt-0.5 sm:mt-0">{{ $post->created_at->diffForHumans(null, true, true) }}</span>
                                </div>
                                
                                {{-- DROPDOWN MENU EDIT & DELETE --}}
                                @if(auth()->id() === $post->user_id)
                                <div class="relative ml-2 shrink-0">
                                    <button @click="showMenu = !showMenu" @click.away="showMenu = false" type="button" class="text-gray-400 hover:text-[#FF3EA5] p-1 rounded-full hover:bg-pink-50 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                    </button>
                                    <div x-show="showMenu" style="display: none;" class="absolute right-0 mt-1 w-32 bg-white border-2 border-[#FF3EA5] rounded-xl shadow-[4px_4px_0px_0px_#ff90c8] z-20 overflow-hidden">
                                        <button @click="editing = true; showMenu = false" type="button" class="w-full text-left px-4 py-2 text-xs sm:text-sm font-bold text-gray-700 hover:bg-pink-50 hover:text-[#FF3EA5] transition-colors border-b-2 border-pink-100">Edit</button>
                                        <form action="{{ route('komunitas.destroy', $post->id) }}" method="POST" class="no-loader" onsubmit="return confirm('Yakin ingin menghapus postingan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left px-4 py-2 text-xs sm:text-sm font-bold text-red-600 hover:bg-red-50 transition-colors">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div x-show="!editing" class="mt-2 mb-3">
                                <h2 class="text-lg sm:text-xl font-black text-gray-800 mb-1 sm:mb-2 leading-tight">{{ $post->title }}</h2>
                                <p class="text-gray-600 font-medium text-xs sm:text-sm leading-relaxed whitespace-pre-wrap">{{ $post->content }}</p>
                            </div>

                            {{-- Edit Form --}}
                            <div x-show="editing" style="display: none;" class="mt-2 mb-3">
                                <form action="{{ route('komunitas.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="no-loader">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="title" value="{{ $post->title }}" required class="w-full mb-2 bg-pink-50 border-2 border-[#FF3EA5] rounded-xl px-3 py-2 text-sm font-bold text-[#FF3EA5] focus:outline-none focus:shadow-[4px_4px_0px_0px_#FF3EA5] transition-all">
                                    <textarea name="content" required rows="3" class="w-full mb-2 bg-pink-50 border-2 border-[#FF3EA5] rounded-xl px-3 py-2 text-sm font-bold text-[#FF3EA5] focus:outline-none focus:shadow-[4px_4px_0px_0px_#FF3EA5] transition-all">{{ $post->content }}</textarea>
                                    
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="editing = false" class="px-4 py-1.5 rounded-xl border-2 border-gray-300 text-gray-600 font-bold text-xs hover:bg-gray-50 transition-all">Batal</button>
                                        <button type="submit" class="bg-[#FF3EA5] text-white px-4 py-1.5 rounded-xl border-2 border-[#FF3EA5] font-black uppercase text-xs shadow-[2px_2px_0px_0px_rgba(0,0,0,0.1)] active:translate-x-[1px] active:translate-y-[1px] active:shadow-none hover:bg-pink-600 transition-all">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>

                            @if($post->image)
                            <div class="mb-4 rounded-xl overflow-hidden border-2 border-[#FF3EA5]">
                                <img src="{{ asset($post->image) }}" alt="Post image" class="w-full h-auto object-cover max-h-64 sm:max-h-96">
                            </div>
                            @endif

                            {{-- Actions --}}
                            <div class="flex items-center gap-4 sm:gap-6 mt-3 pt-3 border-t-2 border-dashed border-pink-200">
                                {{-- Like Button --}}
                                <form action="{{ route('komunitas.like', $post->id) }}" method="POST" class="no-loader">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-1.5 sm:gap-2 group transition-colors">
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-transparent group-hover:border-[#FF3EA5] group-hover:bg-pink-50 flex items-center justify-center transition-all">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $post->isLiked ? 'fill-[#FF3EA5] text-[#FF3EA5]' : 'fill-none text-gray-400 group-hover:text-[#FF3EA5]' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </div>
                                        <span class="font-black text-xs sm:text-sm {{ $post->isLiked ? 'text-[#FF3EA5]' : 'text-gray-400 group-hover:text-[#FF3EA5]' }}">{{ $post->likes_count > 0 ? $post->likes_count : '' }}</span>
                                    </button>
                                </form>

                                {{-- Comment Button --}}
                                <button @click="showComments = !showComments" type="button" class="flex items-center gap-1.5 sm:gap-2 group transition-colors">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-transparent group-hover:border-blue-500 group-hover:bg-blue-50 flex items-center justify-center transition-all">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-none text-gray-400 group-hover:text-blue-500" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </div>
                                    <span class="font-black text-xs sm:text-sm text-gray-400 group-hover:text-blue-500">{{ $post->comments_count > 0 ? $post->comments_count : '' }}</span>
                                </button>
                            </div>

                            {{-- Comments Section --}}
                            <div x-show="showComments" class="w-full mt-3 pt-3 sm:mt-4 sm:pt-4 border-t-2 border-dashed border-pink-200" style="display: none;">
                                {{-- Comment List --}}
                                <div class="space-y-3 sm:space-y-4 mb-3 sm:mb-4">
                                    @foreach($post->comments as $comment)
                                    <div class="flex gap-2 sm:gap-3">
                                        <div class="shrink-0 mt-1">
                                            <x-avatar :user="$comment->user" size="sm" />
                                        </div>
                                        <div class="flex-1 bg-pink-50 p-2 sm:p-3 rounded-xl border sm:border-2 border-pink-200">
                                            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mb-1">
                                                <h4 class="font-black text-[10px] sm:text-[11px] text-[#FF3EA5] uppercase">{{ $comment->user->name }}</h4>
                                                @if($comment->user->role === 'dokter')
                                                    <span class="text-[8px] sm:text-[9px] font-bold bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded-full uppercase">Dokter</span>
                                                @endif
                                                <span class="text-[8px] sm:text-[9px] font-bold text-gray-400 uppercase w-full sm:w-auto mt-0.5 sm:mt-0">
                                                    <span class="hidden sm:inline">· </span>{{ $comment->created_at->diffForHumans(null, true, true) }}
                                                </span>
                                            </div>
                                            <p class="text-[11px] sm:text-xs text-gray-700 font-bold leading-relaxed">{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                {{-- Add Comment Form --}}
                                <form action="{{ route('komunitas.comment', $post->id) }}" method="POST" class="flex flex-col sm:flex-row gap-2 no-loader">
                                    @csrf
                                    <input type="text" name="content" required class="flex-1 w-full bg-white border-2 border-[#FF3EA5] rounded-xl px-3 py-2 sm:px-4 sm:py-2 text-xs sm:text-sm font-bold focus:shadow-[3px_3px_0px_0px_#FF3EA5] sm:focus:shadow-[4px_4px_0px_0px_#FF3EA5] focus:outline-none transition-all" placeholder="Tulis balasanmu di sini...">
                                    <button type="submit" class="w-full sm:w-auto bg-[#FF3EA5] text-white px-4 py-2 rounded-xl font-black uppercase tracking-wide text-xs border-2 border-[#FF3EA5] shadow-[2px_2px_0px_0px_rgba(0,0,0,0.1)] active:translate-x-[1px] active:translate-y-[1px] active:shadow-none hover:bg-pink-600 transition-all">Balas</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 sm:py-12 bg-white border-2 sm:border-4 border-dashed border-gray-300 rounded-2xl sm:rounded-[2rem]">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-3 sm:mb-4 bg-gray-100 rounded-full flex items-center justify-center opacity-50">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-black text-gray-400 uppercase tracking-tight">Belum ada obrolan</h3>
                    <p class="text-xs sm:text-sm font-bold text-gray-400 mt-1 sm:mt-2">Jadilah yang pertama memulai topik!</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- BAGIAN KANAN: Sidebar Widget (Fitur ala X) --}}
        <div class="space-y-6 mt-8 lg:mt-0">
            
            {{-- Widget: Info Komunitas --}}
            <div class="bg-white border-2 sm:border-4 border-[#FF3EA5] rounded-2xl sm:rounded-[2rem] p-5 sm:p-6 shadow-[4px_4px_0px_0px_#ff90c8] sm:shadow-[6px_6px_0px_0px_#ff90c8]">
                <h3 class="font-black text-base sm:text-lg text-[#FF3EA5] uppercase tracking-tight mb-2 sm:mb-3">Tentang Komunitas</h3>
                <p class="text-xs sm:text-sm font-bold text-gray-600 leading-relaxed mb-4">Ruang aman untuk saling mendukung, berbagi cerita, dan mendapatkan informasi seputar kehamilan dan parenting dari sesama Mama dan Dokter Ahli.</p>
                <div class="flex items-center gap-2 text-xs sm:text-sm font-black text-gray-500">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#FF3EA5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    {{ \App\Models\User::where('role', 'mama')->count() }} Mama Bergabung
                </div>
            </div>

            {{-- Widget: Aturan Main --}}
            <div class="bg-[#FF3EA5] border-2 sm:border-4 border-[#FF3EA5] rounded-2xl sm:rounded-[2rem] p-5 sm:p-6 shadow-[4px_4px_0px_0px_#ff90c8] sm:shadow-[6px_6px_0px_0px_#ff90c8] text-white">
                <h3 class="font-black text-base sm:text-lg uppercase tracking-tight mb-3 sm:mb-4">Aturan Main 📌</h3>
                <ul class="space-y-2.5 sm:space-y-3 text-xs sm:text-sm font-bold opacity-90">
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5">💖</span> Saling menghargai sesama Mama
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5">🚫</span> Dilarang berjualan/spam
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5">🩺</span> Konsultasi medis resmi harap gunakan menu Tanya Dokter
                    </li>
                </ul>
            </div>

        </div>

    </div>
</div>
@endsection

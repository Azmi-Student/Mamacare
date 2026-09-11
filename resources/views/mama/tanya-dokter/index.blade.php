@extends('layouts.app')

@section('title', 'Tanya Dokter - Mamacare')

@section('content')
    <div class="min-h-screen bg-white text-[#FF3EA5] py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            {{-- Header Page --}}
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-6 border-b-2 border-[#FF3EA5] pb-8">
                <div>
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-[#FF3EA5] text-[#FF3EA5] font-black uppercase rounded-xl shadow-[3px_3px_0px_0px_#FF3EA5] hover:bg-pink-50 hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all text-[10px] md:text-xs mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <h2 class="text-3xl md:text-5xl font-black uppercase tracking-tight leading-none text-[#FF3EA5]">Tanya Dokter</h2>
                </div>

                {{-- Search Bar Aesthetic --}}
                <div class="w-full md:w-auto">
                    <div class="relative group">
                        <input type="text" placeholder="Cari Dokter..."
                            class="w-full md:w-64 pl-4 pr-10 py-3 bg-white border-2 border-[#FF3EA5] rounded-xl font-bold text-sm text-[#FF3EA5] placeholder-[#FF3EA5]/40 focus:outline-none focus:shadow-[4px_4px_0px_0px_#FF3EA5] transition-all">
                        <div class="absolute right-3 top-3 text-[#FF3EA5]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grid Dokter List --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @foreach ($doctors as $doc)
                    <div
                        class="bg-white border-2 border-[#FF3EA5] rounded-[2rem] p-6 shadow-[6px_6px_0px_0px_#FF3EA5] hover:shadow-none hover:translate-x-[3px] hover:translate-y-[3px] transition-all flex flex-col h-full group">

                        {{-- Profile Info --}}
                        <div class="flex items-center gap-5 mb-6">
                            <x-avatar :user="$doc" size="lg" class="shadow-sm" />

                            <div class="min-w-0">
                                <h4 class="font-black text-lg uppercase leading-tight truncate text-[#FF3EA5]">
                                    {{ $doc->name }}
                                </h4>
                                <div class="mt-1">
                                    <p class="text-[10px] font-black opacity-60 uppercase tracking-tighter">
                                        {{ $doc->specialist }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Actions --}}
                        <div class="mt-auto grid grid-cols-5 gap-3">
                            {{-- Tombol Chat In-App --}}
                            <a href="{{ route('mama.tanya-dokter.chat', $doc['id']) }}"
                                class="col-span-4 bg-[#FF3EA5] text-white border-2 border-[#FF3EA5] py-3 rounded-2xl font-black uppercase text-xs shadow-[3px_3px_0px_0px_rgba(0,0,0,0.1)] hover:bg-white hover:text-[#FF3EA5] transition-all flex items-center justify-center gap-2 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                    </path>
                                </svg>
                                Chat Sekarang
                            </a>

                            {{-- Tombol WhatsApp --}}
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $doc->phone_number) }}?text=Halo%20{{ urlencode($doc->name) }},%20saya%20ingin%20konsultasi%20melalui%20Mamacare."
                                target="_blank"
                                class="col-span-1 bg-white text-[#FF3EA5] border-2 border-[#FF3EA5] rounded-2xl flex items-center justify-center hover:bg-pink-50 transition-colors shadow-[3px_3px_0px_0px_rgba(255,62,165,0.1)] active:scale-90">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

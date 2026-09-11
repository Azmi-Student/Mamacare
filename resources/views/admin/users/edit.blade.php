@extends('layouts.app')

@section('title', 'Edit User - Mamacare')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-rose-500">
            <div class="p-6 text-gray-900">
                
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Edit Data User</h3>
                        <p class="text-sm text-gray-500">Perbarui informasi akun untuk {{ $user->name }}</p>
                    </div>
                </div>

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    {{-- Input Nama --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500 transition" 
                               value="{{ old('name', $user->name) }}" required>
                        @error('name') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Input Email --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500 transition" 
                               value="{{ old('email', $user->email) }}" required>
                        @error('email') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Select Role --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role / Hak Akses</label>
                        <select name="role" id="roleSelect" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500 transition">
                            <option value="mama" {{ $user->role == 'mama' ? 'selected' : '' }}>Mama</option>
                            <option value="dokter" {{ $user->role == 'dokter' ? 'selected' : '' }}>Dokter</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    {{-- ========================================== --}}
{{-- BAGIAN FORM TAMBAHAN KHUSUS DOKTER (EDIT) --}}
{{-- ========================================== --}}
<div id="doctorFields" class="{{ $user->role == 'dokter' ? '' : 'hidden' }} bg-rose-50 p-6 rounded-xl border-2 border-dashed border-rose-200 mb-8">
    <h4 class="text-rose-700 font-bold text-sm mb-4 flex items-center gap-2">
        🩺 Detail Profesi Dokter
    </h4>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-xs font-bold text-rose-600 uppercase mb-1">Spesialisasi</label>
            <input type="text" name="specialist" 
                value="{{ old('specialist', $user->doctor->specialist ?? '') }}"
                class="w-full rounded-lg border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm" 
                placeholder="Contoh: Spesialis Kandungan">
        </div>

        {{-- Input WhatsApp Edit --}}
        <div>
            <label class="block text-xs font-bold text-rose-600 uppercase mb-1">Nomor WhatsApp</label>
            <input type="text" name="phone_number" 
                value="{{ old('phone_number', $user->doctor->phone_number ?? '') }}"
                class="w-full rounded-lg border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm" 
                placeholder="Contoh: 08123456789">
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-rose-600 uppercase mb-1">Pengalaman (Tahun)</label>
            <input type="number" name="experience" 
                value="{{ old('experience', $user->doctor->experience ?? '') }}"
                class="w-full rounded-lg border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm" 
                placeholder="Contoh: 5">
        </div>
    </div>

    <div>
        <label class="block text-xs font-bold text-rose-600 uppercase mb-1">Deskripsi Profil</label>
        <textarea name="description" rows="3" 
            class="w-full rounded-lg border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm" 
            placeholder="Ceritakan singkat profil dokter...">{{ old('description', $user->doctor->description ?? '') }}</textarea>
    </div>
</div>

                    {{-- Action Buttons --}}
                    <div class="flex justify-end items-center gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                             Batal
                        </a>
                        
                        <button type="submit" 
                                class="px-6 py-2 text-sm font-semibold text-white bg-rose-500 rounded-lg hover:bg-rose-600 focus:ring-4 focus:ring-rose-200 transition shadow-md">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script yang sama dengan Create --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('roleSelect');
        const doctorFields = document.getElementById('doctorFields');

        roleSelect.addEventListener('change', function() {
            if (this.value === 'dokter') {
                doctorFields.classList.remove('hidden');
            } else {
                doctorFields.classList.add('hidden');
            }
        });
    });
</script>
@endsection
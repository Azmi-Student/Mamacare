@extends('layouts.app')

@section('title', 'Dashboard Mamacare')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-bold mb-4">Ringkasan Sistem</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-4 bg-blue-100 border-l-4 border-blue-500 rounded">
                        <p class="text-sm text-blue-600 font-semibold uppercase">Total Mama</p>
                        <p class="text-2xl font-bold">{{ $totalMama }}</p>
                    </div>
                    <div class="p-4 bg-green-100 border-l-4 border-green-500 rounded">
                        <p class="text-sm text-green-600 font-semibold uppercase">Total Dokter</p>
                        <p class="text-2xl font-bold">{{ $totalDokter }}</p>
                    </div>
                    <div class="p-4 bg-purple-100 border-l-4 border-purple-500 rounded">
                        <p class="text-sm text-purple-600 font-semibold uppercase">Total Admin</p>
                        <p class="text-2xl font-bold">{{ $totalAdmin }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h3 class="text-lg font-bold">Kelola Pengguna</h3>
                    
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        + Tambah User Baru
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="p-3 text-sm font-semibold text-gray-700">Nama</th>
                                <th class="p-3 text-sm font-semibold text-gray-700">Email</th>
                                <th class="p-3 text-sm font-semibold text-gray-700">Role</th>
                                <th class="p-3 text-sm font-semibold text-center text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="p-3 text-sm text-gray-800 font-medium">{{ $user->name }}</td>
                                <td class="p-3 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $user->role === 'dokter' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $user->role === 'mama' ? 'bg-blue-100 text-blue-800' : '' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="p-3 text-center">
                                    <div class="flex justify-center items-center gap-4">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-bold transition">
                                            Edit
                                        </a>

                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400 italic font-medium">Anda</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
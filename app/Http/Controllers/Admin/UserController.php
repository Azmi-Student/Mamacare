<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi Dasar
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,dokter,mama'],
        ];

        // 2. Validasi Tambahan Jika Role Dokter
        if ($request->role === 'dokter') {
            $rules['specialist'] = ['required', 'string', 'max:100'];
            $rules['experience'] = ['required', 'numeric', 'min:0'];
            $rules['phone_number'] = ['required', 'string', 'max:20']; // <--- TAMBAHAN VALIDASI WA
            $rules['description'] = ['nullable', 'string'];
        }

        $request->validate($rules);

        // 3. Simpan ke tabel Users
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // 4. Simpan ke tabel Doctors jika role-nya dokter
        if ($request->role === 'dokter') {
            Doctor::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'specialist' => $request->specialist,
                'phone_number' => $request->phone_number, // <--- TAMBAHAN SIMPAN WA
                'experience' => $request->experience,
                'description' => $request->description,
                'image' => 'https://ui-avatars.com/api/?name=' . urlencode($request->name) . '&background=random',
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $user->load('doctor'); 
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:admin,dokter,mama'],
        ];

        if ($request->role === 'dokter') {
            $rules['specialist'] = ['required', 'string', 'max:100'];
            $rules['experience'] = ['required', 'numeric', 'min:0'];
            $rules['phone_number'] = ['required', 'string', 'max:20']; // <--- TAMBAHAN VALIDASI WA SAAT UPDATE
        }

        $request->validate($rules);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);
        
        if ($request->role === 'dokter') {
            Doctor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $request->name,
                    'specialist' => $request->specialist,
                    'phone_number' => $request->phone_number, // <--- TAMBAHAN UPDATE WA
                    'experience' => $request->experience,
                    'description' => $request->description,
                ]
            );
        } else {
            Doctor::where('user_id', $user->id)->delete();
        }

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus');
    }
}
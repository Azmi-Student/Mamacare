<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;

class ReservationController extends Controller
{
    /**
     * Menampilkan daftar reservasi pasien (Index)
     */
    public function index()
    {
        // Ambil data dokter yang sedang login
        $dokter = auth()->user()->doctor;

        if (!$dokter) {
            abort(403, 'Profil Dokter belum disetting. Harap hubungi Admin.');
        }

        // Ambil Appointment milik dokter ini
        $appointments = Appointment::where('doctor_id', $dokter->id)
            ->with('user')
            ->orderBy('date', 'desc') // Tanggal terbaru di atas
            ->orderBy('time', 'asc')
            ->paginate(10);

        return view('dokter.reservasi.index', compact('appointments'));
    }

    /**
     * Menampilkan form edit/periksa pasien
     */
    public function edit($id)
    {
        $dokter = auth()->user()->doctor;

        // Pastikan appointment ini milik dokter yang login
        $appointment = Appointment::where('id', $id)
            ->where('doctor_id', $dokter->id)
            ->with('user')
            ->firstOrFail();
        
        return view('dokter.reservasi.edit', compact('appointment'));
    }

    /**
     * Menyimpan hasil pemeriksaan (Update status & notes)
     */
    public function update(Request $request, $id)
    {
        $dokter = auth()->user()->doctor;

        $request->validate([
            'status'       => 'required|in:pending,confirmed,completed,cancelled',
            'diagnosis'    => 'required_if:status,completed|nullable|string',
            'prescription' => 'nullable|string',
            // VALIDASI BARU: Array max 3 file
            'images'       => 'nullable|array|max:3',
            'images.*'     => 'image|mimes:jpeg,png,jpg|max:10240', // Tiap file max 10MB
        ], [
            'diagnosis.required_if' => 'Wajib mengisi Diagnosa Medis jika status kunjungan Selesai.',
            'images.max' => 'Maksimal hanya boleh mengupload 3 foto.',
            'images.*.max' => 'Ukuran salah satu gambar terlalu besar (Max 10MB).',
        ]);

        $appointment = Appointment::where('id', $id)
            ->where('doctor_id', $dokter->id)
            ->firstOrFail();

        // Cek agar tidak mundur status
        if ($appointment->status == 'completed' && ($request->status == 'pending' || $request->status == 'confirmed')) {
            return back()->withErrors(['status' => 'Data Selesai tidak bisa dikembalikan ke status awal.']);
        }

        $data = $request->only(['status', 'diagnosis', 'prescription']);

        // LOGIKA PENYIMPANAN BARU (MULTIPLE)
        if ($request->hasFile('images')) {
            // 1. Ambil data gambar lama (jika ada) sebagai array
            // Cek apakah format lama JSON atau String biasa
            $existingImages = [];
            if ($appointment->image) {
                $decoded = json_decode($appointment->image, true);
                if (is_array($decoded)) {
                    $existingImages = $decoded;
                } else {
                    // Backwards compatibility kalau dulu cuma 1 file string
                    $existingImages = [$appointment->image];
                }
            }

            // 2. Simpan gambar baru
            $newImages = [];
            foreach ($request->file('images') as $file) {
                $path = $file->store('pemeriksaan', 'public');
                $newImages[] = $path;
            }

            // 3. Gabungkan (Opsional: Kalau mau replace total, hapus bagian array_merge)
            // Disini saya buat REPLACE TOTAL sesuai request (upload baru menimpa yang lama agar slotnya rapi)
            
            // Hapus file lama fisik (cleanup) - Opsional
            foreach ($existingImages as $oldImg) {
                if (\Illuminate\Support\Facades\Storage::exists('public/' . $oldImg)) {
                    \Illuminate\Support\Facades\Storage::delete('public/' . $oldImg);
                }
            }

            // Simpan path baru sebagai JSON
            $data['image'] = json_encode($newImages);
        }

        $appointment->update($data);

        return redirect()->route('dokter.reservasi.index')
            ->with('success', 'Hasil pemeriksaan berhasil disimpan!');
    }
}
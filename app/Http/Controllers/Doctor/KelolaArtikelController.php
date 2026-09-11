<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class KelolaArtikelController extends Controller
{
    /**
     * Menampilkan daftar artikel milik dokter yang sedang login.
     */
    public function index(Request $request)
    {
        $query = Article::where('user_id', Auth::id())->latest();

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $articles = $query->paginate(10);

        return view('dokter.kelola-artikel.index', compact('articles'));
    }

    /**
     * Menampilkan form buat artikel baru.
     */
    public function create()
    {
        return view('dokter.kelola-artikel.create');
    }

    /**
     * Menyimpan artikel baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|string',
            'excerpt'  => 'nullable|string|max:500', // Validasi excerpt manual
            'content'  => 'required',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'   => 'required|in:draft,published',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id(); 
        
        // 1. Generate Slug unik dengan timestamp agar tidak bentrok
        $data['slug'] = Str::slug($request->title) . '-' . time();

        // 2. Logika Excerpt Otomatis/Manual
        $data['excerpt'] = $this->generateExcerpt($request);

        // 3. Handle Upload Gambar
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('artikel-images', 'public');
        }

        Article::create($data);

        return redirect()->route('dokter.kelola-artikel.index')->with('success', 'Artikel berhasil dibuat!');
    }

    /**
     * Menampilkan form edit artikel.
     */
    public function edit($id)
    {
        // Pastikan dokter hanya bisa edit artikel miliknya sendiri
        $article = Article::where('user_id', Auth::id())->findOrFail($id);
        
        return view('dokter.kelola-artikel.edit', compact('article'));
    }

    /**
     * Memperbarui data artikel di database.
     */
    public function update(Request $request, $id)
    {
        $article = Article::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|string',
            'excerpt'  => 'nullable|string|max:500',
            'content'  => 'required',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'   => 'required|in:draft,published',
        ]);

        $data = $request->all();

        // 1. Update Slug jika judul berubah
        if ($request->title != $article->title) {
            $data['slug'] = Str::slug($request->title) . '-' . time();
        }

        // 2. Update Excerpt Otomatis/Manual
        $data['excerpt'] = $this->generateExcerpt($request);

        // 3. Handle Update Gambar (Hapus yang lama jika ada upload baru)
        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $data['image'] = $request->file('image')->store('artikel-images', 'public');
        }

        $article->update($data);

        return redirect()->route('dokter.kelola-artikel.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    /**
     * Menghapus artikel secara permanen.
     */
    public function destroy($id)
    {
        $article = Article::where('user_id', Auth::id())->findOrFail($id);

        // Hapus file gambar dari storage
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return redirect()->route('dokter.kelola-artikel.index')->with('success', 'Artikel berhasil dihapus!');
    }

    /**
     * Mengubah status terbit secara cepat melalui AJAX/Form di index.
     */
    public function updateStatus(Request $request, $id)
    {
        $article = Article::where('user_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:draft,published'
        ]);

        $article->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status artikel berhasil diperbarui!');
    }

    /**
     * Helper: Menghasilkan excerpt otomatis atau manual.
     */
    private function generateExcerpt(Request $request)
    {
        if ($request->filled('excerpt')) {
            return Str::limit($request->excerpt, 150);
        }
        return Str::limit(strip_tags($request->content), 150);
    }
}
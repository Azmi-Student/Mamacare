<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    /**
     * Menampilkan daftar artikel untuk Publik (Bunda).
     */
    public function index()
    {
        $articles = Article::with('author')
                            ->where('status', 'published')
                            ->latest()
                            ->get();

        return view('mama.artikel.index', compact('articles'));
    }

    /**
     * Menampilkan detail artikel berdasarkan SLUG.
     */
    // UBAH DISINI: Parameternya jadi $slug
    public function show($slug)
    {
        // Cari artikel berdasarkan SLUG, bukan ID
        $article = Article::with('author')
                            ->where('status', 'published')
                            ->where('slug', $slug) // <-- Kuncinya disini
                            ->firstOrFail(); // Pakai firstOrFail(), bukan findOrFail()

        // Counter views
        $article->increment('views');

        // Artikel Terkait
        $related = Article::where('category', $article->category)
                            ->where('id', '!=', $article->id)
                            ->where('status', 'published')
                            ->latest()
                            ->take(3)
                            ->get();

        return view('mama.artikel.detail', compact('article', 'related'));
    }
}
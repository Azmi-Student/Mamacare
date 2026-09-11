<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CommunityPost;
use App\Models\CommunityLike;
use App\Models\CommunityComment;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function index()
    {
        $posts = CommunityPost::with(['user', 'likes', 'comments.user'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->get();
            
        $userId = Auth::id();
        foreach ($posts as $post) {
            $post->isLiked = $post->likes->contains('user_id', $userId);
        }

        return view('komunitas.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('community', 'public');
            $data['image'] = '/storage/' . $imagePath;
        }

        CommunityPost::create($data);

        return redirect()->back()->with('success', 'Postingan berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        $post = CommunityPost::findOrFail($id);
        
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
        ]);

        $post->title = $request->title;
        $post->content = $request->content;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('community', 'public');
            $post->image = '/storage/' . $imagePath;
        }

        $post->save();

        return back()->with('success', 'Postingan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $post = CommunityPost::findOrFail($id);
        
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $post->delete();

        return back()->with('success', 'Postingan berhasil dihapus.');
    }

    public function toggleLike($id)
    {
        $userId = Auth::id();
        $like = CommunityLike::where('community_post_id', $id)
            ->where('user_id', $userId)
            ->first();

        if ($like) {
            $like->delete();
        } else {
            CommunityLike::create([
                'community_post_id' => $id,
                'user_id' => $userId,
            ]);
        }

        return redirect()->back();
    }

    public function comment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        CommunityComment::create([
            'user_id' => Auth::id(),
            'community_post_id' => $id,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }
}

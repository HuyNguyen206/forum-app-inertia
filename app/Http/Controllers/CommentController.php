<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }

    public function store(Post $post, Request $request)
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2500'],
        ]);

        $post->comments()->create([
            'body' => $validated['body'],
            'user_id' => $request->user()->id
        ]);

        return to_route('posts.show', $post);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class);
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

        return redirect($post->getShowPostUrl($request->query()))->banner('Comment added');
    }

    public function destroy(Request $request, Comment $comment)
    {
        $comment->delete();

        return redirect($comment->post->getShowPostUrl($request->query()))->banner('Comment deleted');
    }

    public function update(Request $request, Comment $comment)
    {
        $data = $request->validate(['body' => 'required']);

        $comment->update($data);

        return redirect($comment->post->getShowPostUrl($request->query()))->banner('Comment updated');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Posts/Index', ['posts' => PostResource::collection(Post::with(['user', 'comments'])->latest()->paginate())]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Posts/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'body' => ['required','string','max:2500'],
        ]);

        $post = $request->user()->posts()->create($data);

        return to_route('posts.show', $post)->banner('Create post success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return inertia('Posts/Show', [
            'post' => fn() => PostResource::make($post->load( 'user')),
            'comments' => fn() => CommentResource::collection($post->comments()->with('user')->latest('id')->paginate(5))
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}

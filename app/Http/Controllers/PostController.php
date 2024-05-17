<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ?string $topicSlug = null)
    {
        $topic = Topic::where('slug', $topicSlug)->first();

        $posts = Post::query()->when($topic, function (Builder $query) use ($topic) {
//            $query->whereHas('topic', function (Builder $query) use ($topicSlug) {
//                $query->where('slug', $topicSlug);
//            });
            $query->whereBelongsTo($topic);
        })->with(['user', 'comments', 'topic'])->latest()->paginate();

        return inertia('Posts/Index', [
            'posts' => PostResource::collection($posts),
            'selectedTopic' => $topic ? TopicResource::make($topic) : null,
            'topics' => TopicResource::collection(Topic::all()),
            ]);
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
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:2500'],
        ]);

        $post = $request->user()->posts()->create($data);

        return redirect($post->getShowPostUrl($request->query()))->banner('Create post success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post, string $slug = null)
    {
        $newSlug = Str::slug($post->title);
        if ($slug && $slug !== $newSlug) {
            return redirect()->to(route('posts.show', [$post, $newSlug, ...$request->query()]));
        }

        return inertia('Posts/Show', [
            'post' => fn() => PostResource::make($post->load('user')),
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

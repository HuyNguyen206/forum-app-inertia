<?php

use function Pest\Laravel\get;

it('shouyld return the correct component', function () {
    get(route('posts.index'))
        ->assertComponent('Posts/Index');
//        ->assertInertia(fn(AssertableInertia $inertia) => $inertia->component('Posts/Index',true));
});

it('pass the post to the view', function () {

    $posts = \App\Models\Post::factory(3)->create();
    get(route('posts.index'))
        ->assertHasPaginatedResource('posts', \App\Http\Resources\PostResource::collection($posts->load('user', 'comments')));
});


it('can show the detail post', function () {
    $post = \App\Models\Post::factory()->create();
    get($post->getShowPostUrl())
        ->assertComponent('Posts/Show')
        ->assertHasResource('post', \App\Http\Resources\PostResource::make($post->load('user')));
});

it('show post with correct comments', function () {
    $post = \App\Models\Post::factory()->create();
    $comments = \App\Models\Comment::factory(3)->for($post)->create();
//    $post->comments()->saveMany(\App\Models\Comment::factory(3)->make());

//    get(route('posts.show', $post->id))->assertInertia(fn (AssertableInertia $page) => $page->has('post.comments', 3));
    get($post->getShowPostUrl())->assertHasPaginatedResource('comments', \App\Http\Resources\CommentResource::collection($comments->reverse()->load('user')));
});

it('require authenticate to store post', function () {
    \Pest\Laravel\postJson(route('posts.store'))->assertUnauthorized();
});

it('store post', function () {
    $user = \App\Models\User::factory()->create();
    $postModel = \App\Models\Post::factory()->make(['user_id'=> $user->id]);
//    dd($postModel->toArray());
    \Pest\Laravel\actingAs($user)
        ->postJson(route('posts.store'), $data = $postModel->toArray())
        ->assertRedirect($user->posts()->first()->getShowPostUrl());
    \Pest\Laravel\assertDatabaseHas(\App\Models\Post::class, $data);
});

it('require title, body', function () {
//    dd($postModel->toArray());
    \Pest\Laravel\actingAs(\App\Models\User::factory()->create())
        ->postJson(route('posts.store'))->assertJsonValidationErrors(['title', 'body']);
});

it('require authenticate to get create post view', function () {
    get(route('posts.create'))->assertRedirect(route('login'));
});

it('return correct component', function () {
    \Pest\Laravel\actingAs(\App\Models\User::factory()->create())
        ->get(route('posts.create'))->assertComponent('Posts/Create');
});

it('show title case post', function () {
    \Pest\Laravel\travel(-1)->weeks();
    $user = App\Models\User::factory()->create();
    \Pest\Laravel\travelBack();
    \Pest\Laravel\actingAs($user)
        ->postJson(route('posts.store', [
            'title' => 'Have a nice day',
            'body' => 'Body desc'
        ]));

    \Pest\Laravel\assertDatabaseHas(\App\Models\Post::class, [
       'title' => 'Have A Nice Day',
        'body' => 'Body desc'
    ]);
});

it('can generate show url', function () {
    $post = \App\Models\Post::factory()->create();

    \PHPUnit\Framework\assertEquals(route('posts.show', [$post, \Illuminate\Support\Str::slug($post->title), 'page' => 2]), $post->getShowPostUrl(['page' => 2]));
});

it('will redirect if slug is incorrect', function () {
    $post = \App\Models\Post::factory()->create(['title' => 'correct slug']);

    get(route('posts.show', [$post, 'incorrect-slug', 'page' => 2]))->assertRedirect($post->getShowPostUrl(['page' => 2]));
});

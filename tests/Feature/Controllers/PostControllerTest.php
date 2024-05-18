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
        ->assertHasPaginatedResource('posts', \App\Http\Resources\PostResource::collection($posts->load('user', 'comments', 'topic')));
});

it('pass the topic to the view', function () {
    $topics = \App\Models\Topic::factory(10)->create();

    get(route('posts.index'))
        ->assertHasResource('topics', \App\Http\Resources\TopicResource::collection($topics));
});

it('can filter posts belong to topic', function () {

    $sportPosts = \App\Models\Post::factory(3)->create(['topic_id' => $sportTopic = \App\Models\Topic::factory()->create(['name' => 'Sport'])]);
    $gamePosts = \App\Models\Post::factory(4)->create(['topic_id' => $gameTopic =\App\Models\Topic::factory()->create(['name' => 'Game'])]);
    $newPosts = \App\Models\Post::factory(5)->create(['topic_id' => $newTopic = \App\Models\Topic::factory()->create(['name' => 'New'])]);

    get(route('posts.index', $gameTopic->slug))
        ->assertHasPaginatedResource('posts', \App\Http\Resources\PostResource::collection($gamePosts->load('user', 'comments', 'topic')));
});

it('it passed selected topic to the view', function () {
  $topic = \App\Models\Topic::factory()->create();
    get(route('posts.index', $topic->slug))
        ->assertHasResource('selectedTopic', \App\Http\Resources\TopicResource::make($topic));
});

it('it passed topics to the view', function () {
    $topics = \App\Models\Topic::factory(5)->create();
    \Pest\Laravel\actingAs(\App\Models\User::factory()->create())->get(route('posts.create'))
        ->assertHasResource('topics', \App\Http\Resources\TopicResource::collection($topics));
});


it('can show the detail post', function () {
    \Pest\Laravel\withoutExceptionHandling();
    $post = \App\Models\Post::factory()->create();

    get($post->getShowPostUrl())
        ->assertComponent('Posts/Show')
        ->assertHasResource('post', \App\Http\Resources\PostResource::make($post->load(['user', 'topic'])));
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

it('generate the html from markdown when create post', function () {
    $post = \App\Models\Post::factory()->create(['body' => $body = '## Hello world']);

    expect($post->body_html)->toEqual(str($post->body)->markdown());
});

it('generate the html from markdown when update post', function () {
    $post = \App\Models\Post::factory()->create(['body' => $body = '## Hello world']);
    expect($post->body_html)->toEqual(str($post->body)->markdown());
    $post->update([
        'body' => '## Good bye world'
    ]);
    expect($post->body_html)->toEqual(str($post->body)->markdown());
});

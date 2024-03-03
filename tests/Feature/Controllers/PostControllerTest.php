<?php

use Inertia\Testing\AssertableInertia;
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
    \Pest\Laravel\withoutExceptionHandling();
    get(route('posts.show', $post->id))
        ->assertComponent('Posts/Show')
        ->assertHasResource('post', \App\Http\Resources\PostResource::make($post->load('comments', 'user')));
});

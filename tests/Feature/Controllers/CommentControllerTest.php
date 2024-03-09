<?php

it('can store comment', function () {
    $user =  \App\Models\User::factory()->create();
    $post = \App\Models\Post::factory()->recycle($user)->create();

    \Pest\Laravel\actingAs($user)->postJson(route('posts.comments.store', $post->id), [
        'body' => 'comment'
    ])->assertRedirect(route('posts.show', $post));

    \Pest\Laravel\assertDatabaseHas('comments', [
        'body' => 'comment',
        'user_id' => $user->id,
        'post_id' => $post->id
    ]);
});

it('can not store empty comment', function ($value) {
    $user =  \App\Models\User::factory()->create();
    $post = \App\Models\Post::factory()->recycle($user)->create();

    \Pest\Laravel\actingAs($user)->postJson(route('posts.comments.store', $post->id), [
        'body' => $value
    ])->assertJsonValidationErrorFor('body');
})->with([
    null,
    '',
    true,
    str_repeat('a', 2501)
]);

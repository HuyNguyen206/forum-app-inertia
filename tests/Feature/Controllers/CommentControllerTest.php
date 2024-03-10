<?php

use function Pest\Laravel\freezeTime;

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

it('guest user can not post comment', function () {
    \Pest\Laravel\postJson(route('posts.comments.store', \App\Models\Post::factory()->create()), [
        'body' => 'comment'
    ])->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
});

it('guest can not delete comment', function () {
    $comment = \App\Models\Comment::factory()->create();
 \Pest\Laravel\deleteJson(route('comments.destroy', $comment))
        ->assertStatus(401);

});

it('can delete comment', function () {
    $comment = \App\Models\Comment::factory()->create();
    \Pest\Laravel\actingAs($comment->user)->deleteJson(route('comments.destroy', $comment))
        ->assertRedirect(route('posts.show', $comment->post));

   \Pest\Laravel\assertModelMissing($comment);
    \PHPUnit\Framework\assertNotContains($comment, $comment->post->comments);
});

it('can not delete other\'s comment', function () {
    $comment = \App\Models\Comment::factory()->create();
    \Pest\Laravel\actingAs(\App\Models\User::factory()->create())->deleteJson(route('comments.destroy', $comment))
        ->assertForbidden();
});

it('can not delete comment over 1 hour', function () {
    freezeTime();
    $comment = \App\Models\Comment::factory()->create();
    $this->travel(61)->minutes();

    \Pest\Laravel\actingAs($comment->user)->deleteJson(route('comments.destroy', $comment))
        ->assertForbidden();
});

it('Redirect with correct page number', function () {
    $comments = \App\Models\Comment::factory(20)->for($post = \App\Models\Post::factory()->create())->create();
    $lastComment = $comments->first();

    \Pest\Laravel\actingAs($lastComment->user)->deleteJson(route('comments.destroy', [$lastComment, 'page' => 4]))
        ->assertRedirect(route('posts.show', [$lastComment->post, 'page' => 4]));

});


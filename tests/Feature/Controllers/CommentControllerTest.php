<?php

use function Pest\Laravel\freezeTime;

it('can store comment', function () {
    $user =  \App\Models\User::factory()->create();
    $post = \App\Models\Post::factory()->recycle($user)->create();

    \Pest\Laravel\actingAs($user)->postJson(route('posts.comments.store', $post->id), [
        'body' => 'comment'
    ])->assertRedirect($post->getShowPostUrl());

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
        ->assertRedirect($comment->post->getShowPostUrl());

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
        ->assertRedirect($lastComment->post->getShowPostUrl(['page' => 4]));

});

it('require authenticate to edit comment', function () {
    $comment = \App\Models\Comment::factory()->for($post = \App\Models\Post::factory()->create())->create();

    \Pest\Laravel\patchJson(route('comments.update', $comment))->assertUnauthorized();

});

it('can update comment', function () {
    $comment = \App\Models\Comment::factory()->for($post = \App\Models\Post::factory()->create())->create(['body' => 'old']);
    \Pest\Laravel\actingAs($comment->user)->patchJson(route('comments.update', [$comment, 'page' => 1]), ['body' => 'update'])
        ->assertRedirect($comment->post->getShowPostUrl(['page' => 1]));;

    $this->assertDatabaseHas(\App\Models\Comment::class, [
        'body' => 'update',
        'id' => $comment->id,
        'user_id' => $comment->user_id,
        'post_id' => $comment->post_id
    ]);
});

it('can not update other user\'s comment', function () {
    $comment = \App\Models\Comment::factory()->for($post = \App\Models\Post::factory()->create())->create(['body' => 'old']);
    \Pest\Laravel\actingAs(\App\Models\User::factory()->create())->patchJson(route('comments.update', [$comment, 'page' => 1]), ['body' => 'update'])->assertForbidden();

    $this->assertDatabaseHas(\App\Models\Comment::class, [
        'body' => 'old',
        'id' => $comment->id,
        'user_id' => $comment->user_id,
        'post_id' => $comment->post_id
    ]);
});

it('required body', function ($value) {
    $comment = \App\Models\Comment::factory()->for($post = \App\Models\Post::factory()->create())->create(['body' => 'old']);
    \Pest\Laravel\actingAs($comment->user)->patchJson(route('comments.update', [$comment, 'page' => 1]), ['body' => $value])->assertInvalid('body');

    \Pest\Laravel\assertModelExists($comment);
})->with([
    null, ''
]);

it('generate the html from markdown when create comment', function () {
    $comment = \App\Models\Comment::factory()->create(['body' => $body = '## Hello world']);

    expect($comment->body_html)->toEqual(str($comment->body)->markdown());
});

it('generate the html from markdown when update comment', function () {
    $comment = \App\Models\Comment::factory()->create(['body' => $body = '## Hello world']);
    expect($comment->body_html)->toEqual(str($comment->body)->markdown());
    $comment->update([
        'body' => '## Good bye world'
    ]);
    expect($comment->body_html)->toEqual(str($comment->body)->markdown());
});


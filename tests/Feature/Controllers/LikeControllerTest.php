<?php

test('it require authenticate', function () {
    $post = \App\Models\Post::factory()->create();
    $this->patch(route('likes.toggle', ['post', $post]))
        ->assertRedirect(route('login'));
});

it('allow like/dislike a likeable model (can be either post or comment)', function (\Illuminate\Database\Eloquent\Model $model) {
//    \Pest\Laravel\withoutExceptionHandling();
    $user = \App\Models\User::factory()->create();

    \Pest\Laravel\actingAs($user)->fromRoute('dashboard')->patch(route('likes.toggle', [$model->getMorphClass(), $model]))->assertRedirectToRoute('dashboard');

    \Pest\Laravel\assertDatabaseHas('likes', [
        'likeable_type' => $model->getMorphClass(),
        'likeable_id' => $model->id,
        'user_id' => $user->id,
    ]);
    expect($model->refresh()->likes_count)->toEqual(1);


    \Pest\Laravel\actingAs($user)->patch(route('likes.toggle', [$model->getMorphClass(), $model]))->assertRedirect();
//    \Pest\Laravel\actingAs($user)->patch(route('likes.toggle', [$model->getMorphClass(), $model]))->assertForbidden();
    \Pest\Laravel\assertDatabaseMissing('likes', [
        'likeable_type' => $model->getMorphClass(),
        'likeable_id' => $model->id,
        'user_id' => $user->id,
    ]);

    expect($model->refresh()->likes_count)->toEqual(0);

})->with([
    fn() => \App\Models\Post::factory()->create(),
    fn() => \App\Models\Comment::factory()->create(),
]);

it('only allow like post or comment', function () {
//        \Pest\Laravel\withoutExceptionHandling();
//
    $user = \App\Models\User::factory()->create();
    $likeUser = \App\Models\User::factory()->create();

    \Pest\Laravel\actingAs($user)->fromRoute('dashboard')->patch(route('likes.toggle', ['user', $likeUser]))->assertForbidden();
});

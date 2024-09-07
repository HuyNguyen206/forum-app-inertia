<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $huy = User::factory()->create([
            'name' => 'huy',
            'email' => 'nguyenlehuyuit@gmail.com',
        ]);

        $users = User::factory(10)->create();

        $this->call(TopicSeeder::class);

//        User::all()->each(function ($user) {
//            Like::factory(10)->create(['user_id' => $user->id]);
//            Like::factory(10)->create(['user_id' => $user->id, 'likeable_id' => Comment::factory()]);
//        });

        $posts = Post::factory(100)->withFixture()->recycle($allUsers = $users->push($huy))->create();

        Comment::factory(600)->recycle($posts)->recycle($allUsers)->create();
        Like::factory(500)->recycle($allUsers)->recycle($posts)->create(['likeable_id' => Comment::factory()]);
        Like::factory(500)->recycle($allUsers)->create();

        Post::query()->chunk(100, function ($posts) {
            $posts->each(function ($post) {
                $post->increment('likes_count', $post->refresh()->likes()->count());
            });
        });

        Comment::query()->chunk(100, function ($comments) {
            $comments->each(function ($comment) {
                $comment->increment('likes_count', $comment->refresh()->likes()->count());
            });
        });
    }
}

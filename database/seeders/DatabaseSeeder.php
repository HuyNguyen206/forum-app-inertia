<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
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
        $posts = Post::factory(100)->withFixture()->recycle($users->push($huy))->create();
        Comment::factory(600)->recycle($posts)->recycle($users)->create();
    }
}

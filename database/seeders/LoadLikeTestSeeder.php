<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\LazyCollection;
use function Laravel\Prompts\progress;

class LoadLikeTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $post = Post::first();

        Like::query()->whereHasMorph('likeable', Post::class,  function (Builder $query) use ($post) {
            $query->where('id', $post->id);
        })->delete();

       $progress = progress('Creating likes', 500_000);
        LazyCollection::times(5000)->each(function () use ($post, $progress) {
            Like::factory(100)->for($post, 'likeable')->create();
            $progress->advance(100);
        });
       $progress->start();

       $post->increase(500_000);

    }
}

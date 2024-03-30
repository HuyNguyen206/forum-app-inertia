<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    private static ?Collection $cachePosts = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => str($this->faker->sentence)->beforeLast('.')->title(),
            'body' => Collection::times(4, fn() => $this->faker->realText(600))->join(PHP_EOL . PHP_EOL)
        ];
    }

    public function withFixture()
    {
        if (!$this::$cachePosts) {
            $posts = collect(File::files(database_path('factories/fixtures/posts')))
                ->map(fn(SplFileInfo $file) => $file->getContents())
            ->map(fn(string $contents) => str($contents)->explode("\n",2))
            ->map(fn (Collection $parts) => [
                'title' => str($parts[0])->trim()->after('# '),
                'body' => str($parts[1])->trim()
            ]);
            $this::$cachePosts = clone $posts;
        } else {
            $posts = $this::$cachePosts;
        }

        return $this->sequence(...$posts);
    }
}

<?php

namespace Database\Factories;

use App\Models\Topic;
use App\Models\User;
use App\Support\PostFixture;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $topicIds = Topic::all('id')->pluck('id')->toArray();
        return [
            'user_id' => User::factory(),
            'topic_id' => function () use ($topicIds) {
                return count($topicIds) ? collect($topicIds)->random() : Topic::factory()->create()->id;
            },
            'title' => str($this->faker->sentence)->beforeLast('.')->title(),
            'body' => Collection::times(4, fn() => $this->faker->realText(600))->join(PHP_EOL . PHP_EOL),
            'likes_count' => 0
        ];
    }

    public function withFixture()
    {
        return $this->sequence(...PostFixture::getFixture());
    }
}

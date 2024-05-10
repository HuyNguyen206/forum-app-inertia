<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            [
                'name' => 'Fashion',
                'description' => 'About fashion'
            ],
            [
                'name' => 'Sport',
                'description' => 'About Sport'
            ],
            [
                'name' => 'Game',
                'description' => 'About Game'
            ],
            [
                'name' => 'New',
                'description' => 'About New'
            ],
            [
                'name' => 'Device',
                'description' => 'About Device'
            ],
            [
                'name' => 'PC',
                'description' => 'About PC'
            ],
            [
                'name' => 'Laptop',
                'description' => 'About Laptop'
            ],
            [
                'name' => 'Hobby',
                'description' => 'About Hobby'
            ], [
                'name' => 'Travel',
                'description' => 'About Travel'
            ], [
                'name' => 'Places',
                'description' => 'About Places'
            ], [
                'name' => 'IT',
                'description' => 'About IT'
            ], [
                'name' => 'School',
                'description' => 'About School'
            ],
        ])->map(function (array $topic) {
            Topic::factory()->create([
                'name' => $topic['name'],
                'slug' => Str::slug($topic['name']),
                'description' => $topic['description'],
            ]);
        });


    }
}

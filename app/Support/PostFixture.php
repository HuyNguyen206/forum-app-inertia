<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

class PostFixture
{
    private static ?Collection $cachePosts = null;

    public static function getFixture()
    {
        if (!static::$cachePosts) {
            $posts = collect(File::files(database_path('factories/fixtures/posts')))
                ->map(fn(SplFileInfo $file) => $file->getContents())
                ->map(fn(string $contents) => str($contents)->explode("\n",2))
                ->map(fn (Collection $parts) => [
                    'title' => str($parts[0])->trim()->after('# '),
                    'body' => str($parts[1])->trim()
                ]);
            static::$cachePosts = clone $posts;
        } else {
            $posts = static::$cachePosts;
        }

        return $posts;
    }
}

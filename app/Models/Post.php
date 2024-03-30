<?php

namespace App\Models;

use App\Models\Concerns\ConvertMarkdownToHtml;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, ConvertMarkdownToHtml;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function title(): Attribute
    {
        return Attribute::make(
            set: fn($value) => Str::title($value)
        );
    }

    public function getShowPostUrl(array $parameters = [], bool $withRedirect = true, string $message = null)
    {
        return route('posts.show', [$this, Str::slug($this->title), ...$parameters]);
    }

}

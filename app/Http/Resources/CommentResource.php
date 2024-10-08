<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class CommentResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request) +
        [
            'body' => $this->body,
            'body_html' => $this->body_html,
            'can' => [
                'delete' => $request->user()?->can('delete', $this->resource),
                'update' => $request->user()?->can('update', $this->resource)
            ],
            'user' => new UserResource($this->whenLoaded('user')),
            'post' => new PostResource($this->whenLoaded('post')),
            'likes_count' => $this->likes_count,
            'likes_count_label' => Number::abbreviate($this->likes_count) . ' ' . Str::plural('like', $this->likes_count),
            'is_like' => auth()->user()?->likeModel($this->resource) ?? false,
        ];
    }
}

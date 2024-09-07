<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class PostResource extends BaseResource
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
            'title' => $this->title,
            'body' => $this->body,
            'body_html' => $this->body_html,
            'likes_count' => $this->likes_count,
            'likes_count_label' => Number::abbreviate($this->likes_count) . ' ' . Str::plural('like', $this->likes_count),
            'user' => new UserResource($this->whenLoaded('user')),
            'topic' => new TopicResource($this->whenLoaded('topic')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'show_post_url' => $this->getShowPostUrl($request->query()),
            'show_post_url_without_query_string' => $this->getShowPostUrl(),
        ];
    }
}

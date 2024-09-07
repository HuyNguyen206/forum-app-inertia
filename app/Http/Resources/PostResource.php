<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

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
            'user' => new UserResource($this->whenLoaded('user')),
            'topic' => new TopicResource($this->whenLoaded('topic')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'show_post_url' => $this->getShowPostUrl($request->query()),
            'show_post_url_without_query_string' => $this->getShowPostUrl(),
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    private bool $withLikePermission = false;

    public function withLikePermission(): self
    {
        $this->withLikePermission = true;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user', fn() => UserResource::make($this->user)),
            'post' => $this->whenLoaded('user', fn() => PostResource::make($this->post)),
            'body' => $this->body,
            'html' => $this->html,
            'likes_count' => $this->likes_count,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'can' => [
                'update' => $request->user()?->can('update', $this->resource),
                'delete' => $request->user()?->can('delete', $this->resource),
                'like' => $this->when(
                    $this->withLikePermission,
                    fn() => $request->user()?->can('create', [Like::class, $this->resource])
                )
            ]
        ];
    }
}

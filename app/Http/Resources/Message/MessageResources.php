<?php

namespace App\Http\Resources\Message;

use App\Http\Resources\User\UserResources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'chat_id' => $this->chat_id,
            'user_name' => UserResources::make($this->user),
            'body' => $this->body,
            'is_owner' => $this->is_owner,
            'createdAt' => $this->created_at->diffForHumans(),
        ];
    }
}

<?php

namespace App\Http\Resources\Chat;

use App\Http\Resources\Message\MessageResources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResources extends JsonResource
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
            'title' => $this->title,
            'users' => $this->users,
            'last_message' => MessageResources::make($this->lastMessage),
            'readableMessageCount' => $this->un_readable_message_status_auth_user_count
        ];
    }
}

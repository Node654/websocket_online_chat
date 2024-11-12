<?php

namespace App\Services\Message;

use App\Events\Message\NotificationStatusEvent;
use App\Events\Message\StoreEvent;
use App\Jobs\Message\NotificationStatusJob;
use App\Models\Chat;
use App\Models\Message;
use App\Models\MessageStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Mockery\Exception;

class MessageService
{
    public function store(array $data): Message|JsonResponse
    {
        $chat = Chat::find($data['chat_id']);
        $users = $chat->users()->whereNot('user_id', auth()->id())->get();

        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => auth()->id(),
            'body' => $data['body'],
        ]);

        NotificationStatusJob::dispatch($users, $chat->id, $message);

        broadcast(new StoreEvent($message))->toOthers();

        return $message;
    }

    public function update(array $data)
    {
        MessageStatus::query()
            ->where(['user_id' => $data['user_id'], 'message_id' => $data['message_id'], 'is_read' => false])
            ->update(['is_read' => true]);
        return redirect()->route('chats.show', $data['chat_id'], 303);
    }
}

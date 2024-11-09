<?php

namespace App\Services\Message;

use App\Events\Message\NotificationStatusEvent;
use App\Events\Message\StoreEvent;
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

        try {
            DB::beginTransaction();

            $message = Message::create([
                'chat_id' => $chat->id,
                'user_id'=> auth()->id(),
                'body' => $data['body'],
            ]);

            foreach ($users as $user)
            {
                DB::table('message_status')->insert([
                    'chat_id' => $chat->id,
                    'user_id' => $user->id,
                    'message_id' => $message->id,
                ]);

                $count = MessageStatus::query()
                    ->where('user_id', $user->id)
                    ->where('is_read', false)
                    ->count();

                broadcast(new NotificationStatusEvent($count, $user, $chat->id))->toOthers();
            }

            broadcast(new StoreEvent($message))->toOthers();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ], 400);
        }
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

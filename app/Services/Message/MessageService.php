<?php

namespace App\Services\Message;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
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
            }

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
}

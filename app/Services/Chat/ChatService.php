<?php

namespace App\Services\Chat;

use App\Http\Resources\Chat\ChatResources;
use App\Models\Chat;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class ChatService
{
    public function index(): AnonymousResourceCollection
    {
        return ChatResources::collection(auth()->user()->chats()->withCount('unReadableMessageStatusAuthUser')->has('messages')->with(['lastMessage', 'chatWith'])->get());
    }

    public function store(array $data): Chat
    {
        $userIds = array_merge($data['users'], [auth()->id()]);
        sort($userIds);
        $userIdsStr = implode('-', $userIds);
        try {
            DB::beginTransaction();

            $chat = Chat::query()->createOrFirst([
                'users' => $userIdsStr,
            ], [
                'title' => $data['title'],
            ]);

            $chat->users()->sync($userIds);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return $chat;
    }
}

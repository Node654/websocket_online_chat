<?php

namespace App\Facade;

use App\Services\Chat\ChatService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Models\Chat store(array $data)
 * @method static AnonymousResourceCollection index()
 *
 * @see ChatService
 */
class Chat extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'chats_service';
    }
}

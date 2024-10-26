<?php

namespace App\Facade;

use App\Services\Message\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Models\Message|JsonResponse store(array $data)
 *
 * @see MessageService
 */
class Message extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'messages_service';
    }
}

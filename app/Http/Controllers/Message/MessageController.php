<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\Message\MessageResources;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    public function store(StoreRequest $request): MessageResources|JsonResponse
    {
        $messageOrJsonResponse = $request->store();
        if ($messageOrJsonResponse instanceof JsonResponse) {
            return $messageOrJsonResponse;
        }
        return MessageResources::make($messageOrJsonResponse);
    }
}

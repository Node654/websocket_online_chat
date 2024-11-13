<?php

namespace App\Http\Controllers\Message;

use App\Facade\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Requests\Message\UpdateRequest;
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

    public function update(UpdateRequest $request)
    {
        return Message::update($request->validated());
    }
}

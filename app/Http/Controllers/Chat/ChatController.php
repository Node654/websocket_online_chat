<?php

namespace App\Http\Controllers\Chat;

use App\Facade\Chat as ChatFacade;
use App\Facade\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\StoreRequest;
use App\Http\Resources\Chat\ChatResources;
use App\Http\Resources\User\UserResources;
use App\Models\Chat;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ChatController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Chat/Index', ['users' => User::index(), 'chats' => ChatFacade::index()]);
    }

    public function show(Chat $chat): Response
    {
        return Inertia::render('Chat/Show', ['chat' => new ChatResources($chat), 'users' => UserResources::collection($chat->users()->get())]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $chat = $request->store();
        return redirect()->route('chats.show', [$chat->id]);
    }
}

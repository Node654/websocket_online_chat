<?php

namespace App\Http\Controllers\Chat;

use App\Facade\Chat as ChatFacade;
use App\Facade\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\StoreRequest;
use App\Http\Resources\Chat\ChatResources;
use App\Http\Resources\Message\MessageResources;
use App\Http\Resources\User\UserResources;
use App\Models\Chat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Inertia\Inertia;
use Inertia\Response;

class ChatController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Chat/Index', ['users' => User::index(), 'chats' => ChatFacade::index()]);
    }

    public function show(Chat $chat): Response|JsonResource|JsonResponse
    {
        $chat->unReadableMessageStatusAuthUser()->update([
            'is_read' => true,
        ]);

        $page = request()->get('page') ?? 1;
        $paginateMessages = MessageResources::collection($chat->messages()->with('user')->orderByDesc('created_at')->paginate(5, '*', 'page', (int)$page));
        $isLastPage = (int)$page === (int)$paginateMessages->lastPage();

        if ($page > 1) {
            return response()->json([
                'messages' => $paginateMessages,
                'isLastPage' => $isLastPage
            ]);
        }

        return Inertia::render('Chat/Show', [
            'chat' => new ChatResources($chat),
            'users' => UserResources::collection($chat->users()->get()),
            'messages' => $paginateMessages,
            'isLastPage' => $isLastPage
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $chat = $request->store();

        return redirect()->route('chats.show', [$chat->id]);
    }
}

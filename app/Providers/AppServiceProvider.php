<?php

namespace App\Providers;

use App\Http\Resources\Chat\ChatResources;
use App\Http\Resources\User\UserResources;
use App\Services\Chat\ChatService;
use App\Services\Message\MessageService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('user_service', UserService::class);
        $this->app->bind('chats_service', ChatService::class);
        $this->app->bind('messages_service', MessageService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        UserResources::withoutWrapping();
        ChatResources::withoutWrapping();
        Vite::prefetch(concurrency: 3);
    }
}

<?php

namespace App\Services\User;

use App\Http\Resources\User\UserResources;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserService
{
    public function index(): JsonResource
    {
        return UserResources::collection(User::query()->whereNot('id', auth()->id())->get());
    }
}

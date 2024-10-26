<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Http\Resources\Json\JsonResource index()
 *
 * @see \App\Services\User\UserService
 */
class User extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'user_service';
    }
}

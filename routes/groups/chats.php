<?php

use App\Http\Controllers\Chat\ChatController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->controller(ChatController::class)->prefix('/chats')->group(function () {
    Route::get('/', 'index')->name('chats.index');
    Route::post('/', 'store')->name('chats.store');
    Route::get('/{chat}', 'show')->name('chats.show');
});

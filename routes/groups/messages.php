<?php

use App\Http\Controllers\Message\MessageController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->controller(MessageController::class)->prefix('/messages')->group(function () {
    Route::post('', 'store')->name('messages.store');
    Route::patch('/update', 'update')->name('messages.update');
});

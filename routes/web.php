<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/auth/google', [App\Http\Controllers\UserController::class, 'authGoogle']);
Route::get('/auth/callback/google', [App\Http\Controllers\UserController::class, 'authCallback']);


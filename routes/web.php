<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

Route::get('/', function () {
    if (Auth::check()) {
        return view('home');
    } else {
        return view('login');
    }
    
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/teste', function() {
    return view('teste');
});






Route::get('/task/list', [App\Http\Controllers\TaskController::class, 'getTasks'])->name('gettasks');
Route::get('/task/id', [App\Http\Controllers\TaskController::class, 'getById'])->name('getbyid');
Route::post('/task/store', [App\Http\Controllers\TaskController::class, 'store'])->name('store');
Route::get('/task/destroy', [App\Http\Controllers\TaskController::class, 'destroy'])->name('destroy');
Route::post('/task/update', [App\Http\Controllers\TaskController::class, 'update'])->name('update');
Route::get('/task/teste', [App\Http\Controllers\TaskController::class, 'teste'])->name('teste');

Route::get('/auth/google', [App\Http\Controllers\UserController::class, 'authGoogle'])->name('google.login');
Route::get('/auth/callback/google', [App\Http\Controllers\UserController::class, 'authCallback'])->name('authCallback');
Route::post('/auth/login', [App\Http\Controllers\UserController::class, 'login'])->name('login');
Route::get('/auth/logout', [App\Http\Controllers\UserController::class, 'logout'])->name('logout');

Route::get('/gcalendar/listevents', [App\Http\Controllers\GoogleCalendarController::class, 'listEvents'])->name('listEvents');
Route::post('/gcalendar/createevent', [App\Http\Controllers\GoogleCalendarController::class, 'createEvent'])->name('createEvent');
Route::post('/gcalendar/updateevent', [App\Http\Controllers\GoogleCalendarController::class, 'updateEvent'])->name('updateEvent');
Route::post('/gcalendar/deleteevent', [App\Http\Controllers\GoogleCalendarController::class, 'deleteEvent'])->name('deleteEvent');
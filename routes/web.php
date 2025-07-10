<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
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

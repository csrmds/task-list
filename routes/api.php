<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/task/list', [App\Http\Controllers\TaskController::class, 'getTasks'])->middleware('auth:sanctum');
Route::post('/task/store', [App\Http\Controllers\TaskController::class, 'store'])->middleware('auth:sanctum');
Route::post('/task/destroy', [App\Http\Controllers\TaskController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('/task/update', [App\Http\Controllers\TaskController::class, 'update'])->middleware('auth:sanctum');

Route::post('/auth/login', [App\Http\Controllers\UserController::class, 'login']);
Route::get('/auth/logout', [App\Http\Controllers\UserController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/auth/check', [App\Http\Controllers\UserController::class, 'authCheck'])->middleware('auth:sanctum');

Route::post('/gcalendar/createevent', [App\Http\Controllers\GoogleCalendarController::class, 'createEvent'])->middleware('auth:sanctum');
Route::post('/gcalendar/updateevent', [App\Http\Controllers\GoogleCalendarController::class, 'updateEvent'])->middleware('auth:sanctum');
Route::post('/gcalendar/deleteevent', [App\Http\Controllers\GoogleCalendarController::class, 'deleteEvent'])->middleware('auth:sanctum');


Route::get('/xb', function () {
	try {
		\DB::connection()->getPdo();

		$query= User::all();

		
	return response()->json($query);
	} catch (\Exception $e) {
		return 'Erro na conexão: ' . $e->getMessage();
	}
})->middleware('auth:sanctum');

Route::get('/xa', function () {
	try {
		$dbConnection= env('DB_CONNECTION');
		$dbHost= env('DB_USERNAME');
		$dbUrl= env('DB_URL');
		$dbUser= env('DB_USERNAME');
		$dbDatabase= env('DB_DATABASE');
		$dbPassword= env('DB_PASSWORD');

		//$query= User::all();

		
	return response()->json([
		'Connection'=> $dbConnection,
		'host'=> $dbHost,
		'Url'=> $dbUrl,
		'User'=> $dbUser,
		'Database'=> $dbDatabase,
		'pwd'=> $dbPassword,
	]);
	} catch (\Exception $e) {
		return 'Erro na conexão: ' . $e->getMessage();
	}
})->middleware('auth:sanctum');
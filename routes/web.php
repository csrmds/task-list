<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/auth/google', [App\Http\Controllers\UserController::class, 'authGoogle']);
Route::get('/auth/callback/google', [App\Http\Controllers\UserController::class, 'authCallback']);

Route::get('/xb', function () {
	try {
		\DB::connection()->getPdo();

		$query= User::all();

		
	return response()->json($query);
	} catch (\Exception $e) {
		return 'Erro na conexão: ' . $e->getMessage();
	}
});

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
});
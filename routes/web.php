<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/auth/google', [App\Http\Controllers\UserController::class, 'authGoogle']);
Route::get('/auth/callback/google', [App\Http\Controllers\UserController::class, 'authCallback']);

Route::get('/xc', function () {
	try {
		return response()->json(teste: "qq informação de retorno");
	} catch (\Exception $e) {
		return 'Erro na conexão: ' . $e->getMessage();
	}
});
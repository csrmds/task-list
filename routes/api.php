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


Route::get('/xa', function () {
	try {
		$dbaConnection= env('DBA_CONNECTION');
		$dbaHost= env('DBA_HOST');
		$dbaUrl= env('DBA_URL');
		$dbaUser= env('DBA_USERNAME');
		$dbaDatabase= env('DBA_DATABASE');
		$dbaPassword= env('DBA_PASSWORD');
		
		$dbConnection= env('DB_CONNECTION');
		$dbHost= env('DB_HOST');
		$dbUrl= env('DB_URL');
		$dbUser= env('DB_USERNAME');
		$dbDatabase= env('DB_DATABASE');
		$dbPassword= env('DB_PASSWORD');
		
	return response()->json([
		'tasklistdb'=> [
			'Connection'=> $dbaConnection,
			'host'=> $dbaHost,
			'Url'=> $dbaUrl,
			'User'=> $dbaUser,
			'Database'=> $dbaDatabase,
		],
		'.env'=> [
			'Connection'=> $dbConnection,
			'host'=> $dbHost,
			'Url'=> $dbUrl,
			'User'=> $dbUser,
			'Database'=> $dbDatabase,
		]
	]);
	} catch (\Exception $e) {
		return 'Erro na conexão: ' . $e->getMessage();
	}
});

Route::get('/xb', function () {
	try {
		\DB::connection()->getPdo();

		$config= DB::connection()->getConfig();
		//$query= User::all();


		
	    return response()->json($config);
	} catch (\Exception $e) {
		return 'Erro na conexão: ' . $e->getMessage();
	}
});

Route::get('/xc', function() {
	try {
        // Listar arquivos em /etc/secrets
        $secretsPath = '/etc/secrets';
        $secretsFiles = [];
        if (is_dir($secretsPath)) {
            $secretsFiles = array_values(array_filter(scandir($secretsPath), function($file) use ($secretsPath) {
                return is_file($secretsPath . '/' . $file);
            }));
        }

        // Verificar arquivos .env na raiz do projeto
        $projectRoot = base_path();
        $envFiles = [];
        foreach (glob($projectRoot . '/.env*') as $envFile) {
            $envFiles[] = basename($envFile);
        }

        return response()->json([
            'secrets_files' => $secretsFiles,
            'env_files' => $envFiles,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
   }
});

Route::get('/manual-db-test', function () {
    try {
        $host = env('DB_HOST');
        $port = env('DB_PORT');
        $dbname = env('DB_DATABASE');
        $user = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        // Teste simples: listar tabelas
        $stmt = $pdo->query("SELECT * FROM tasks");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return response()->json([
            'success' => true,
            'tables' => $tables
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});
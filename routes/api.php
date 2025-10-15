    <?php

use App\Http\Controllers\Api\Auth\TaskController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Session\Middleware\StartSession;

Route::group(['prefix' => 'v1', 'as' => 'api.'], function () {
    Route::prefix('/auth')->middleware(['throttle:limiter', StartSession::class])->controller(AuthController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::post('/refresh', 'refresh')->middleware('auth.token-only');
    });

    Route::middleware(['AuthMiddleware'])->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout',  [AuthController::class, 'logout']);

        // Task routes
        Route::apiResource('tasks', TaskController::class)->middleware('task.permission');
        Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update.status')->middleware('task.permission');
    });
});
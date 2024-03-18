<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['json.response'])->prefix('v1')->group(function(){
    // UNAUTHENTICATED REQUESTS
    Route::middleware(['guest'])->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        
        
    });

    // AUTHENTICATED REQUESTS
    Route::middleware(['auth:sanctum'])->group(function(){
        // USER
        Route::apiResource('/user', UsersController::class);

        // USER SOFT DELETE
        Route::get('soft-deleted-users', [UsersController::class, 'soft_deleted_users']);
        Route::get('restore-soft-deleted-user/{user_id}', [UsersController::class, 'restore_soft_deleted_user']);
        Route::delete('delete-soft-deleted-user/{user_id}', [UsersController::class, 'permanent_delete_soft_deleted_user']);

        // USER ADDRESS
        Route::get('user-address/{user}', [UsersController::class, 'user_address']);
        Route::post('user-address', [UsersController::class, 'store_user_address']);
        Route::patch('user-address/{user_address}', [UsersController::class, 'update_user_address']);
        Route::delete('user-address/{user_address}', [UsersController::class, 'destroy_user_address']);
    });
});
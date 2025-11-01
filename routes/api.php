<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('profile')->group(function () {
        Route::post('', [ProfileController::class, 'store']);
        Route::get('/{user_id}', [ProfileController::class, 'show']);
        Route::put('/{user_id}', [ProfileController::class, 'update']);
    });
    // Route::get('user/{id}/profile', [UserController::class, 'getProfile']);
    // Route::get('user/{id}/tasks', [UserController::class, 'getUserTasks']);


    Route::apiResource('tasks', TaskController::class);
    Route::get('task/all', [TaskController::class, 'getAllTasks'])->middleware('CheckUser');
    // Route::get('task/{id}/user', [TaskController::class, 'getTaskUser']);
    Route::get('task/ordered', [TaskController::class, 'getTasksByPriority']);
    Route::get('task/{taskId}/categories', [TaskController::class, 'getTaskCategories']);
    Route::post('tasks/{taskId}/categories', [TaskController::class, 'addCategoriesToTask']);

    Route::get('task/favorites', [TaskController::class, 'getFavoriteTasks']);
    Route::post('task/{id}/favorite', [TaskController::class, 'addToFavorites']);
    Route::delete('task/{id}/favorite', [TaskController::class, 'removeFromFavorites']);

    Route::get('category/{categoryId}/tasks', [CategoryController::class, 'getCategoryTasks']);
});

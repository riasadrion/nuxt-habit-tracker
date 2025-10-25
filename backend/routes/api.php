<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PetActionController;
use App\Http\Controllers\Api\PetController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/pets', [PetController::class, 'index']);
    Route::post('/pets', [PetController::class, 'store'])->middleware('throttle:pet-create');
    Route::get('/pets/{pet}', [PetController::class, 'show']);
    Route::patch('/pets/{pet}', [PetController::class, 'update']);
    Route::delete('/pets/{pet}', [PetController::class, 'destroy']);

    Route::get('/pets/{pet}/actions', [PetActionController::class, 'index']);
    Route::post('/pets/{pet}/feed', [PetActionController::class, 'feed'])->middleware('throttle:pet-actions');
    Route::post('/pets/{pet}/clean', [PetActionController::class, 'clean'])->middleware('throttle:pet-actions');
    Route::post('/pets/{pet}/play', [PetActionController::class, 'play'])->middleware('throttle:pet-actions');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\UserAccountController;

Route::get('/hello', [IndexController::class, 'show'])
  ->middleware('auth');

Route::get('/listing', [ListingController::class, 'index']);
Route::get('/listing/{listing}', [ListingController::class, 'show']);
Route::post('/login', [AuthController::class, 'store'])
    ->name('login.store');

Route::post('/register', [UserAccountController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
  Route::post('/listing/create', [ListingController::class, 'store']);
  Route::get('/listing/{id}/edit', [ListingController::class, 'edit']);
  Route::put('/listing/{id}', [ListingController::class, 'update']);
  Route::delete('/listing/{id}', [ListingController::class, 'destroy']);

  Route::get('/user', [AuthController::class, 'getUser']);
  Route::delete('/logout', [AuthController::class, 'destroy'])
    ->name('logout');
});

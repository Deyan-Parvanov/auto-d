<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarDealerController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\UserAccountController;

Route::get('/hello', [IndexController::class, 'show'])
  ->middleware('auth');

Route::get('/listing', [ListingController::class, 'index']);
Route::get('/listing/{listing}', [ListingController::class, 'show']);
Route::get('/available-makes', [ListingController::class, 'getAvailableMakes']);
Route::get('/available-engines', [ListingController::class, 'getAvailableEngines']);

Route::post('/login', [AuthController::class, 'store'])
    ->name('login.store');
Route::post('/register', [UserAccountController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/user', [AuthController::class, 'getUser']);
  Route::delete('/logout', [AuthController::class, 'destroy'])
    ->name('logout');

  Route::prefix('car-dealer')
    ->name('car-dealer.')
    ->middleware('auth')
    ->group(function () {
      Route::get('/listing', [CarDealerController::class, 'index']);
      Route::delete('/listing/{id}', [CarDealerController::class, 'destroy']);
      Route::post('/listing/create', [CarDealerController::class, 'store']);
      Route::get('/listing/{id}/edit', [CarDealerController::class, 'edit']);
      Route::put('/listing/{id}', [CarDealerController::class, 'update']);
  });
});

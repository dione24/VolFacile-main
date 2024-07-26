<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
Route::get('/check-username/{username}', [AuthController::class, 'checkUsernameAvailability'])->middleware('guest');
Route::get('/check-email/{email}', [AuthController::class, 'checkEmailAvailability'])->middleware('guest');
Route::get('/check-phone-number/{phone_number}', [AuthController::class, 'checkPhoneNumberAvailability'])->middleware('guest');
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BLAController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class,'loginPage']);
Route::post('/check-user', [AuthController::class, 'checkUser']);
Route::post('/verify-otp', [AuthController::class, 'verifyOTP']);

Route::post('/verify-password', [AuthController::class, 'verifyPassword']);
Route::get('/voter-list', [BLAController::class,'voterlist'])->name('voterlist');
Route::get('/voters', [BLAController::class,'getVoters']);
Route::get('/logout', [AuthController::class,'logout'])->name('logout');

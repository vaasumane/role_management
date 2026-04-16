<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BLAController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'loginPage']);
Route::post('/check-user', [AuthController::class, 'checkUser']);
Route::post('/verify-otp', [AuthController::class, 'verifyOTP']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/verify-password', [AuthController::class, 'verifyPassword']);


Route::middleware([\App\Http\Middleware\CheckLogin::class])->group(function () {

    Route::get('/voter-list', [BLAController::class, 'voterlist'])->name('voterlist');
    Route::get('/voters', [BLAController::class, 'getVoters']);
    Route::get('/get-voter', [BLAController::class, 'getVoterDetails'])->name('voterdetails');
    Route::post('/update-voter', [BLAController::class, 'UpdateVoter'])->name('voter.update');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

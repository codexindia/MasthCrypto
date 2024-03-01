<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->controller('AuthManagement')->group(function () {
    Route::post('/login','login_OTP');
    Route::post('/login','login_attempt');
    Route::post('/SignUp','SignUP');
    Route::post('/SignUp/SendOTP','SignUP_OTP');
});

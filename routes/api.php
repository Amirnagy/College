<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ResetController;
use App\Http\Controllers\Api\User\UserController;

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
Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::post('varifyotp',[AuthController::class,'varifyUser']);
Route::delete('logout',[AuthController::class,'logout']);
Route::delete('delete/accouunt',[AuthController::class,'deleteAccount']);
Route::post('reset/sendmail',[ResetController::class,'checkRest']);
Route::post('reset/varifyotp',[ResetController::class,'checkresetOTP']);
Route::post('reset/password',[ResetController::class,'resetPassword']);

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('updateuser',[UserController::class,'update']);
});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Rest\NewsController;
use App\Http\Controllers\Rest\UserController;
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

Route::post('register', [AuthController::class, 'register'])->name('create_user');
Route::post('login', [AuthController::class, 'login'])->name('user_login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::patch('/user/update', [UserController::class, 'update'])->name('update_user_info');
    Route::post('logout', [AuthController::class, 'logout'])->name('user_logout');
    Route::get('news', [NewsController::class, 'index'])->name('all_news');
});

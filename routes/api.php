<?php

use App\Filament\Pages\News;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Rest\UserController;
use App\Http\Controllers\Rest\NewsController;

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

Route::post('/user/create', [UserController::class, 'register'])->name('create_user');
Route::post('login', [UserController::class, 'login'])->name('user_login');

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/user/update', [UserController::class, 'update'])->name('update_user_info');
    Route::post('/user/change-password', [UserController::class, 'changePassword'])->name('change_password');
    Route::post('logout', [UserController::class, 'logout'])->name('user_logout');

    Route::post('admin/news/sync', [News::class, 'sync'])->name('filament.pages.news.sync');

    Route::get('news', [NewsController::class, 'index'])->name('all_news');
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth'])->get('/user', function (Request $request) {
    return $request->user();
});

///get all posts regardless of authentication
Route::get('posts', [PostController::class, 'index']);

/// commit crud while suthenticated
Route::middleware(['auth'])->post('/create-posts', [PostController::class, 'store']);
Route::middleware(['auth'])->post('update-posts/{id}', [PostController::class, 'update']);
Route::middleware(['auth'])->post('delete-posts/{id}', [PostController::class, 'destroy']);
Route::middleware(['auth'])->post('read-posts/{id}', [PostController::class, 'read']);

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

});

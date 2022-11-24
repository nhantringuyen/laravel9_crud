<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PassportAuthController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\FollowController;


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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);

Route::get('list_posts', [PostController::class, 'index']);
Route::get('post_detail/{post}', [PostController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::get('get-user', [PassportAuthController::class, 'userInfo']);
    //post
    Route::post('create_posts', [PostController::class, 'store']);
    Route::put('update_post/{post}', [PostController::class, 'update']);
    Route::delete('delete_post/{post}', [PostController::class, 'destroy']);
    //follow
    Route::post('add_follow/{user}', [FollowController::class, 'add_follow']);
    Route::post('user_follow_one', [FollowController::class, 'user_follow_one']);
    Route::post('one_follow_users', [FollowController::class, 'one_follow_users']);
});

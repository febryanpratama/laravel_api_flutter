<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group([
    'prefix' => 'auth',
    'controller' => AuthController::class
],function(){
    Route::post('register', 'registerUser');
    Route::post('login', 'login');
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'user',
    'controller' => UserController::class,
    'middleware' => ['auth:sanctum','abilities:user']
], function(){
    Route::prefix('blog')->group(function(){
        Route::get('/', 'getBlog');
        Route::post('/', 'createBlog');
        Route::get('/{id}', 'getBlogById');
        Route::put('/', 'updateBlog');
    });
});

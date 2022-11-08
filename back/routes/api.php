<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\DecksController;
use App\Http\Controllers\UsersController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::group(['middleware' => ['web']], function () {
//     Route::post('/login', [AuthController::class, 'authenticate']);
// });

Route::post('/register', [UsersController::class, 'store']);

Route::post('/sanctum/token', [AuthController::class, 'authenticate']);
Route::post('/sanctum/logout', [AuthController::class, 'logout']);

route::get('auth', [AuthController::class, 'index']);

Route::post('users/{user}/favorites/{deck}', [UsersController::class, 'addFavorite']);

Route::middleware('auth:sanctum')->prefix('/users')->group(function () {
    Route::get('/{user}', [UsersController::class, 'show']);
    Route::post('/{user}/favorites', [UsersController::class, 'addFavorite']);
    Route::delete('/{user}/favorites', [UsersController::class, 'removeFavorite']);
});

Route::prefix('/cards')->group(function () {
    Route::get('/', [CardsController::class, 'index']);
    Route::get('/{card}', [CardsController::class, 'show']);

    Route::post('/', [CardsController::class, 'store']);

    Route::put('/{card}', [CardsController::class, 'update']);

    Route::delete('/{card}', [CardsController::class, 'destroy']);
});


Route::prefix('/decks')->group(function () {
    route::get('/', [DecksController::class, 'index']);
    route::get('/{deck}', [DecksController::class, 'show']);
    route::get('/{deck}/edit', [DecksController::class, 'edit']);

    route::post('/', [DecksController::class, 'store']);
    Route::post('/{deck}/clone', [DecksController::class, 'clone']);

    route::put('/{deck}', [DecksController::class, 'update']);

    route::delete('/{deck}', [DecksController::class, 'delete']);
});

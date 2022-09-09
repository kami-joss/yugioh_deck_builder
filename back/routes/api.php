<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardsController;
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
Route::group(['middleware' => ['web']], function () {
    Route::post('/login', [AuthController::class, 'authenticate']);
});

Route::post('/register', [UsersController::class, 'store']);

Route::post('/sanctum/token', [AuthController::class, 'authenticate']);

Route::prefix('/cards')->group(function () {
    Route::get('/', [CardsController::class, 'index']);
    Route::get('/{card}', [CardsController::class, 'show']);

    Route::post('/', [CardsController::class, 'store']);

    Route::put('/{card}', [CardsController::class, 'update']);

    Route::delete('/{card}', [CardsController::class, 'destroy']);
});

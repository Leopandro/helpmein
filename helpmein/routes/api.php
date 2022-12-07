<?php

use App\Domain\User\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers as Controller;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/verify_token', [Controller\AuthController::class, 'verifyToken']);

    Route::get('user/list', function () {
        return User::query()->get()->toArray();
    });
});
Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [Controller\AuthController::class, 'login']);
    Route::post('/register', [Controller\AuthController::class, 'register']);
    Route::post('/remind-password', [Controller\AuthController::class, 'remindPassword']);
    Route::post('/change-password', [Controller\AuthController::class, 'changePassword']);
});

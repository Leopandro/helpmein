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

    Route::group(['prefix' => 'user'], function () {
        Route::get('/info/{user}', [Controller\UserController::class, 'info'])->name('info');
        Route::post('/create', [Controller\UserController::class, 'create'])->name('create');
        Route::post('/edit/{user}', [Controller\UserController::class, 'edit'])->name('edit');
        Route::post('/delete/{user}', [Controller\UserController::class, 'delete'])->name('delete');
        Route::get('/list', [Controller\UserController::class, 'list'])->name('list');
    });

    Route::group(['prefix' => 'category-tree'], function () {
        Route::get('/list', [Controller\CategoryTreeController::class, 'list'])->name('list');
        Route::post('/add', [Controller\CategoryTreeController::class, 'add'])->name('add');
        Route::post('/edit', [Controller\CategoryTreeController::class, 'edit'])->name('edit');
        Route::post('/delete', [Controller\CategoryTreeController::class, 'delete'])->name('delete');
    });
});
Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [Controller\AuthController::class, 'login']);
    Route::post('/update-password', [Controller\AuthController::class, 'updatePassword'])->name('update-password');
    Route::post('/register', [Controller\AuthController::class, 'register']);
    Route::post('/remind-password', [Controller\AuthController::class, 'remindPassword']);
    Route::post('/change-password', [Controller\AuthController::class, 'changePassword']);
});

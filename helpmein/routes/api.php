<?php

use App\Domain\User\Model\User;
use App\Infrastructure\Http\Routing\FrontendRouterFacade;
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
    Route::post('/verify_token', [Controller\AuthController::class, 'verifyToken'])->name('verify_token');

    Route::group(['prefix' => 'user'], function () {
        Route::get('/info/{user}', [Controller\UserController::class, 'info'])->name('info');
        Route::get('/profile/info/', [Controller\UserController::class, 'profileInfo'])->name('profile-info');
        Route::post('/create', [Controller\UserController::class, 'create'])->name('create');
        Route::post('/edit/{user}', [Controller\UserController::class, 'edit'])->name('edit');
        Route::post('/profile/edit/', [Controller\UserController::class, 'profileEdit'])->name('profile-edit');
        Route::post('/delete/{user}', [Controller\UserController::class, 'delete'])->name('delete');
        Route::get('/list', [Controller\UserController::class, 'list'])->name('list');
    });

    Route::group(['prefix' => 'admin'], function () {
        Route::group(['prefix' => 'task'], function () {
            Route::get('/info/{task}', [Controller\TaskController::class, 'info'])->name('info');
            Route::post('/create', [Controller\TaskController::class, 'create'])->name('create');
            Route::post('/edit/{task}', [Controller\TaskController::class, 'edit'])->name('edit');
            Route::post('/delete/{task}', [Controller\TaskController::class, 'delete'])->name('delete');
            Route::get('/list', [Controller\TaskController::class, 'list'])->name('list');
        });
        Route::group(['prefix' => 'user-task'], function () {
            Route::get('/list', [Controller\TeacherClientTaskController::class, 'list'])->name('list');
            Route::post('/mass-assign', [Controller\TeacherClientTaskController::class, 'massAssign'])->name('mass-assign');
        });
    });

    Route::group(['prefix' => 'client'], function() {
        Route::group(['prefix' => 'task'], function () {
            Route::get('/list', [Controller\ClientTaskController::class, 'list'])->name('list');
            Route::post('/{task}/check-answer/{id}', [Controller\ClientTaskController::class, 'checkAnswer'])->name('check-answer');
            Route::post('/solve/{task}', [Controller\ClientTaskController::class, 'solve'])->name('solve');
            Route::get('/info/{task}', [Controller\ClientTaskController::class, 'info'])->name('info');
        });
    });


    Route::group(['prefix' => 'category-tree'], function () {
        Route::get('/list', [Controller\CategoryTreeController::class, 'list'])->name('list');
        Route::post('/add', [Controller\CategoryTreeController::class, 'add'])->name('add');
        Route::post('/edit', [Controller\CategoryTreeController::class, 'edit'])->name('edit');
        Route::post('/replace', [Controller\CategoryTreeController::class, 'replace'])->name('replace');
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

Route::get('/test', [Controller\TestController::class, 'list']);

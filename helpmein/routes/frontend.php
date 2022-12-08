<?php
declare(strict_types=1);
use Illuminate\Support\Facades\Route;
Route::name('frontend.')
    ->group(static function() {
        Route::get('/')->name('main-page');

        Route::get('/support')->name('support');
    });

<?php

namespace App\Providers;

use App\Rules\CheckedCheckboxValue;
use App\Rules\CheckedCheckboxValueRadio;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootValidators();
    }

    public function bootValidators()
    {
        Validator::extend('arrayChecked', CheckedCheckboxValue::class);
        Validator::extend('arrayCheckedRadio', CheckedCheckboxValueRadio::class);
    }
}

<?php

namespace App\Providers;

use App\Models\User;
use App\Rules\CheckedCheckboxValue;
use App\Rules\CheckedCheckboxValueMax;
use App\Rules\CheckedCheckboxValueRadio;
use App\Rules\InnValidationRule;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;

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

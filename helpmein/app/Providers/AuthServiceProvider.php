<?php

namespace App\Providers;

use App\Domain\Task\Gates\TaskEditByUserGate;
use App\Domain\Task\Gates\TaskSolveByClientGate;
use App\Domain\User\Gates\ClientEditByUserGate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        $this->registerGates();
    }

    public function registerGates()
    {
        Gate::define(ClientEditByUserGate::getCode(), ClientEditByUserGate::class);
        Gate::define(TaskEditByUserGate::getCode(), TaskEditByUserGate::class);
        Gate::define(TaskSolveByClientGate::getCode(), TaskSolveByClientGate::class);
    }
}

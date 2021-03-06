<?php

namespace App\Providers;

use App\User;
use App\Demand;
use App\Contract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Demand\Demand::class => Demand\DemandPolicy::class,
        User\User::class => User\UserPolicy::class,
        Contract\Contract::class => Contract\ContractPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}

<?php

namespace App\Providers;

use App\Models\ApprenantFirebase;
use App\Models\UserMysql;
use App\Models\UserFirebase;
use App\Policies\UserPolicy;
use Laravel\Passport\Passport;
use App\Policies\ApprenantsPolicy;
use App\Repositories\AuthRepository;
use App\Providers\AuthCustomProvider;
use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        UserMysql::class => UserPolicy::class,
        UserFirebase::class => UserPolicy::class,
        ApprenantFirebase::class => ApprenantsPolicy::class,
    ];

    public function register()
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->register(AuthCustomProvider::class);
        
    }

    public function boot()
    {
        $this->registerPolicies();
            Passport::tokensExpireIn(now()->addMinutes(5));
            Passport::refreshTokensExpireIn(now()->addDays(1));
            Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }
}

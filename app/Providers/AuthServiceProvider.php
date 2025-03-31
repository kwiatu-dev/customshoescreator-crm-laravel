<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Client;
use App\Policies\ProjectPolicy;
use App\Policies\ForgotPasswordPolicy;
use App\Models\Project;
use App\Models\User;
use App\Models\UserEvents;
use App\Policies\ClientPolicy;
use App\Policies\UserEventsPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Project::class => ProjectPolicy::class,
        User::class => UserPolicy::class,
        Client::class => ClientPolicy::class,
        UserEvents::class => UserEventsPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}

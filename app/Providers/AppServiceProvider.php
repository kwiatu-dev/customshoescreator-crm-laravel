<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Expenses;
use App\Models\Income;
use App\Models\Investment;
use App\Models\Project;
use App\Models\User;
use App\Observers\ClientObserver;
use App\Observers\ExpensesObserver;
use App\Observers\IncomeObserver;
use App\Observers\InvestmentObserver;
use App\Observers\ProjectObserver;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Client::observe(ClientObserver::class);
        Project::observe(ProjectObserver::class);
        Income::observe(IncomeObserver::class);
        Expenses::observe(ExpensesObserver::class);
        Investment::observe(InvestmentObserver::class);
    }
}

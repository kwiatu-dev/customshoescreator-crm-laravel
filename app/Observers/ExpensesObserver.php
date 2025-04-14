<?php

namespace App\Observers;

use App\Models\Expenses;
use Illuminate\Support\Facades\Cache;

class ExpensesObserver
{
    /**
     * Handle the Expenses "created" event.
     */
    public function created(Expenses $expenses): void
    {
        $this->clearExpensesCache();
    }

    /**
     * Handle the Expenses "updated" event.
     */
    public function updated(Expenses $expenses): void
    {
        $this->clearExpensesCache();
    }

    /**
     * Handle the Expenses "deleted" event.
     */
    public function deleted(Expenses $expenses): void
    {
        $this->clearExpensesCache();
    }

    /**
     * Handle the Expenses "restored" event.
     */
    public function restored(Expenses $expenses): void
    {
        $this->clearExpensesCache();
    }

    /**
     * Handle the Expenses "force deleted" event.
     */
    public function forceDeleted(Expenses $expenses): void
    {
        $this->clearExpensesCache();
    }

    /**
     * Clear cache for expenses-related metrics.
     */
    private function clearExpensesCache(): void
    {
        Cache::forget(config('cache_keys.total_expenses_count'));
        Cache::forget(config('cache_keys.total_expenses_sum'));
    }
}

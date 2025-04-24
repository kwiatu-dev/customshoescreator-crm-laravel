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
        $this->clearCache();
    }

    /**
     * Handle the Expenses "updated" event.
     */
    public function updated(Expenses $expenses): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Expenses "deleted" event.
     */
    public function deleted(Expenses $expenses): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Expenses "restored" event.
     */
    public function restored(Expenses $expenses): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Expenses "force deleted" event.
     */
    public function forceDeleted(Expenses $expenses): void
    {
        $this->clearCache();
    }

    /**
     * Clear cache for expenses-related metrics.
     */
    private function clearCache(): void
    {
        Cache::tags(['expenses'])->flush();
    }
}

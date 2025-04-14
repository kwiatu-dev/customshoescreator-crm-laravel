<?php

namespace App\Observers;

use App\Models\Income;
use Illuminate\Support\Facades\Cache;

class IncomeObserver
{
    /**
     * Handle the Income "created" event.
     */
    public function created(Income $income): void
    {
        $this->clearIncomeCache();
    }

    /**
     * Handle the Income "updated" event.
     */
    public function updated(Income $income): void
    {
        $this->clearIncomeCache();
    }

    /**
     * Handle the Income "deleted" event.
     */
    public function deleted(Income $income): void
    {
        $this->clearIncomeCache();
    }

    /**
     * Handle the Income "restored" event.
     */
    public function restored(Income $income): void
    {
        $this->clearIncomeCache();
    }

    /**
     * Handle the Income "force deleted" event.
     */
    public function forceDeleted(Income $income): void
    {
        $this->clearIncomeCache();
    }

    /**
     * Clear cache for income-related metrics.
     */
    private function clearIncomeCache(): void
    {
        Cache::forget(config('cache_keys.total_income_count'));
        Cache::forget(config('cache_keys.total_gross_income_sum'));
        Cache::forget(config('cache_keys.total_net_income_sum'));
        Cache::forget(config('cache_keys.total_awaiting_income_sum'));
        Cache::forget(config('cache_keys.total_completed_income_count'));
        Cache::forget(config('cache_keys.total_active_income_count'));
    }
}

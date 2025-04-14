<?php

namespace App\Observers;

use App\Models\Investment;
use Illuminate\Support\Facades\Cache;

class InvestmentObserver
{
    /**
     * Handle the Investment "created" event.
     */
    public function created(Investment $investment): void
    {
        $this->clearInvestmentCache();
    }

    /**
     * Handle the Investment "updated" event.
     */
    public function updated(Investment $investment): void
    {
        $this->clearInvestmentCache();
    }

    /**
     * Handle the Investment "deleted" event.
     */
    public function deleted(Investment $investment): void
    {
        $this->clearInvestmentCache();
    }

    /**
     * Handle the Investment "restored" event.
     */
    public function restored(Investment $investment): void
    {
        $this->clearInvestmentCache();
    }

    /**
     * Handle the Investment "force deleted" event.
     */
    public function forceDeleted(Investment $investment): void
    {
        $this->clearInvestmentCache();
    }

    /**
     * Clear cache for investment-related metrics.
     */
    private function clearInvestmentCache(): void
    {
        Cache::forget(config('cache_keys.total_investments_count'));
        Cache::forget(config('cache_keys.total_investments_sum'));
        Cache::forget(config('cache_keys.total_active_investments_count'));
        Cache::forget(config('cache_keys.total_after_date_investments_count'));
        Cache::forget(config('cache_keys.total_completed_investments_count'));
        Cache::forget(config('cache_keys.total_awaiting_repayment_sum'));
        Cache::forget(config('cache_keys.total_repayment_sum'));
    }
}

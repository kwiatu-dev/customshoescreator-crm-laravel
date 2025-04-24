<?php

namespace App\Observers;

use App\Models\Investment;
use Illuminate\Support\Facades\Cache;

class InvestmentObserver
{
    public function created(Investment $investment): void
    {
        $this->clearCache();
    }

    public function updated(Investment $investment): void
    {
        $this->clearCache();
    }

    public function deleted(Investment $investment): void
    {
        $this->clearCache();
    }

    public function restored(Investment $investment): void
    {
        $this->clearCache();
    }

    public function forceDeleted(Investment $investment): void
    {
        $this->clearCache();
    }

    private function clearCache(): void
    {
        Cache::tags(['investments'])->flush();
    }
}

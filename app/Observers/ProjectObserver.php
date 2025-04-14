<?php

namespace App\Observers;

use App\Models\Project;
use Illuminate\Support\Facades\Cache;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        $this->clearProjectAndIncomeCache();
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        $this->clearProjectAndIncomeCache();
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        $this->clearProjectAndIncomeCache();
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        $this->clearProjectAndIncomeCache();
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        $this->clearProjectAndIncomeCache();
    }

    /**
     * Clear cache for projects and related income.
     */
    private function clearProjectAndIncomeCache(): void
    {
        Cache::forget(config('cache_keys.total_projects_count'));
        Cache::forget(config('cache_keys.total_income_count'));
        Cache::forget(config('cache_keys.total_gross_income_sum'));
        Cache::forget(config('cache_keys.total_net_income_sum'));
        Cache::forget(config('cache_keys.total_awaiting_income_sum'));
        Cache::forget(config('cache_keys.total_completed_income_count'));
        Cache::forget(config('cache_keys.total_active_income_count'));
        Cache::forget(config('cache_keys.total_awaiting_projects_count'));
        Cache::forget(config('cache_keys.total_in_progress_projects_count'));
        Cache::forget(config('cache_keys.total_after_deadline_projects_count'));
        Cache::forget(config('cache_keys.total_completed_projects_count'));
    }
}

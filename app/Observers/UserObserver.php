<?php

namespace App\Observers;

use App\Models\User;
use Cache;
use Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->clearCache();
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        Log::info('User updated via observer', ['user_id' => $user->id]);
        $this->clearCache();
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->clearCache();
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        $this->clearCache();
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        $this->clearCache();
    }

    private function clearCache(): void
    {
        Cache::tags(['users'])->flush();
    }
}

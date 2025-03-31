<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserEvents;
use Illuminate\Auth\Access\Response;

class UserEventsPolicy
{
/**
     * Perform pre-authorization checks.
     */
    public function before(User $user)
    {
        if ($user->is_admin) {
            return true; 
        }
    }

    public function view(User $user, UserEvents $userEvent)
    {
        return $user->id === $userEvent->user_id;
    }

    /**
     * Determine if the user can update the event.
     */
    public function update(User $user, UserEvents $userEvent)
    {
        return $user->id === $userEvent->user_id;
    }

    /**
     * Determine if the user can delete the event.
     */
    public function delete(User $user, UserEvents $userEvent)
    {
        return $user->id === $userEvent->user_id;
    }

    /**
     * Determine if the user can restore the event.
     */
    public function restore(User $user, UserEvents $userEvent)
    {
        return $user->id === $userEvent->user_id;
    }
}

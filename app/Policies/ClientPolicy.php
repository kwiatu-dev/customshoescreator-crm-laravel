<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientPolicy
{
    public function before(?User $user, $ability) {
        if($user?->is_admin){
            return true;
        }
    }

    public function edit(User $user, Client $client): bool
    {
        return $user->id === $client->created_by_user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Client $client): bool
    {
        return $user->id === $client->created_by_user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Client $client): bool
    {
        return $user->id === $client->created_by_user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Client $client): bool
    {
        return $user->id === $client->created_by_user_id;
    }
}

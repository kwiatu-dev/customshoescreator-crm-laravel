<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function edit(User $user, User $model): bool {
        return $model?->is_admin == false || $user->id == $model->id;
    }

    public function update(User $user, User $model): bool
    {
        return $model?->is_admin == false || $user->id == $model->id;
    }

    public function destroy(User $user, User $model): bool
    {
        return $model?->is_admin == false;

    }

    public function restore(User $user, User $model): bool
    {
        return $model?->is_admin == false;
    }
}

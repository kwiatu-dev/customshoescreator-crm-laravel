<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;

class ProjectPolicy
{
    public function before(?User $user, $ability){
        if($user?->is_admin){
            return true;
        }
    }

    //todo: ustawienie polityk sprawdzać, czy mozna edytowac dodawac usuwac itd.
    public function show(User $user, Project $project): bool
    {
        return $user->id === $project->created_by_user_id;
    }

    public function destroy(User $user, Project $project): bool
    {
        return $user->id === $project->created_by_user_id;
    }

    public function restore(User $user, Project $project): bool
    {
        return $user->id === $project->created_by_user_id;
    }

    public function edit(User $user, Project $project): bool
    {
        return $user->id === $project->created_by_user_id;
    }

    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->created_by_user_id;
    }

    public function status(User $user, Project $project): bool
    {
        return $user->id === $project->created_by_user_id;
    }

    public function upload(User $user, Project $project): bool
    {
        return $user->id === $project->created_by_user_id;
    }
}

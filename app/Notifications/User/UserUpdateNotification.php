<?php
namespace App\Notifications\User;

use App\Models\User;

class UserUpdateNotification extends BaseUserNotification
{
    public function __construct(
        User $user,
        User $auth,
        ?User $recipient) 
    {
        parent::__construct($user, $auth, $recipient);
    }

    public function toArray(object $notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
           //
        ]);
    }
}
<?php
namespace App\Notifications\User;

use App\Models\User;

class UserResetPasswordNotification extends BaseUserNotification
{
    public function __construct(
        User $user,
        ?User $auth,
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

    public function buildMessage(): string
    {
        return __("users.notifications.password_reset.message", [
            'user_fullname' => $this->user_fullname,
            'user_url' => $this->user_url,
        ]);
    }
}
<?php
namespace App\Notifications\UserEvent;

use App\Models\User;
use App\Models\UserEvents;

class UserEventDeleteNotification extends BaseUserEventNotification
{
    public function __construct(
        UserEvents $user_event,
        User $auth,
        User $recipient) 
    {
        parent::__construct($user_event, $auth, $recipient);
    }

    public function toArray(object $notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
           //
        ]);
    }
}

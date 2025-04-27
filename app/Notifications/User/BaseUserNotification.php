<?php

namespace App\Notifications\Client;

use App\Models\Client;
use App\Models\User;
use App\Notifications\BaseNotification;

class BaseUserNotification extends BaseNotification
{
    private string $user_id;
    private string $user_url;
    private string $user_fullname;

    public function __construct(    
        private User $user,
        User $auth,
        User $recipient) 
    {
        parent::__construct($auth, $recipient);

        $this->user_id = $this->user->id;
        $this->user_url = route('user.show', ['user' => $this->user->id]);
        $this->user_fullname = $this->user->first_name .' '. $this->user->last_name;
    }

    public function toArray(object $notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
            'user_id' => $this->user_id,
            'user_url' => $this->user_url,
            'user_fullname' => $this->user_fullname,
            'message' => $this->buildMessage(),
        ]);
    }

    public function buildMessage(): string
    {
        return __("users.notifications.{$this->action}.message", [
            'user_fullname' => $this->user_fullname,
            'user_url' => $this->user_url,
            'auth_fullname' => $this->auth_fullname,
            'auth_url' => $this->auth_url,
            'recipient_fullname' => $this->recipient_fullname,
            'recipient_url' => $this->recipient_url,
        ]);
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

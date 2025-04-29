<?php
namespace App\Notifications\UserEvent;

use App\Models\User;
use App\Models\UserEvents;
use App\Notifications\BaseNotification;

class BaseUserEventNotification extends BaseNotification
{
    protected string $user_event_id;
    protected string $user_event_url;
    protected string $user_event_title;
    protected string $user_event_type_id;
    protected string $user_event_type_name;

    public function __construct(    
        private UserEvents $user_event,
        User $auth,
        User $recipient) 
    {
        parent::__construct($auth, $recipient);

        $this->user_event_id = $this->user_event->id;
        $this->user_event_url = route('user-events.show', ['user_event' => $this->user_event->id]);
        $this->user_event_title = $this->user_event->title;
        $this->user_event_type_id = $this->user_event->type_id;
        $this->user_event_type_name = $this->user_event->type->name;
    }

    public function toArray(object $notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
            'user_event_id' => $this->user_event_id,
            'user_event_url' => $this->user_event_url,
            'user_event_title' => $this->user_event_title,
            'user_event_type_id' => $this->user_event_type_id,
            'user_event_type_name' => $this->user_event_type_name,
            'message' => $this->buildMessage(),
        ]);
    }

    public function buildMessage(): string
    {
        return __("user-events.notifications.{$this->action}.message", [
            'user_event_title' => $this->user_event_title,
            'user_event_type_name' => $this->user_event_type_name,
            'user_event_url' => $this->user_event_url,
            'auth_fullname' => $this->auth_fullname,
            'auth_url' => $this->auth_url,
            'recipient_fullname' => $this->recipient_fullname,
            'recipient_url' => $this->recipient_url,
        ]);
    }

    public function getUserEvent(): UserEvents
    {
        return $this->user_event;
    }
}

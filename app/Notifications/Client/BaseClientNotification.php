<?php

namespace App\Notifications\Client;

use App\Models\Client;
use App\Models\User;
use App\Notifications\BaseNotification;

class BaseClientNotification extends BaseNotification
{
    protected string $client_id;
    protected string $client_url;
    protected string $client_fullname;

    public function __construct(    
        private Client $client,
        User $auth,
        User $recipient) 
    {
        parent::__construct($auth, $recipient);

        $this->client_id = $this->client->id;
        $this->client_url = route('client.show', ['client' => $this->client->id]);
        $this->client_fullname = $this->client->first_name .' '. $this->client->last_name;
    }

    public function toArray(object $notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
            'client_id' => $this->client_id,
            'client_url' => $this->client_url,
            'client_fullname' => $this->client_fullname,
            'message' => $this->buildMessage(),
        ]);
    }

    public function buildMessage(): string
    {
        return __("clients.notifications.{$this->action}.message", [
            'client_fullname' => $this->client_fullname,
            'client_url' => $this->client_url,
            'auth_fullname' => $this->auth_fullname,
            'auth_url' => $this->auth_url,
            'recipient_fullname' => $this->recipient_fullname,
            'recipient_url' => $this->recipient_url,
        ]);
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}

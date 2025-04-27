<?php
namespace App\Notifications\Client;

use App\Models\Client;
use App\Models\User;

class ClientUpdateNotification extends BaseClientNotification
{
    public function __construct(
        Client $client,
        User $auth,
        User $recipient) 
    {
        parent::__construct($client, $auth, $recipient);
    }

    public function toArray(object $notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
           //
        ]);
    }
}

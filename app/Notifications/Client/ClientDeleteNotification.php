<?php

namespace App\Notifications\Client;

use App\Models\Client;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientDeleteNotification extends BaseClientNotification
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

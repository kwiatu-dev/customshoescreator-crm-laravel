<?php
namespace App\Notifications\Project;

use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectDeleteNotification extends BaseProjectNotification
{
    public function __construct(
        Project $project,
        User $auth,
        User $recipient) 
    {
        parent::__construct($project, $auth, $recipient);
    }

    public function toArray(object $notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
            //
        ]);
    }
}

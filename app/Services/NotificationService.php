<?php
namespace App\Services;

use App\Models\User;
use App\Notifications\BaseNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function sendNotification(BaseNotification $notification)
    {
        $auth = $notification->getAuth(); 
        $recipient = $notification->getRecipient();

        $notifiables = $this->buildRecipients($auth, $recipient);

        if ($notifiables->isNotEmpty()) {
            Notification::send($notifiables, $notification);
        }
    }

    protected function buildRecipients(User $auth, User $recipient)
    {
        $admins = User::where('is_admin', true)->get();
        $recipients = collect();

        $admin_recipients = $admins->filter(fn ($admin) => ($admin->id !== $recipient->id && $admin->id !== $auth->id));
        $recipients = $recipients->merge($admin_recipients);

        if ($auth->id !== $recipient->id) {
            $recipients->push($recipient);
        }

        return $recipients->unique('id');
    }
}
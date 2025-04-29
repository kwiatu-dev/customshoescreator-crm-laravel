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

    protected function buildRecipients(?User $auth, ?User $recipient)
    {
        $admins = User::where('is_admin', true)->get();
        $recipients = collect();

        $admin_recipients = $admins->filter(fn ($admin) => ((!$recipient || $admin->id !== $recipient->id) && (!$auth || $admin->id !== $auth->id)));
        $recipients = $recipients->merge($admin_recipients);

        if ($recipient && $auth && $auth->id !== $recipient->id) {
            $recipients->push($recipient);
        }

        return $recipients->unique('id');
    }
}
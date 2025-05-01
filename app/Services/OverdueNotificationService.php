<?php
namespace App\Services;

use App\Models\Project;
use App\Notifications\Project\OverdueProjectNotification;
use Illuminate\Support\Facades\Notification;

class OverdueNotificationService
{
    public function __construct(
        protected NotificationService $notificationService)
    {

    }

    public function handle(): void
    {
        $projects = Project::with(['user', 'client'])
            ->where('deadline', '<', now())
            ->where('status_id', '!=', Project::STATUS_COMPLETED)
            ->get();

            $projects->each(function ($project) {
                $notification = new OverdueProjectNotification($project);
                $this->notificationService->sendNotification($notification);
            });
    }
}

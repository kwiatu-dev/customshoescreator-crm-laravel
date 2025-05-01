<?php
namespace App\Services;

use App\Models\Project;
use App\Notifications\Project\ProjectStartDelayedNotification;
use Illuminate\Support\Facades\Notification;

class UnstartedProjectNotificationService
{
    public function __construct(
        protected NotificationService $notificationService)
    {

    }

    public function handle(): void
    {
        $projects = Project::with(['user', 'client'])
            ->where('start', '<', now())
            ->where('status_id', Project::STATUS_AWAITING)
            ->get();

        $projects->each(function ($project) {
            $notification = new ProjectStartDelayedNotification($project);
            $this->notificationService->sendNotification($notification);
        });
    }
}

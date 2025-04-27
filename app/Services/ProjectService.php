<?php
namespace App\Services;

use App\Models\Project;
use App\Models\ProjectImageType;
use App\Models\User;
use App\Notifications\ProjectCreate;

class ProjectService {
    public function __construct(
        private NotificationService $notificationService,
        private UserService $userService,
    ) {}

    public function create(User $auth, array $fields, array $images): Project {
        $created_by_user = $this->userService->getUserById($fields['created_by_user_id'] ?? $auth->id());

        $fields = array_merge($fields, [
            'commission' => $created_by_user->commission,
            'costs' => $created_by_user->costs,
            'distribution' => $created_by_user->distribution,
            'created_by_user_id' => $created_by_user->id,
        ]);

        $project = Project::create($fields);
        $project->addImages($images, ProjectImageType::TYPE_INSPIRATION);

        $this->notificationService->sendNotification(
            notification: new ProjectCreate($project, $created_by_user),
            recipient: $created_by_user
        );

        return $project;
    }
}
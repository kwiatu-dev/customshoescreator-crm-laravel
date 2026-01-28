<?php

namespace App\Notifications\Project;

use App\Models\Project;
use App\Models\User;
use App\Notifications\BaseNotification;

class BaseProjectNotification extends BaseNotification
{
    protected string $project_id;
    protected string $project_url;
    protected string $project_title;
    protected string $project_status_id;
    protected string $project_status_name;

    public function __construct(    
        private Project $project,
        ?User $auth,
        ?User $recipient) 
    {
        parent::__construct($auth, $recipient);

        $this->project_id = $this->project->id;
        $this->project_url = route('projects.show', ['project' => $this->project->id]);
        $this->project_title = $this->project->title;
        $this->project_status_id = $this->project->status_id;
        $this->project_status_name = $this->project->status->name;
    }

    public function toArray(object $notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
            'project_id' => $this->project_id,
            'project_url' => $this->project_url,
            'project_title' => $this->project_title,
            'project_status_id' => $this->project_status_id,
            'project_status_name' => $this->project_status_name,
            'message' => $this->buildMessage(),
        ]);
    }

    public function buildMessage(): string
    {
        return __("projects.notifications.{$this->action}.message", [
            'project_title' => $this->project_title,
            'project_url' => $this->project_url,
            'auth_fullname' => $this->auth_fullname,
            'auth_url' => $this->auth_url,
            'recipient_fullname' => $this->recipient_fullname,
            'recipient_url' => $this->recipient_url,
            'status_name' => $this->project_status_name,
        ]);
    }

    public function getProject(): Project
    {
        return $this->project;
    }
}

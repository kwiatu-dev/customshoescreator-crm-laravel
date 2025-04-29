<?php

namespace App\Notifications\Income;

use App\Models\Income;
use App\Models\User;
use App\Notifications\BaseNotification;

class BaseIncomeNotification extends BaseNotification
{
    protected string $income_id;
    protected string $income_title;
    protected string $income_url;
    protected string $income_status_id;
    protected string $income_status_name;
    protected ?string $project_id;
    protected ?string $project_url;
    protected ?string $project_title;
    protected string $related_with;

    public function __construct(    
        private Income $income,
        User $auth,
        ?User $recipient) 
    {
        parent::__construct($auth, $recipient);

        if ($this->income->project) {
            $this->project_id = $this->income->project->id;
            $this->project_url = route('projects.show', ['project' => $this->income->project->id]);
            $this->project_title = $this->income->project->title;
            $this->related_with = 'related_with_project';
        } else {
            $this->project_id = null;
            $this->project_url = null;
            $this->project_title = null;
            $this->related_with = 'related_with_user';
        }

        $this->income_id = $this->income->id;
        $this->income_title = $this->income->title;
        $this->income_url = route('incomes.show', ['income' => $this->income->id]);
        $this->income_status_id = $this->income->status_id;
        $this->income_status_name = $this->income->status->name;
    }

    public function toArray(object $notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
            'income_id' => $this->income_id,
            'income_title' => $this->income_title,
            'income_url' => $this->income_url,
            'income_status_id' => $this->income_status_id,
            'income_status_name' => $this->income_status_name,
            'project_id' => $this->project_id,
            'project_url' => $this->project_url,
            'project_title' => $this->project_title,
            'related_with' => $this->related_with,
            'message' => $this->buildMessage(),
        ]);
    }

    public function buildMessage(): string
    {
        return __("incomes.notifications.{$this->action}.{$this->related_with}.message", [
            'income_id' => $this->income_id,
            'income_title' => $this->income_title,
            'income_status_name' => $this->income_status_name,
            'income_url' => $this->income_url,
            'project_id' => $this->project_id,
            'project_url' => $this->project_url,
            'project_title' => $this->project_title,
            'auth_fullname' => $this->auth_fullname,
            'auth_url' => $this->auth_url,
        ]);
    }

    public function getIncome(): Income
    {
        return $this->income;
    }
}

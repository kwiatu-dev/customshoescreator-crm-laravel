<?php

namespace App\Notifications\Investment;

use App\Models\Investment;
use App\Models\User;
use App\Notifications\BaseNotification;

class BaseInvestmentNotification extends BaseNotification
{
    protected string $investment_id;
    protected string $investment_url;
    protected string $investment_status_id;
    protected string $investment_status_name;
    protected string $investment_investor_id;
    protected string $investment_investor_url;
    protected string $investment_investor_fullname;

    public function __construct(    
        private Investment $investment,
        User $auth,
        ?User $recipient) 
    {
        parent::__construct($auth, $recipient);

        $this->investment_id = $this->investment->id;
        $this->investment_url = route('investments.show', ['investment' => $this->investment->id]);
        $this->investment_status_id = $this->investment->status_id;
        $this->investment_status_name = $this->investment->status->name;
        $this->investment_investor_id = $this->investment->user_id;
        $this->investment_investor_url = route('user.show', ['user' => $this->investment->user_id]);
        $this->investment_investor_fullname = $this->investment->investor->first_name . ' ' . $this->investment->investor->last_name;
    }

    public function toArray(object $notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
            'investment_id' => $this->investment_id,
            'investment_url' => $this->investment_url,
            'investment_status_id' => $this->investment_status_id,
            'investment_status_name' => $this->investment_status_name,
            'investment_investor_id' => $this->investment_investor_id,
            'investment_investor_url' => $this->investment_investor_url,
            'investment_investor_fullname' => $this->investment_investor_fullname,
            'message' => $this->buildMessage(),
        ]);
    }

    public function buildMessage(): string
    {
        return __("investments.notifications.{$this->action}.message", [
            'investment_status_name' => $this->investment_status_name,
            'investment_url' => $this->investment_url,
            'investment_id' => $this->investment_id,
            'investment_investor_fullname' => $this->investment_investor_fullname,
            'investment_investor_url' => $this->investment_investor_url,
            'auth_fullname' => $this->auth_fullname,
            'auth_url' => $this->auth_url,
            'recipient_fullname' => $this->recipient_fullname,
            'recipient_url' => $this->recipient_url,
        ]);
    }

    public function getInvestment(): Investment
    {
        return $this->investment;
    }
}

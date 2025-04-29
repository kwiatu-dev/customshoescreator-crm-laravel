<?php

namespace App\Notifications\InvestmentRepayment;

use App\Models\InvestmentRepayment;
use App\Models\User;
use App\Notifications\BaseNotification;

class BaseInvestmentRepaymentNotification extends BaseNotification
{
    protected string $investment_repayment_id;
    protected string $investment_id;
    protected string $investment_url;
    protected string $investment_status_id;
    protected string $investment_status_name;
    protected string $investment_investor_id;
    protected string $investment_investor_url;
    protected string $investment_investor_fullname;

    public function __construct(    
        private InvestmentRepayment $investment_repayment,
        User $auth,
        ?User $recipient) 
    {
        parent::__construct($auth, $recipient);

        $this->investment_id = $this->investment_repayment->investment->id;
        $this->investment_url = route('investments.show', ['investment' => $this->investment_repayment->investment->id]);
        $this->investment_repayment_id = $this->investment_repayment->id;
        $this->investment_status_id = $this->investment_repayment->investment->status_id;
        $this->investment_status_name = $this->investment_repayment->investment->status->name;
        $this->investment_investor_id = $this->investment_repayment->investment->user_id;
        $this->investment_investor_url = route('user.show', ['user' => $this->investment_repayment->investment->user_id]);
        $this->investment_investor_fullname = $this->investment_repayment->investment->investor->first_name . ' ' . $this->investment_repayment->investment->investor->last_name;
    }

    public function toArray(object $notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
            'investment_id' => $this->investment_id,
            'investment_url' => $this->investment_url,
            'investment_repayment_id' => $this->investment_repayment_id,
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
        return __("investment-repayments.notifications.{$this->action}.message", [
            'investment_status_name' => $this->investment_status_name,
            'investment_url' => $this->investment_url,
            'investment_id' => $this->investment_id,
            'investment_investor_fullname' => $this->investment_investor_fullname,
            'investment_investor_url' => $this->investment_investor_url,
            'auth_fullname' => $this->auth_fullname,
            'auth_url' => $this->auth_url,
            'recipient_fullname' => $this->recipient_fullname,
            'recipient_url' => $this->recipient_url,
            'investment_repayment_id' => $this->investment_repayment_id,
        ]);
    }

    public function getInvestmentRepayment(): InvestmentRepayment
    {
        return $this->investment_repayment;
    }
}

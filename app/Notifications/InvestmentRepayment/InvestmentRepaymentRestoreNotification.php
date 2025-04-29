<?php
namespace App\Notifications\InvestmentRepayment;

use App\Models\InvestmentRepayment;
use App\Models\User;

class InvestmentRepaymentRestoreNotification extends BaseInvestmentRepaymentNotification
{
    public function __construct(
        InvestmentRepayment $investment_repayment,
        User $auth,
        ?User $recipient) 
    {
        parent::__construct($investment_repayment, $auth, $recipient);
    }

    public function toArray(object $notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
           //
        ]);
    }
}

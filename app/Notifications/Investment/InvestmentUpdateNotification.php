<?php
namespace App\Notifications\Investment;

use App\Models\Investment;
use App\Models\User;

class InvestmentUpdateNotification extends BaseInvestmentNotification
{
    public function __construct(
        Investment $investment,
        User $auth,
        ?User $recipient) 
    {
        parent::__construct($investment, $auth, $recipient);
    }

    public function toArray(object $notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
           //
        ]);
    }
}

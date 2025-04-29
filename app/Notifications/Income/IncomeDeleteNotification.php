<?php
namespace App\Notifications\Income;

use App\Models\Income;
use App\Models\User;

class IncomeDeleteNotification extends BaseIncomeNotification
{
    public function __construct(
        Income $income,
        User $auth,
        ?User $recipient) 
    {
        parent::__construct($income, $auth, $recipient);
    }

    public function toArray(object $notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
           //
        ]);
    }
}

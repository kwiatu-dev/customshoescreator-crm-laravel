<?php
namespace App\Notifications\Expense;

use App\Models\Expenses;
use App\Models\User;

class ExpenseDeleteNotification extends BaseExpenseNotification
{
    public function __construct(
        Expenses $expense,
        User $auth,
        ?User $recipient) 
    {
        parent::__construct($expense, $auth, $recipient);
    }

    public function toArray(object $notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
           //
        ]);
    }
}

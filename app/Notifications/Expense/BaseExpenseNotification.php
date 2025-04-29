<?php

namespace App\Notifications\Expense;

use App\Models\Expenses;
use App\Models\User;
use App\Notifications\BaseNotification;

class BaseExpenseNotification extends BaseNotification
{
    protected string $expenses_id;
    protected string $expenses_title;

    public function __construct(    
        private Expenses $expenses,
        User $auth,
        ?User $recipient) 
    {
        parent::__construct($auth, $recipient);

        $this->expenses_id = $this->expenses->id;
        $this->expenses_title = $this->expenses->title;
    }

    public function toArray(object $notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
            'expenses_id' => $this->expenses_id,
            'expenses_title' => $this->expenses_title,
            'message' => $this->buildMessage(),
        ]);
    }

    public function buildMessage(): string
    {
        return __("expenses.notifications.{$this->action}.message", [
            'expenses_id' => $this->expenses_id,
            'expenses_title' => $this->expenses_title,
            'auth_fullname' => $this->auth_fullname,
            'auth_url' => $this->auth_url,
            'recipient_fullname' => $this->recipient_fullname,
            'recipient_url' => $this->recipient_url,
        ]);
    }

    public function getExpenses(): Expenses
    {
        return $this->expenses;
    }
}

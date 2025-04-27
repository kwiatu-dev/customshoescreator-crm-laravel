<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

abstract class BaseNotification extends Notification
{
    use Queueable;

    protected string $recipient_id;
    protected string $recipient_url;
    protected string $recipient_fullname;
    protected string $auth_id;
    protected string $auth_url;
    protected string $auth_fullname;
    protected string $action;

    /**
     * Create a new notification instance.
     */
    public function __construct(    
        protected User $auth,
        protected User $recipient) 
    {
        $this->recipient_id = $this->recipient->id;
        $this->recipient_url = route('user.show', ['user' => $this->recipient->id]);
        $this->recipient_fullname = $this->recipient->first_name . ' ' . $this->recipient->last_name;
        $this->auth_id = $this->auth->id;
        $this->auth_url = route('user.show', ['user' => $this->auth->id]);
        $this->auth_fullname = $this->auth->first_name . ' ' . $this->auth->last_name;
        $this->action = $this->getActionFromClass();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'recipient_id' => $this->recipient_id,
            'recipient_url' => $this->recipient_url,
            'recipient_fullname' => $this->recipient_fullname,
            'auth_id' => $this->auth_id,
            'auth_url' => $this->auth_url,
            'auth_fullname' => $this->auth_fullname,
            'action' => $this->action,
            'message' => $this->buildMessage(),
        ];
    }

    protected function buildMessage(): string {
        return 'Nie zaimplementowano metody buildMessage() w klasie ' . get_class($this);
    }

    protected function getActionFromClass(): string
    {
        $className = (new \ReflectionClass($this))->getShortName();

        if (preg_match('/^[A-Za-z]+([A-Z][a-zA-Z]+)Notification$/', $className, $matches)) {
            $action = $matches[1];
        } else {
            $action = $className;
        }
    
        return strtolower($action);
    }

    public function getAuth(): User
    {
        return $this->auth;
    }

    public function getRecipient(): User
    {
        return $this->recipient;
    }
}
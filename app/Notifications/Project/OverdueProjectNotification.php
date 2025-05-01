<?php
namespace App\Notifications\Project;

use App\Models\Project;
use App\Notifications\Project\BaseProjectNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OverdueProjectNotification extends BaseProjectNotification
{
    protected string $project_deadline;
    protected string $client_fullname;
    protected string $client_url;

    public function __construct(protected Project $project)
    {
        $client = $project->client; 
    
        $this->client_fullname = $client
            ? $client->first_name . ' ' . $client->last_name
            : 'Unknown Client';
    
        $this->client_url = $client
            ? route('client.show', ['client' => $client->id])
            : '#';
    
        $this->project_deadline = $project->deadline;
    
        parent::__construct($project, null, $project->user);
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Czas na realizację projektu minął!')
            ->greeting('Czas na realizację projektu minął!')
            ->line("**Tytuł projektu:** [{$this->project_title}]({$this->project_url})")
            ->line("**Ostateczny czas realizacji:** {$this->project_deadline}")
            ->line("**Klient:** [{$this->client_fullname}]({$this->client_url})")
            ->line('')
            ->line('🛠️ **Co należy zrobić:**')
            ->line('- **Skontaktuj się z klientem** i ustal nowy termin realizacji projektu.')
            ->line('- **Zaktualizuj termin realizacji** w systemie, edytując szczegóły projektu.')
            ->line('')
            ->line("_Jeśli nie zaktualizujesz terminu, jutro otrzymasz kolejne przypomnienie.⚠️_");
    }

    public function toArray($notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
            //
        ]);
    }

    public function buildMessage(): string
    {
        return __("projects.notifications.overdue.message", [
            'project_title' => $this->project_title,
            'project_url' => $this->project_url,
            'project_deadline' => $this->project->deadline,
        ]);
    }
}

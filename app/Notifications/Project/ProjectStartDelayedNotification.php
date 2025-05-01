<?php
namespace App\Notifications\Project;

use App\Models\Project;
use App\Notifications\Project\BaseProjectNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectStartDelayedNotification extends BaseProjectNotification
{
    protected string $project_start;
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
        
        $this->project_start = $this->project->start;

        parent::__construct($project, null, $project->user);
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Projekt nie wystartował w wyznaczonym terminie!')
            ->greeting('Projekt nie wystartował w wyznaczonym terminie!')
            ->line("**Tytuł projektu:** [{$this->project_title}]({$this->project_url})")
            ->line("**Planowany start:** {$this->project_start}")
            ->line("**Klient:** [{$this->client_fullname}]({$this->client_url})")
            ->line('')
            ->line('🛠️ **Co należy zrobić:**')
            ->line('- Skontaktuj się z klientem i potwierdź powód opóźnienia.')
            ->line('- Zaktualizuj datę rozpoczęcia projektu w systemie, jeśli jest już znana.')
            ->line('- Zmień status projektu, jeśli prace już się rozpoczęły.')
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
        return __("projects.notifications.unstarted.message", [
            'project_title' => $this->project_title,
            'project_url' => $this->project_url,
            'project_start' => $this->project->deadline,
        ]);
    }
}

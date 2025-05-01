<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UnstartedProjectNotificationService;

class NotifyUnstartedProjects extends Command
{
    protected $signature = 'projects:notify-unstarted';
    protected $description = 'Send notifications about unstarted projects after planned start time';

    public function handle()
    {
        app(UnstartedProjectNotificationService::class)->handle();

        $this->info('Unstarted project notifications sent.');
    }
}
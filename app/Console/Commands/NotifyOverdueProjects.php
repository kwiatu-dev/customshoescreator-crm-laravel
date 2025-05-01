<?php

namespace App\Console\Commands;

use App\Services\OverdueNotificationService;
use Illuminate\Console\Command;

class NotifyOverdueProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:notify-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications about overdue projects';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(OverdueNotificationService::class)->handle();

        $this->info('Overdue project notifications sent.');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\TestJob;

class DispatchTestJobs extends Command
{
    /**
     * Nazwa i sygnatura komendy.
     *
     * @var string
     */
    protected $signature = 'dispatch:testjobs {count=1}';

    /**
     * Opis komendy.
     *
     * @var string
     */
    protected $description = 'Dodaje określoną liczbę testowych zadań do kolejki';

    /**
     * Wykonanie komendy.
     *
     * @return void
     */
    public function handle()
    {
        $count = $this->argument('count');

        for ($i = 0; $i < $count; $i++) {
            TestJob::dispatch();
        }

        $this->info("Dodano {$count} zadań do kolejki.");
    }
}

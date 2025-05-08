<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TestUserCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:user-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test cache tags for users and check if flush works';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Set test cache
        Cache::tags(['users'])->put('test_user_cache', 'some value', now()->addMinutes(10));
        $this->info('Cache set with value: some value');

        // Read before flush
        $valueBefore = Cache::tags(['users'])->get('test_user_cache');
        Log::info('Before flush: ' . $valueBefore);
        $this->info('Before flush: ' . $valueBefore);

        // Flush cache
        Cache::tags(['users'])->flush();

        // Read after flush
        $valueAfter = Cache::tags(['users'])->get('test_user_cache');
        Log::info('After flush: ' . ($valueAfter ?? 'null'));
        $this->info('After flush: ' . ($valueAfter ?? 'null'));

        return 0;
    }
}

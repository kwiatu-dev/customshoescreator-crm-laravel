<?php

namespace App\Console\Commands;

use App\Services\CacheService;
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
        $tags = ['users'];
        $args = ['user_id' => 123];
    
        // Pierwsze wywołanie — wykona callback i zapisze wynik
        $value = CacheService::remember($tags, $args, function () {
            return 'cached value';
        });
    
        $this->info('Value cached: ' . $value);
    
        // Odczyt z cache
        $cached = CacheService::remember($tags, $args, function () {
            return 'new value (should not appear)';
        });
    
        $this->info('Value read from cache: ' . $cached);
    
        // Flush tag
        Cache::tags($tags)->flush();
        $this->info('Cache flushed');
    
        // Ponowny zapis po flush
        $newCached = CacheService::remember($tags, $args, function () {
            return 'value after flush';
        });
    
        $this->info('Value after flush: ' . $newCached);
    
        return 0;
    }
}





<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use RuntimeException;

class CacheService {
    private static function cacheTTL() {
        return now()->addHours(24);
    }

    public static function remember(array $tags, array $args, \Closure $callback, $ttl = null)
    {
        $base = self::getCallingContext();
        $key = self::buildCacheKey($base, $args);

        return Cache::tags($tags)->remember($key, $ttl ?? self::cacheTTL(), $callback);
    }

    private static function getCallingContext(): string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
    
        $caller = $trace[2] ?? null;
    
        if ($caller && isset($caller['function'])) {
            return Str::snake($caller['function']);
        }
    
        throw new RuntimeException('Unable to determine calling context for cache key generation.');
    }

    private static function buildCacheKey(string $base, array $args): string
    {
        if (empty($args)) {
            return $base;
        }

        $parts = collect($args)->map(function ($value, $key) {
            $val = is_scalar($value) ? $value : json_encode($value);

            return is_string($key)
                ? Str::slug($key) . '_' . Str::slug((string)$val)
                : Str::slug((string)$val);
        });

        return $base . '_' . $parts->implode('_');
    }
}
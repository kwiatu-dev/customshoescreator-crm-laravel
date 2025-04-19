<?php
namespace App\Services;

use Closure;
use DB;

class ModelAggregatorService {
    private static function generateCacheKey(
        string $model,
        string $method,
        array $conditions,
        Closure|string|null $compute,
        string|\Illuminate\Database\Query\Expression|null $raw = null,
    ): array {
        $normalized = [];
    
        foreach ($conditions as $key => $value) {
            if (is_callable($value)) {
                $normalized[] = 'callback:' . ($value instanceof \Closure ? 'closure' : md5(serialize($value)));
            } elseif (is_array($value)) {
                $normalized[] = "$key:" . implode(':', $value);
            } else {
                $normalized[] = "$key:$value";
            }
        }
    
        $computeKey = match (true) {
            is_string($compute) => "compute:$compute",
            $compute instanceof \Closure => 'compute:closure',
            is_callable($compute) => 'compute:' . md5(serialize($compute)),
            default => '',
        };
    
        $rawKey = match (true) {
            is_string($raw) => "raw:$raw",
            $raw instanceof \Illuminate\Database\Query\Expression => $raw->getValue(DB::connection()->getQueryGrammar()),
            default => '',
        };

        return [
            'model'  => class_basename($model),
            'method' => $method,
            'hash'   => md5($method . implode('|', $normalized) . $computeKey . $rawKey . $model),
        ];
    }

    public static function getModelData(
        string $model,
        string $method,
        array $tags,
        array $conditions = [],
        string|\Illuminate\Database\Query\Expression|null $raw = null,
        Closure|string|null $compute = null,
    ) {
        return CacheService::remember(
            $tags,
            self::generateCacheKey($model, $method, $conditions, $compute, $raw),
            function () use ($model, $method, $conditions, $raw, $compute) {
                $query = $model::query();
    
                foreach ($conditions as $condition) {
                    if (is_callable($condition)) {
                        $query->where($condition); 
                    } 
                    elseif (count($condition) === 2) {
                        [$column, $value] = $condition;

                        if ($value)
                            $query->where($column, $value);
                    } 
                    elseif (count($condition) === 3) {
                        [$column, $operator, $value] = $condition;

                        if ($value)
                            $query->where($column, $operator, $value);
                    }
                }
    
                return match ($method) {
                    'count' => is_callable($compute) ? $compute($query->count()) : $query->count(),
                    'get'   => is_callable($compute) ? $compute($query->get()) : $query->get(),
                    'sum'   => is_callable($compute) ? $compute($query->sum($raw)) : $query->sum($raw),
                    'sql' => $query->toSql(),
                    default => throw new \InvalidArgumentException("Unsupported method: $method")
                };
            }
        );
    }
}


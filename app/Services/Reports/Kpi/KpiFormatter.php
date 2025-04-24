<?php
namespace App\Services\Reports\Kpi;

class KpiFormatter
{
    const FINANCIAL_SECTIONS = ['income', 'expenses', 'profit', 'earnings'];
    const PROJECT_SECTIONS = ['new_projects', 'completed_projects', 'avg_days_projects'];
    const CLIENT_SECTIONS = ['new_clients', 'returning_clients'];

    public function format(array $current, array $previous): array
    {
        $data = [
            'financial' => 
                $this->section(KpiFormatter::FINANCIAL_SECTIONS, $current, $previous),
            'projects' => 
                array_merge(
                    $this->section(KpiFormatter::PROJECT_SECTIONS, $current, $previous),
                    ['types' => $this->projectTypes($current, $previous)],
                ),
            'clients' => 
                $this->section(KpiFormatter::CLIENT_SECTIONS, $current, $previous),
            
        ];

        return $data;
    }

    private function section(array $keys, array $current, array $previous): array
    {
        return collect($keys)
            ->filter(function ($key) use ($current, $previous) {
                return array_key_exists($key, $current) && array_key_exists($key, $previous);
            })
            ->mapWithKeys(function ($key) use ($current, $previous) {
                $parts = explode('_', $key);

                if (count($parts) > 1) {
                    array_pop($parts);
                }

                $shortKey = implode('_', $parts);

                return [
                    $shortKey => $this->formatItem($current[$key], $previous[$key]),
                ];
            })
            ->toArray();
    }

    private function formatItem($current, $previous): array
    {
        return [
            'value' => round($current, 2),
            'arrow' => $current >= $previous ? 'up' : 'down',
            'percentage' => $this->percentageChange($current, $previous),
        ];
    }

    private function projectTypes(array $current, array $previous): array
    {
        $result = [];
    
        foreach ($current as $key => $value) {
            if (strpos($key, 'project_type') !== false && isset($previous[$key])) {
                $result[str_replace('project_type:', '', $key)] = $this->formatItem($current[$key], $previous[$key]);
            }
        }
    
        return $result;
    }

    private function percentageChange($current, $previous): int
    {
        if ($previous == 0) return $current == 0 ? 0 : 100;
        return (int) floor(abs(($current - $previous) / abs($previous)) * 100);
    }
}

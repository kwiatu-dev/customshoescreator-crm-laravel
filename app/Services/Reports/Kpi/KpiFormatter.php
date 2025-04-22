<?php
namespace App\Services\Reports\Kpi;

namespace App\Services\Kpi;

class KpiFormatter
{
    public const PROJECT_TYPES = [
        1 => 'renowacja-butow',
        2 => 'personalizacja-butow',
        3 => 'personalizacja-ubran',
        4 => 'haft-reczny',
        5 => 'haft-komputerowy',
        6 => 'inne',
    ];

    public function format(array $current, array $previous): array
    {
        return [
            'financial' => $this->section(['income', 'expenses', 'profit'], $current, $previous),
            'projects' => array_merge(
                $this->section(['new_projects', 'completed_projects', 'avg_days_projects'], $current, $previous),
                ['types' => $this->projectTypes($current, $previous)]
            ),
            'clients' => $this->section(['new_clients', 'returning_clients'], $current, $previous),
        ];
    }

    private function section(array $keys, array $current, array $previous): array
    {
        return collect($keys)->mapWithKeys(function ($key) use ($current, $previous) {
            return [
                str_replace(['new_', '_projects', '_clients'], ['new', '', ''], $key) => $this->formatItem($current[$key], $previous[$key]),
            ];
        })->toArray();
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
        foreach (self::PROJECT_TYPES as $id => $slug) {
            $key = "project_type:$slug";
            $result[$slug] = $this->formatItem($current[$key], $previous[$key]);
        }
        return $result;
    }

    private function percentageChange($current, $previous): int
    {
        if ($previous == 0) return $current == 0 ? 0 : 100;
        return (int) floor(abs(($current - $previous) / abs($previous)) * 100);
    }
}

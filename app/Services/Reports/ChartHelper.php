<?php
namespace App\Services\Reports;

use Closure;
use DB;

class ChartHelper
{
    public static function getMonthLabels(): array
    {
        return [
            'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec',
            'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'
        ];
    }

    public static function getMonthlyAggregatedData(
        string $table, 
        string $dateColumn, 
        int $year, 
        ?string $groupByField = null, 
        ?Closure $filter = null, 
        string $aggregate = 'COUNT(*)', 
        string $alias = 'count'): array
    {
        $data = array_fill(0, 12, 0);

        $column = '"' . $dateColumn . '"';

        $query = DB::table($table)
            ->selectRaw("EXTRACT(MONTH FROM {$column}) AS month, {$aggregate} AS {$alias}")
            ->whereNull('deleted_at')
            ->whereRaw("EXTRACT(YEAR FROM {$column}) = ?", [$year]);

        if ($filter) {
            $query->where($filter);
        }

        $query->groupBy(DB::raw("EXTRACT(MONTH FROM {$column})"));

        $rows = $query->get();

        foreach ($rows as $row) {
            $index = (int) $row->month - 1;
            $data[$index] = round((float) $row->{$alias}, 2);
        }

        return $data;
    }
}
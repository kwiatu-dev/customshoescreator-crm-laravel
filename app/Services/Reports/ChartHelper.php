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

        $query = DB::table($table)
            ->selectRaw("MONTH($dateColumn) as month, $aggregate as $alias")
            ->whereNull('deleted_at')
            ->whereYear($dateColumn, $year);

        if ($filter) {
            $query = $query->where($filter);
        }

        $query->groupBy(DB::raw("MONTH($dateColumn)"));

        $rows = $query->get();

        foreach ($rows as $row) {
            $index = (int)$row->month - 1;
            $data[$index] = round((float)$row->$alias, 2);
        }

        return $data;
    }
}
<?php
namespace App\Services\Reports;

use App\Models\Income;
use DB;

class IncomeReportService {
    public function getIncomeYears($user_id = null) {
        $years = Income::query()
            ->where('status_id', Income::STATUS_SETTLED)
            ->when($user_id, function ($query, $user_id) {
                return $query->relatedIncome($user_id);
            })
            ->distinct()
            ->selectRaw('YEAR(created_at) as year')
            ->orderBy('year')
            ->pluck('year');

        return $years;
    }

    public function getMonthlyIncome(int $year): array
    {
        $data = ChartHelper::getMonthlyAggregatedData(
            'incomes', 
            'date', 
            $year, 
            null, 
            fn ($query) => $query->where('status_id', Income::STATUS_SETTLED), 
            'ROUND(SUM(price), 2)', 
            'income');

        return 
        
        [
            'labels' => ChartHelper::getMonthLabels(),
            'data' => $data,
        ];
    }
}
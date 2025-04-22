<?php
namespace App\Services;

class ExpensesReportService {
    public function getMonthlyCosts(int $year): array
    {
        $data = ChartHelper::getMonthlyAggregatedData(
            'expenses', 'date', $year, null, null, 'ROUND(SUM(price), 2)', 'costs');

        return [
            'labels' => ChartHelper::getMonthLabels(),
            'data' => $data,
        ];
    }
}
<?php
namespace App\Services\Reports;

use Auth;

class FinancialReportService {
    public function __construct(
        private IncomeReportService $incomeReportService,
        private ExpensesReportService $expensesReportService,
        private UserReportService $userReportService
    ) {}

    public function getMonthlyFinancialStats(int $year, ?int $user_id = null) {
        $auth = Auth::user();

        $monthlyIncomeData = $user_id
            ? $this->userReportService->getMonthlyGeneratedIncomeByUserId($year, $user_id)
            : $this->incomeReportService->getMonthlyIncome($year)['data'];

        $monthlyExpensesData = $user_id
            ? null
            : $this->expensesReportService->getMonthlyCosts($year)['data'];

        $monthlyProfitData = $user_id
            ? null
            : $this->calculateMonthlyProfit($monthlyIncomeData, $monthlyExpensesData);

        $monthlyEarningsData = $user_id
            ? $this->userReportService->getMonthlyEarningsForUser($year, $user_id)
            : null;

        $datasets = [];

        if ($monthlyIncomeData && $auth?->is_admin) {
            $datasets[] = $this->makeDataset('Przychód', $monthlyIncomeData);
        }

        if ($monthlyExpensesData) {
            $datasets[] = $this->makeDataset('Wydatki', $monthlyExpensesData);
        }

        if ($monthlyProfitData) {
            $datasets[] = $this->makeDataset('Dochód', $monthlyProfitData);
        }

        if ($monthlyEarningsData) {
            $datasets[] = $this->makeDataset('Zarobki', $monthlyEarningsData);
        }

        return [
            'labels' => ChartHelper::getMonthLabels(),
            'datasets' => $datasets,
        ];
    }

    private function calculateMonthlyProfit(array $monthlyIncomeData, array $monthlyExpensesData): array
    {
        return collect(ChartHelper::getMonthLabels())
            ->mapWithKeys(function ($month, $index) use ($monthlyIncomeData, $monthlyExpensesData) {
                $income = $monthlyIncomeData[$index] ?? 0;
                $expense = $monthlyExpensesData[$index] ?? 0;
                return [$month => round($income - $expense, 2)];
            })
            ->toArray();
    }

    private function makeDataset(string $label, array $data): array
    {
        return [
            'label' => $label,
            'data' => $data,
        ];
    }
}
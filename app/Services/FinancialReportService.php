<?php
namespace App\Services;

use Auth;

class FinancialReportService {
    public function __construct(
        private IncomeReportService $incomeReportService,
        private ExpensesReportService $expensesReportService,
        private UserReportService $userReportService
    ) {}

    public function getMonthlyFinancialStats(int $year, ?int $user_id = null) {
        $auth = Auth::user();

        $incomeData = $user_id
            ? $this->userReportService->getMonthlyGeneratedIncomeByUserId($year, $user_id)
            : $this->incomeReportService->getMonthlyIncome($year);

        $expensesData = $user_id
            ? null
            : $this->expensesReportService->getMonthlyCosts($year);

        $profitData = $user_id
            ? null
            : $this->calculateMonthlyProfit($incomeData, $expensesData);

        $earningData = $user_id
            ? $this->userReportService->getMonthlyEarningsForUser($year, $user_id)
            : null;

        $datasets = [];

        if ($incomeData && $auth?->is_admin) {
            $datasets[] = $this->makeDataset('Przychód', $incomeData);
        }

        if ($expensesData) {
            $datasets[] = $this->makeDataset('Wydatki', $expensesData);
        }

        if ($profitData) {
            $datasets[] = $this->makeDataset('Dochód', $profitData);
        }

        if ($earningData) {
            $datasets[] = $this->makeDataset('Zarobki', $earningData);
        }

        return [
            'labels' => ChartHelper::getMonthLabels(),
            'datasets' => $datasets,
        ];
    }

    private function calculateMonthlyProfit(array $incomes, array $costs): array
    {
        return array_map(fn($income, $cost) => round($income - $cost, 2), $incomes, $costs);
    }

    private function makeDataset(string $label, array $data): array
    {
        return [
            'label' => $label,
            'data' => $data,
        ];
    }
}
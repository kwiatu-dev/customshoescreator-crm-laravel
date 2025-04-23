<?php
namespace App\Services;

use App\Models\Income;
use App\Models\User;

class UserReportService {
    public function getMonthlyGeneratedIncomeByUserId($year, $user_id): array {
        return ChartHelper::getMonthlyAggregatedData(
            table: 'incomes',
            dateColumn: 'date',
            year: $year,
            filter: function ($query) use ($user_id) {
                $query
                    ->where('status_id', Income::STATUS_SETTLED)
                    ->userHasIncome($user_id);
            },
            aggregate: 'SUM(price * (costs / 100))',
            alias: 'income'
        );
    }

    public function getMonthlyEarningsForUser(int $year, $user_id): array
    {
        $data = array_fill(0, 12, 0);

        $incomes = Income::select('date', 'price', 'costs', 'commission', 'distribution')
            ->where('status_id', Income::STATUS_SETTLED)
            ->whereYear('date', $year)
            ->userHasIncome($user_id)
            ->get();

        foreach ($incomes as $income) {
            $earnings = $income->calculateEarnings($user_id);
            $month = (int) date('n', strtotime($income->date)) - 1;
            $data[$month] += $earnings;
        }

        return array_map(fn($v) => round($v, 2), $data);
    }

    public static function getTopUsersByIncome($limit, $from, $to) {
        $users = User::query()
            ->whereHas('incomes', function ($query) use ($from, $to) {
                $query->whereNull('deleted_at')
                    ->where('status_id', Income::STATUS_SETTLED)
                    ->whereNotNull('project_id'); 

                if (!is_null($from)) {
                    $query->where('date', '>=', $from);
                }

                if (!is_null($to)) {
                    $query->where('date', '<=', $to);
                }
            })
            ->with(['incomes' => function ($query) use ($from, $to) {
                $query->whereNull('deleted_at')
                    ->where('status_id', Income::STATUS_SETTLED)
                    ->whereNotNull('project_id')
                    ->with(['project' => function ($query) {
                        $query->whereNull('deleted_at')
                            ->whereNotNull('end')
                            ->select('id', 'start', 'end');
                    }]);

                if (!is_null($from)) {
                    $query->where('date', '>=', $from);
                }

                if (!is_null($to)) {
                    $query->where('date', '<=', $to);
                }
            }])
            ->get()
            ->map(function ($user) {
                $totalIncome = $user->incomes->sum('price');

                $projects = $user->incomes
                    ->filter(fn($income) => $income->project && $income->project->start && $income->project->end)
                    ->mapToGroups(fn($income) => [$income->project->id => $income->project]);

                $projectCount = $projects->count();

                $avgCompletionDays = $projects
                    ->map(fn($group) => $group->first()->end->diffInDays($group->first()->start))
                    ->avg();

                return (object)[
                    'id' => $user->id,
                    'full_name' => $user->first_name . ' ' . $user->last_name,
                    'total_income' => round($totalIncome, 2),
                    'project_count' => $projectCount,
                    'avg_completion_days' => round($avgCompletionDays ?? 0, 2),
                ];
            })
            ->sortByDesc('total_income')
            ->take($limit)
            ->values();

        return $users;
    }
}
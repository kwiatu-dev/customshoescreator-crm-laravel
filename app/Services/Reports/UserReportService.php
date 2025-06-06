<?php
namespace App\Services\Reports;

use App\Models\Income;
use App\Models\User;
use Carbon\Carbon;
use Date;
use DateTime;

class UserReportService {
    public function getMonthlyGeneratedIncomeByUserId(int $year, $user_id): array
    {
        $data = array_fill(0, 12, 0);
    
        $incomes = Income::select('date', 'price', 'costs')
            ->where('status_id', Income::STATUS_SETTLED)
            ->whereYear('date', $year)
            ->userHasIncome($user_id)
            ->get();
    
        foreach ($incomes as $income) {
            $generatedIncome = $income->price * ($income->costs / 100);
            $month = (int) date('n', strtotime($income->date)) - 1;
            $data[$month] += $generatedIncome;
        }
    
        return array_map(fn($v) => round($v, 2), $data);
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

    public function getTopUsersByIncome($limit, $from, $to)
    {
        $users = User::query()
            ->whereHas('projectIncomes', function ($query) use ($from, $to) {
                $this->applyIncomeDateFilters($query, $from, $to);
                $query->whereHas('project', fn($q) => $q->whereNotNull('end'));
            })
            ->with([
                'projectIncomes' => function ($query) use ($from, $to) {
                    $this->applyIncomeDateFilters($query, $from, $to);
                    $query->with(['project' => fn($q) => $q
                        ->whereNotNull('end')
                        ->select('id', 'start', 'end')
                    ]);
                }
            ])
            ->get()
            ->map(fn($user) => $this->transformUserIncomeData($user))
            ->sortByDesc('total_income')
            ->take($limit)
            ->values();
    
        return $users;
    }
    
    private function applyIncomeDateFilters($query, $from, $to)
    {
        $query->where('incomes.status_id', Income::STATUS_SETTLED)
              ->whereNotNull('incomes.project_id');
    
        if (!is_null($from)) {
            $query->where('date', '>=', $from);
        }
    
        if (!is_null($to)) {
            $query->where('date', '<=', $to);
        }
    }
    
    private function transformUserIncomeData($user)
    {
        $totalIncome = $user->projectIncomes->sum('price');
    
        $projects = $user->projectIncomes
            ->filter(fn($income) => $income->project && $income->project->start && $income->project->end)
            ->mapToGroups(fn($income) => [$income->project->id => $income->project]);
    
        $projectCount = $projects->count();
    
        $avgCompletionDays = $projects
            ->map(fn($group) => Carbon::parse($group->first()->end)
                ->diffInDays(Carbon::parse($group->first()->start)))
            ->avg();
    
        return (object)[
            'id' => $user->id,
            'full_name' => $user->first_name . ' ' . $user->last_name,
            'total_income' => round($totalIncome, 2),
            'project_count' => $projectCount,
            'avg_completion_days' => round($avgCompletionDays ?? 0, 2),
        ];
    }
}
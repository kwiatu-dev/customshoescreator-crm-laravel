<?php
namespace App\Services\Reports\Kpi;

use App\Models\Expenses;
use App\Models\Income;
use App\Models\Project;
use App\Models\Client;
use Auth;
use Carbon\Carbon;

class KpiDataFetcher
{
    public function fetch(Carbon $from, Carbon $to, ?int $user_id = null): array
    {
        $data = [];

        $data = array_merge($data, $this->getFinancialData($from, $to, $user_id));
        $data = array_merge($data, $this->getProjectData($from, $to, $user_id));
        $data = array_merge($data, $this->getClientData($from, $to, $user_id));
        $data = array_merge($data, $this->getProjectTypeData($from, $to, $user_id));

        return $data;
    }

    private function getFinancialData(
        Carbon $from, 
        Carbon $to, 
        ?int $user_id): array
    {
        $auth = Auth::user();
        $data = [];

        $incomeSum = $auth?->is_admin ? $this->getIncomeSum($from, $to, $user_id) : null;
        $expenseSum = $user_id ? null : $this->getExpenseSum($from, $to);
        $earningSum = $user_id ? $this->getEarningSum($from, $to, $user_id) : null;
        $profitSum = $user_id ? null : $this->calculateProfit($incomeSum, $expenseSum, $user_id);

        if ($incomeSum) {
            $data['income'] = $incomeSum;
        }

        if ($expenseSum) {
            $data['expenses'] = $expenseSum;
        }

        if ($profitSum) {
            $data['profit'] = $profitSum;
        }

        if ($earningSum) {
            $data['earnings'] = $earningSum;
        }

        return $data;
    }

    private function getProjectData(Carbon $from, Carbon $to, ?int $user_id): array
    {
        $data = [];

        $data['new_projects'] = Project::whereBetween('created_at', [$from, $to])
            ->when($user_id, fn ($query) => $query->where('created_by_user_id', $user_id))
            ->count();
        $data['completed_projects'] = Project::whereBetween('end', [$from, $to])
            ->when($user_id, fn ($query) => $query->where('created_by_user_id', $user_id))
            ->count();
        $data['avg_days_projects'] = Project::whereBetween('end', [$from, $to])
            ->when($user_id, fn ($query) => $query->where('created_by_user_id', $user_id))
            ->avg(\DB::raw('DATEDIFF(end, start)'));

        return $data;
    }

    private function getClientData(Carbon $from, Carbon $to, ?int $user_id): array
    {
        $data = [];

        $data['new_clients'] =  Client::whereBetween('created_at', [$from, $to])
            ->when($user_id, fn ($query) => $query->where('created_by_user_id', $user_id))
            ->count();

        if (is_null($user_id)) {
            $data['returning_clients'] = Client::whereHas('projects', function ($query) use ($from, $to) {
                    $query->whereBetween('created_at', [$from, $to]);
                })
                ->whereHas('projects', function ($query) use ($from) {
                    $query->where('end', '<', $from);
                })
                ->count();
        }

        return $data;
    }

    private function getProjectTypeData(Carbon $from, Carbon $to, ?int $user_id): array
    {
        $projectTypes = Project::select('type_id')
            ->whereBetween('created_at', [$from, $to])
            ->distinct()
            ->get();

        $data = [];

        foreach ($projectTypes as $type) {
            $slug = $this->slugify($type->name);

            if ($slug) {
                $data["project_type:$slug"] = Project::where('type_id', $type->id)
                    ->whereBetween('created_at', [$from, $to])
                    ->when($user_id, fn ($query) => $query->where('created_by_user_id', $user_id))
                    ->count();
            }
        }

        return $data;
    }

    private function getIncomeSum(Carbon $from, Carbon $to, ?int $user_id): float
    {
        return Income::query()
            ->whereBetween('date', [$from, $to])
            ->where('status_id', Income::STATUS_SETTLED)
            ->when($user_id, function ($query) use ($user_id) {
                $query->userHasIncome($user_id);
            })
            ->sum('price');
    }

    private function getExpenseSum(Carbon $from, Carbon $to): float
    {
        return Expenses::whereBetween('date', [$from, $to])->sum('price');
    }

    private function getEarningSum(Carbon $from, Carbon $to, int $user_id): float 
    {
        $incomes = Income::query()
            ->whereBetween('date', [$from, $to])
            ->where('status_id', Income::STATUS_SETTLED)
            ->userHasIncome($user_id)
            ->get();

        $total = 0;

        foreach ($incomes as $income) {
            $total += $income->calculateEarnings($user_id);
        }

        return $total;
    }

    private function calculateProfit(float $incomeSum, float $expenseSum): float
    {
        return $incomeSum - $expenseSum;
    }

    private function slugify(string $name): string
    {
        $unaccented = iconv('UTF-8', 'ASCII//TRANSLIT', $name);
        $uppercased = strtolower($unaccented); 
        $underscored = preg_replace('/\s+/', '_', $uppercased); 
        return preg_replace('/[^A-Z0-9_]/', '', $underscored);
    }
}

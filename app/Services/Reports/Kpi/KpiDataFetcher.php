<?php
namespace App\Services\Kpi;

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
        $incomeSum = $this->getIncomeSum($from, $to, $user_id);
        $expenseSum = $this->getExpenseSum($from, $to, $user_id);
        $earningSum = $this->getEarningSum($from, $to, $user_id);
        $profitSum = $this->calculateProfit($incomeSum, $expenseSum, $user_id);


        $data = $this->getFinancialData($from, $to, $user_id, $incomeSum, $expenseSum, $earningSum, $profitSum);
        $data = array_merge($data, $this->getProjectData($from, $to));
        $data = array_merge($data, $this->getClientData($from, $to));
        $data = array_merge($data, $this->getProjectTypeData($from, $to));

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

    private function getExpenseSum(Carbon $from, Carbon $to, ?int $user_id): ?float
    {
        return $user_id ? null : Expenses::whereBetween('date', [$from, $to])->sum('price');
    }

    private function getEarningSum(Carbon $from, Carbon $to, ?int $user_id): ?float 
    {
        if (is_null($user_id)) {
            return null;
        }

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

    private function calculateProfit(float $incomeSum, float $expenseSum, ?int $user_id): ?float
    {
        return $user_id ? null : $incomeSum - $expenseSum;
    }

    private function getFinancialData(
        Carbon $from, 
        Carbon $to, 
        ?int $user_id, 
        float $incomeSum, 
        float $expenseSum, 
        ?float $earningSum,
        ?float $profitSum): array
    {
        $data = [];

        if (Auth::user()?->is_admin && $incomeSum) {
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

    private function getProjectData(Carbon $from, Carbon $to): array
    {
        $data = [];

        $data['new_projects'] = Project::whereBetween('created_at', [$from, $to])->count();
        $data['completed_projects'] = Project::whereBetween('end', [$from, $to])->count();
        $data['avg_days_projects'] = round(Project::whereBetween('end', [$from, $to])->avg(\DB::raw('DATEDIFF(end, start)')), 2);

        return $data;
    }

    private function getClientData(Carbon $from, Carbon $to, ?int $user_id): array
    {
        $data = [];

        $data['new_clients'] =  Client::whereBetween('created_at', [$from, $to])->count();

        if (is_null($user_id)) {
            $data['returning_clients'] = Client::whereHas('projects', function ($query) use ($from, $to) {
                    $query->whereBetween('created_at', [$from, $to]);
                })->whereHas('projects', function ($query) use ($from) {
                    $query->where('end', '<', $from);
                })->count();
        }

        return $data;
    }

    private function getProjectTypeData(Carbon $from, Carbon $to): array
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
                    ->count();
            }
        }

        return $data;
    }

    private function slugify(string $name): string
    {
        $unaccented = iconv('UTF-8', 'ASCII//TRANSLIT', $name);
        $uppercased = strtolower($unaccented); 
        $underscored = preg_replace('/\s+/', '_', $uppercased); 
        return preg_replace('/[^A-Z0-9_]/', '', $underscored);
    }
}

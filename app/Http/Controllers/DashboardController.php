<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Expenses;
use App\Models\Income;
use App\Models\Investment;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    
    public function index(Request $request)
    {
        $metrics = $this->getMetrics();

        return inertia(
            'Dashboard/Index',
            [
                'metrics' => $metrics,
            ]
        );
    }

    public function kpi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => ['required', 'date_format:Y-m'],
            'to'   => ['required', 'date_format:Y-m', 'after_or_equal:from'],
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $fromInput = $request->input('from');
        $toInput = $request->input('to');
    
        $from = Carbon::createFromFormat('Y-m', $fromInput)->startOfMonth()->startOfDay();
        $to = Carbon::createFromFormat('Y-m', $toInput)->endOfMonth()->endOfDay();
    
        $kpi = $this->getKPI($from, $to);
    
        return response()->json($kpi);
    }

    private function getKPI($from, $to)
    {
        $previousPeriod = $this->getPreviousPeriod($from, $to);
        $currentStatus = $this->calculateStatsInPeriod($from, $to);
        $previousStats = $this->calculateStatsInPeriod($previousPeriod['from'], $previousPeriod['to']);

        $getPercentageChange = function ($current, $previous) {
            if ($previous == 0) {
                if ($current == 0) {
                    return 0;
                }
                return $current > 0 ? 100 : -100;
            }
            
            $change = (($current - $previous) / abs($previous)) * 100;
            return (int) floor(abs($change));
        };

        // dd([
        //     'current' => $currentStatus,
        //     'previous' => $previousStats,
        //     'financial' => $this->formatFinancialKPI($currentStatus, $previousStats, $getPercentageChange),
        //     'projects' => $this->formatProjectKPI($currentStatus, $previousStats, $getPercentageChange),
        //     'clients' => $this->formatClientKPI($currentStatus, $previousStats, $getPercentageChange),
        // ]);

        return [
            'financial' => $this->formatFinancialKPI($currentStatus, $previousStats, $getPercentageChange),
            'projects' => $this->formatProjectKPI($currentStatus, $previousStats, $getPercentageChange),
            'clients' => $this->formatClientKPI($currentStatus, $previousStats, $getPercentageChange),
        ];
    }

    private function getPreviousPeriod($from, $to)
    {
        $previousFrom = Carbon::parse($from)->subDays(Carbon::parse($to)->diffInDays($from));
        $previousTo = Carbon::parse($from);

        return ['from' => $previousFrom, 'to' => $previousTo];
    }

    private function calculateStatsInPeriod($from, $to)
    {
        $income = Income::whereBetween('created_at', [$from, $to])->where('status_id', 2)->sum(\DB::raw('price * (costs / 100)'));
        $expenses = Expenses::whereBetween('created_at', [$from, $to])->sum('price');
        $profit = $income - $expenses;

        return [
            'income' => $income,
            'expenses' => $expenses,
            'profit' => $profit,
            'new_projects' => Project::whereBetween('created_at', [$from, $to])->count(),
            'completed_projects' => Project::whereBetween('end', [$from, $to])->count(),
            'avg_days_projects' => round(Project::whereBetween('end', [$from, $to])->avg(\DB::raw('DATEDIFF(end, start)')), 2),
            'new_clients' => Client::whereBetween('created_at', [$from, $to])->count(),
            
            'returning_clients' => Client::whereHas('projects', function ($query) use ($from, $to) {
                $query->whereBetween('created_at', [$from, $to]);
            })->whereHas('projects', function ($query) use ($from) {
                $query->where('end', '<', $from);
            })->count(),

            'project_type:renowacja-butow' => Project::where('type_id', '1')->whereBetween('created_at', [$from, $to])->count(),
            'project_type:personalizacja-butow' => Project::where('type_id', '2')->whereBetween('created_at', [$from, $to])->count(),
            'project_type:personalizacja-ubran' => Project::where('type_id', '3')->whereBetween('created_at', [$from, $to])->count(),
            'project_type:haft-reczny' => Project::where('type_id', '4')->whereBetween('created_at', [$from, $to])->count(),
            'project_type:haft-komputerowy' => Project::where('type_id', '5')->whereBetween('created_at', [$from, $to])->count(),
            'project_type:inne' => Project::where('type_id', '6')->whereBetween('created_at', [$from, $to])->count(),
        ];
    }

    private function formatFinancialKPI($current, $previous, $getPercentageChange)
    {
        return [
            'income' => [
                'value' => round($current['income'], 2),
                'arrow' => $current['income'] >= $previous['income'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['income'], $previous['income']),
            ],
            'expenses' => [
                'value' => round($current['expenses'], 2),
                'arrow' => $current['expenses'] >= $previous['expenses'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['expenses'], $previous['expenses']),
            ],
            'profit' => [
                'value' => round($current['profit'], 2),
                'arrow' => $current['profit'] >= $previous['profit'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['profit'], $previous['profit']),
            ],
        ];
    }

    private function formatProjectKPI($current, $previous, $getPercentageChange)
    {
        return [
            'new' => [
                'value' => $current['new_projects'],
                'arrow' => $current['new_projects'] >= $previous['new_projects'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['new_projects'], $previous['new_projects']),
            ],
            'completed' => [
                'value' => $current['completed_projects'],
                'arrow' => $current['completed_projects'] >= $previous['completed_projects'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['completed_projects'], $previous['completed_projects']),
            ],
            'avg_days' => [
                'value' => $current['avg_days_projects'],
                'arrow' => $current['avg_days_projects'] >= $previous['avg_days_projects'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['avg_days_projects'], $previous['avg_days_projects']),
            ],
            'types' => $this->formatProjectTypesKPI($current, $previous, $getPercentageChange),
        ];
    }

    private function formatClientKPI($current, $previous, $getPercentageChange)
    {
        return [
            'new' => [
                'value' => $current['new_clients'],
                'arrow' => $current['new_clients'] >= $previous['new_clients'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['new_clients'], $previous['new_clients']),
            ],
            'returning' => [
                'value' => $current['returning_clients'],
                'arrow' => $current['returning_clients'] >= $previous['returning_clients'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['returning_clients'], $previous['returning_clients']),
            ],
        ];
    }

    private function formatProjectTypesKPI($current, $previous, $getPercentageChange)
    {
        return [
            'renowacja-butow' => [
                'value' => $current['project_type:renowacja-butow'],
                'arrow' => $current['project_type:renowacja-butow'] >= $previous['project_type:renowacja-butow'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['project_type:renowacja-butow'], $previous['project_type:renowacja-butow']),
            ],
            'personalizacja-butow' => [
                'value' => $current['project_type:personalizacja-butow'],
                'arrow' => $current['project_type:personalizacja-butow'] >= $previous['project_type:personalizacja-butow'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['project_type:personalizacja-butow'], $previous['project_type:personalizacja-butow']),
            ],
            'personalizacja-ubran' => [
                'value' => $current['project_type:personalizacja-ubran'],
                'arrow' => $current['project_type:personalizacja-ubran'] >= $previous['project_type:personalizacja-ubran'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['project_type:personalizacja-ubran'], $previous['project_type:personalizacja-ubran']),
            ],
            'haft-reczny' => [
                'value' => $current['project_type:haft-reczny'],
                'arrow' => $current['project_type:haft-reczny'] >= $previous['project_type:haft-reczny'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['project_type:haft-reczny'], $previous['project_type:haft-reczny']),
            ],
            'haft-komputerowy' => [
                'value' => $current['project_type:haft-komputerowy'],
                'arrow' => $current['project_type:haft-komputerowy'] >= $previous['project_type:haft-komputerowy'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['project_type:haft-komputerowy'], $previous['project_type:haft-komputerowy']),
            ],
            'inne' => [
                'value' => $current['project_type:inne'],
                'arrow' => $current['project_type:inne'] >= $previous['project_type:inne'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['project_type:inne'], $previous['project_type:inne']),
            ],
        ];
    }

    private function getMetrics()
    {
        return [
            'total_projects_count' => $this->getTotalProjectsCount(),
            'total_clients_count' => $this->getTotalClientsCount(),
            'total_users_count' => $this->getTotalUsersCount(),
            'total_income_count' => $this->getTotalIncomeCount(),
            'total_expenses_count' => $this->getTotalExpensesCount(),
            'total_expenses_sum' => $this->getTotalExpensesSum(),
            'total_gross_income_sum' => $this->getTotalGrossIncomeSum(),
            'total_net_income_sum' => $this->getTotalNetIncomeSum(),
            'total_investments_sum' => $this->getTotalInvestmentsSum(),
            'total_investments_count' => $this->getTotalInvestmentsCount(),
            'total_active_investments_count' => $this->getTotalActiveInvestmentsCount(),
            'total_after_date_investments_count' => $this->getTotalAfterDateInvestmentsCount(),
            'total_completed_investments_count' => $this->getTotalCompletedInvestmentsCount(),
            'total_awaiting_repayment_sum' => $this->getTotalAwaitingRepaymentSum(),
            'total_awaiting_projects_count' => $this->getTotalAwaitingProjectsCount(),
            'total_in_progress_projects_count' => $this->getTotalInProgressProjectsCount(),
            'total_after_deadline_projects_count' => $this->getTotalAfterDeadlineProjectsCount(),
            'total_completed_projects_count' => $this->getTotalcompleted_projectsCount(),
            'total_active_income_count' => $this->getTotalActiveIncomeCount(),
            'total_completed_income_count' => $this->getTotalCompletedIncomeCount(),
            'total_awaiting_income_sum' => $this->getTotalAwaitingIncomeSum(),
            'wallet' => $this->getWallet(),
        ];
    }

    private function getTotalProjectsCount()
    {
        return Cache::remember(config('cache_keys.total_projects_count'), now()->addHours(24), function () {
            return Project::count();
        });
    }

    private function getTotalClientsCount()
    {
        return Cache::remember(config('cache_keys.total_clients_count'), now()->addHours(24), function () {
            return Client::count();
        });
    }

    private function getTotalUsersCount()
    {
        return Cache::remember(config('cache_keys.total_users_count'), now()->addHours(24), function () {
            return User::count();
        });
    }

    private function getTotalIncomeCount()
    {
        return Cache::remember(config('cache_keys.total_income_count'), now()->addHours(24), function () {
            return Income::count();
        });
    }

    private function getTotalExpensesCount()
    {
        return Cache::remember(config('cache_keys.total_expenses_count'), now()->addHours(24), function () {
            return Expenses::count();
        });
    }

    private function getTotalExpensesSum()
    {
        return Cache::remember(config('cache_keys.total_expenses_sum'), now()->addHours(24), function () {
            return round(Expenses::sum('price'), 2);
        });
    }

    private function getTotalGrossIncomeSum()
    {
        return Cache::remember(config('cache_keys.total_gross_income_sum'), now()->addHours(24), function () {
            return round(Income::sum('price'), 2);
        });
    }

    private function getTotalNetIncomeSum()
    {
        return Cache::remember(config('cache_keys.total_net_income_sum'), now()->addHours(24), function () {
            return round(Income::sum(\DB::raw('price * (costs / 100)')), 2);
        });
    }

    private function getTotalInvestmentsSum()
    {
        return Cache::remember(config('cache_keys.total_investments_sum'), now()->addHours(24), function () {
            return round(Investment::sum('amount'), 2);
        });
    }

    private function getTotalInvestmentsCount()
    {
        return Cache::remember(config('cache_keys.total_investments_count'), now()->addHours(24), function () {
            return Investment::count();
        });
    }

    private function getTotalActiveInvestmentsCount()
    {
        return Cache::remember(config('cache_keys.total_active_investments_count'), now()->addHours(24), function () {
            return Investment::where('status_id', '1')->count();
        });
    }

    private function getTotalAfterDateInvestmentsCount()
    {
        return Cache::remember(config('cache_keys.total_after_date_investments_count'), now()->addHours(24), function () {
            return Investment::where('status_id', '1')->where('date', '<', now())->count();
        });
    }

    private function getTotalCompletedInvestmentsCount()
    {
        return Cache::remember(config('cache_keys.total_completed_investments_count'), now()->addHours(24), function () {
            return Investment::where('status_id', '2')->count();
        });
    }

    private function getTotalAwaitingRepaymentSum()
    {
        return Cache::remember(config('cache_keys.total_awaiting_repayment_sum'), now()->addHours(24), function () {
            return round(Investment::sum(\DB::raw('(amount + amount * (interest_rate / 100)) - total_repayment')), 2);
        });
    }

    private function getTotalAwaitingProjectsCount()
    {
        return Cache::remember(config('cache_keys.total_awaiting_projects_count'), now()->addHours(24), function () {
            return Project::where('status_id', '1')->count();
        });
    }

    private function getTotalInProgressProjectsCount()
    {
        return Cache::remember(config('cache_keys.total_in_progress_projects_count'), now()->addHours(24), function () {
            return Project::where('status_id', '2')->count();
        });
    }

    private function getTotalAfterDeadlineProjectsCount()
    {
        return Cache::remember(config('cache_keys.total_after_deadline_projects_count'), now()->addHours(24), function () {
            return Project::whereNull('end')->where('deadline', '<', now())->count();
        });
    }

    private function getTotalcompleted_projectsCount()
    {
        return Cache::remember(config('cache_keys.total_completed_projects_count'), now()->addHours(24), function () {
            return Project::where('status_id', '3')->count();
        });
    }

    private function getTotalActiveIncomeCount()
    {
        return Cache::remember(config('cache_keys.total_active_income_count'), now()->addHours(24), function () {
            return Income::where('status_id', '1')->count();
        });
    }

    private function getTotalCompletedIncomeCount()
    {
        return Cache::remember(config('cache_keys.total_completed_income_count'), now()->addHours(24), function () {
            return Income::where('status_id', '2')->count();
        });
    }

    private function getTotalAwaitingIncomeSum()
    {
        return Cache::remember(config('cache_keys.total_awaiting_income_sum'), now()->addHours(24), function () {
            return round(Income::where('status_id', '1')->sum(\DB::raw('price * (costs / 100)')), 2);
        });
    }

    private function getTotalRepaymentSum()
    {
        return Cache::remember(config('cache_keys.total_repayment_sum'), now()->addHours(24), function () {
            return round(Investment::sum('total_repayment'), 2);
        });
    }

    private function getWallet()
    {
        $total_net_income_sum = $this->getTotalNetIncomeSum();
        $total_expenses_sum = $this->getTotalExpensesSum();
        $total_investments_sum = $this->getTotalInvestmentsSum();
        $total_repayment_sum = $this->getTotalRepaymentSum();

        return round($total_net_income_sum - $total_expenses_sum + $total_investments_sum - $total_repayment_sum, 2);
    }
}

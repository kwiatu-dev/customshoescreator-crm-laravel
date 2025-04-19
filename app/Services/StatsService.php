<?php
namespace App\Services;

use App\Models\Client;
use App\Models\Expenses;
use App\Models\Income;
use App\Models\Project;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatsService {
    public static function projectYears($user_id = null) {
        $years = DB::table('projects')
            ->whereNull('deleted_at')
            ->whereNotNull('created_at')
            ->when($user_id, function ($query, $user_id) {
                return $query->where('created_by_user_id', $user_id);
            })
            ->select(DB::raw('DISTINCT YEAR(created_at) as year'))
            ->orderBy('year')
            ->pluck('year');
        
        return $years;
    }

    public static function incomeYears($user_id = null) {
        $years = Income::where('status_id', 2)
            ->when($user_id, function ($query, $user_id) {
                return $query->where(function ($query) use ($user_id) {
                    $query->whereHas('project', function ($query) use ($user_id) {
                        $query->where('created_by_user_id', $user_id);
                    })
                    ->orWhereRaw("JSON_EXTRACT(distribution, '$.\"{$user_id}\"') IS NOT NULL");
                });
            })
            ->select(DB::raw('DISTINCT YEAR(created_at) as year'))
            ->orderBy('year')
            ->pluck('year');

        return $years;
    }

    public static function projectTypeBreakdown($year, $user_id = null) {
        $query = DB::table('projects')
            ->select('type_id', DB::raw('COUNT(*) as count'))
            ->whereNull('deleted_at')
            ->whereYear('created_at', $year)
            ->when($user_id, function ($query, $user_id) {
                return $query->where('created_by_user_id', $user_id);
            });


        $rawData = $query
            ->groupBy('type_id')
            ->get()
            ->keyBy('type_id');

        $labels = [
            1 => 'Renowacja butów',
            2 => 'Personalizacja butów',
            3 => 'Personalizacja ubrań',
            4 => 'Haft ręczny',
            5 => 'Haft komputerowy',
            6 => 'Inne',
        ];

        $result = [
            'labels' => array_values($labels),
            'data' => [],
        ];

        foreach ($labels as $typeId => $label) {
            $result['data'][] = isset($rawData[$typeId]) ? (int) $rawData[$typeId]->count : 0;
        }

        return $result;
    }

    public static function monthlyNewProjectsCount($year, $user_id = null) {
        $data = array_fill(0, 12, 0);
    
        $rows = DB::table('projects')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereNull('deleted_at')
            ->whereYear('created_at', $year)
            ->when($user_id, function ($query, $user_id) {
                return $query->where('created_by_user_id', $user_id);
            })
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
    
        foreach ($rows as $row) {
            $index = (int) $row->month - 1;
            $data[$index] = (int) $row->count;
        }

        return [
            'labels' => self::getMonthLabels(),
            'data' => $data,
            'user_id' => $user_id
        ];
    }

    public static function monthlyCompletedProjectsCount($year, $user_id = null) {
        $data = array_fill(0, 12, 0);

        $rows = DB::table('projects')
            ->selectRaw('MONTH(end) as month, COUNT(*) as count')
            ->whereNull('deleted_at')
            ->whereYear('end', $year)
            ->when($user_id, function ($query, $user_id) {
                return $query->where('created_by_user_id', $user_id);
            })
            ->groupBy(DB::raw('MONTH(end)'))
            ->get();

        foreach ($rows as $row) {
            $index = (int) $row->month - 1;
            $data[$index] = (int) $row->count;
        }

        return [
            'labels' => self::getMonthLabels(),
            'data' => $data,
            'user_id' => $user_id
        ];
    }

    public static function monthlyUserIncome($year, $user_id) {
        $data = array_fill(0, 12, 0);

        $incomes = Income::where('status_id', 2)
            ->whereYear('date', $year)
            ->where(function ($query) use ($user_id) {
                $query->whereHas('project', function ($query) use ($user_id) {
                    $query->where('created_by_user_id', $user_id);
                })
                ->orWhereRaw("JSON_EXTRACT(distribution, '$.\"{$user_id}\"') IS NOT NULL");
            })
            ->get();

        foreach ($incomes as $income) {
            $monthIndex = (int) \Carbon\Carbon::parse($income->date)->format('n') - 1;
            $amount = round((float) $income->price * ((int) $income->costs / 100), 2);
            $data[$monthIndex] += $amount;
        }
    
        return $data;
    }

    public static function monthlyFinancialStats($year, $user_id = null) {
        $auth = Auth::user();
        $incomeData = $user_id ? self::monthlyUserIncome($year, $user_id) : self::getMonthlyIncome($year);
        $costsData = $user_id ? null : self::getMonthlyCosts($year);
        $netData = $user_id ? null : self::calculateMonthlyNet($incomeData, $costsData);
        $erningData = $user_id ? self::getMonthlyEarningsForUser($year, $user_id) : null;

        $datasets = [];

        if ($incomeData && $auth?->is_admin) {
            $datasets[] = [
                'label' => 'Przychód',
                'data' => $incomeData,
            ];
        }
    
        if ($costsData) {
            $datasets[] = [
                'label' => 'Wydatki',
                'data' => $costsData,
            ];
        }
    
        if ($netData) {
            $datasets[] = [
                'label' => 'Dochód',
                'data' => $netData,
            ];
        }

        if ($erningData) {
            $datasets[] = [
                'label' => 'Zarobki',
                'data' => $erningData,
            ];
        }
    
        return [
            'labels' => self::getMonthLabels(),
            'datasets' => $datasets,
        ];
    }

    private static function getMonthlyCosts(int $year): array
    {
        $data = array_fill(0, 12, 0);

        $rows = DB::table('expenses')
            ->selectRaw('MONTH(date) as month, SUM(price) as costs')
            ->whereNull('deleted_at')
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'))
            ->get();

        foreach ($rows as $row) {
            $index = (int) $row->month - 1;
            $data[$index] = round((float) $row->costs, 2);
        }

        return $data;
    }

    private static function calculateMonthlyNet(array $income, array $costs): array
    {
        $profit = [];
        for ($i = 0; $i < 12; $i++) {
            $profit[] = round($income[$i] - $costs[$i], 2);
        }
        return $profit;
    }

    private static function getMonthlyIncome(int $year): array
    {
        $data = array_fill(0, 12, 0);

        $rows = DB::table('incomes')
            ->selectRaw('MONTH(date) as month, SUM(price) as income')
            ->whereNull('deleted_at')
            ->whereYear('date', $year)
            ->where('status_id', 2)
            ->groupBy(DB::raw('MONTH(date)'))
            ->get();

        foreach ($rows as $row) {
            $index = (int) $row->month - 1;
            $data[$index] = round((float) $row->income, 2);
        }

        return $data;
    }

    private static function getMonthlyEarningsForUser(int $year, $user_id): array {
        $data = array_fill(0, 12, 0);

        $incomes = Income::where('status_id', 2)
            ->whereYear('date', $year)
            ->where(function ($query) use ($user_id) {
                $query->whereHas('project', function ($query) use ($user_id) {
                    $query->where('created_by_user_id', $user_id);
                })
                ->orWhereRaw("JSON_EXTRACT(distribution, '$.\"{$user_id}\"') IS NOT NULL");
            })
            ->get();

        foreach ($incomes as $income) {
            $price = (float) $income->price;
            $costs = (float) $income->costs;
            $commission = (float) $income->commission;
            $creator = 0;
            $participant = 0;
    
            $base = $price - ($price * ($costs / 100));
    
            if ($commission) {
                $creator = round($base * ($commission / 100), 2);
            }
    
            if (is_array($income->distribution) && array_key_exists($user_id, $income->distribution)) {
                $participant = round(($base - $creator) * (($income->distribution[$user_id] ?? 0) / 100), 2);
            }
    
            $total = $creator + $participant;
    
            $month = (int) date('n', strtotime($income->date)) - 1;
            $data[$month] += $total;
        }
    
        foreach ($data as &$value) {
            $value = round($value, 2);
        }
    
        return $data;
    }

    private static function getMonthLabels(): array
    {
        return [
            'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec',
            'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'
        ];
    }

    public static function topProjectsByIncome($limit, $from, $to) {
        $query = DB::table('projects')
            ->join('incomes', 'projects.id', '=', 'incomes.project_id')
            ->leftJoin('project_images', 'projects.id', '=', 'project_images.project_id')
            ->whereNull('projects.deleted_at')
            ->whereNull('incomes.deleted_at')
            ->where('incomes.status_id', 2)
            ->whereNotNull('projects.end'); 

        if ($from) {
            $query->where('incomes.date', '>=', $from);
        }

        if ($to) {
            $query->where('incomes.date', '<=', $to);
        }

        $topProjects = $query
            ->select(
                'projects.id',
                'projects.title',
                DB::raw('incomes.price as total_income'),
                DB::raw('DATEDIFF(projects.end, projects.start) as duration_days'),
                DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(project_images.file ORDER BY RAND()), ",", 1) as preview_image')
            )
            ->groupBy('projects.id', 'projects.title', 'projects.start', 'projects.end', 'incomes.price')
            ->orderByDesc('total_income')
            ->limit($limit)
            ->get();

        $topProjects->transform(function ($project) {
            if (!empty($project->preview_image)) {
                $project->preview_image_url = route('private.files', [
                    'catalog' => 'projects',
                    'file' => $project->preview_image,
                ]);
            } else {
                $project->preview_image_url = null;
            }
            return $project;
        });

        return $topProjects;
    }

    public static function topUsersByIncome($limit, $from, $to) {
        $query = DB::table('incomes')
            ->join('projects', 'incomes.project_id', '=', 'projects.id')
            ->join('users', 'projects.created_by_user_id', '=', 'users.id')
            ->whereNull('incomes.deleted_at')
            ->whereNull('projects.deleted_at')
            ->where('incomes.status_id', 2)
            ->whereNotNull('projects.end');

        if ($from) {
            $query->where('incomes.date', '>=', $from);
        }

        if ($to) {
            $query->where('incomes.date', '<=', $to);
        }

        $topUsers = $query
            ->select(
                'users.id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"),
                DB::raw('SUM(incomes.price) as total_income'),
                DB::raw('COUNT(DISTINCT projects.id) as project_count'),
                DB::raw('ROUND(AVG(DATEDIFF(projects.end, projects.start)), 2) as avg_completion_days')
            )
            ->groupBy('users.id', 'users.first_name', 'users.last_name')
            ->orderByDesc('total_income')
            ->limit($limit)
            ->get();

        return $topUsers;
    }

    public static function kpi($from, $to, $user_id) {
        $from = Carbon::createFromFormat('Y-m', $from)->startOfMonth()->startOfDay();
        $to = Carbon::createFromFormat('Y-m', $to)->endOfMonth()->endOfDay();
    
        return self::buildKpi($from, $to, $user_id);
    }

    private static function buildKpi($from, $to, $user_id) {
        $previousPeriod = self::getPreviousPeriod($from, $to);
        $currentStatus = self::calculateStatsInPeriod($from, $to, $user_id);
        $previousStats = self::calculateStatsInPeriod($previousPeriod['from'], $previousPeriod['to'], $user_id);

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

        return [
            'financial' => self::formatFinancialKPI($currentStatus, $previousStats, $getPercentageChange),
            'projects' => self::formatProjectKPI($currentStatus, $previousStats, $getPercentageChange),
            'clients' => self::formatClientKPI($currentStatus, $previousStats, $getPercentageChange),
        ];
    }

    private static function getPreviousPeriod($from, $to)
    {
        $previousFrom = Carbon::parse($from)->subDays(Carbon::parse($to)->diffInDays($from));
        $previousTo = Carbon::parse($from);

        return ['from' => $previousFrom, 'to' => $previousTo];
    }

    private static function calculateStatsInPeriod($from, $to, $user_id)
    {
        $auth = Auth::user();
        $income = Income::whereBetween('date', [$from, $to])->where('status_id', 2)->sum(\DB::raw('price'));
        $expenses = Expenses::whereBetween('date', [$from, $to])->sum('price');
        $profit = $income - $expenses;

        return [
            'income' => $income,
            'expenses' => $expenses,
            'profit' => $profit,
            'earnings' => null,
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

    private static function formatFinancialKPI($current, $previous, $getPercentageChange)
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

    private static function formatProjectKPI($current, $previous, $getPercentageChange)
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
            'types' => self::formatProjectTypesKPI($current, $previous, $getPercentageChange),
        ];
    }

    private static function formatClientKPI($current, $previous, $getPercentageChange)
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

    private static function formatProjectTypesKPI($current, $previous, $getPercentageChange)
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
}
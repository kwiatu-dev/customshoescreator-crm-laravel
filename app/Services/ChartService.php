<?php
namespace App\Services;

use App\Models\Income;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChartService {
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

    public static function monthlyFinancialStats($year, $user_id = null) {
        $incomeData = $user_id ? self::getMonthlyUserIncome($year, $user_id) : self::getMonthlyIncome($year);
        $costsData = $user_id ? null : self::getMonthlyCosts($year);
        $netData = $user_id ? null : self::calculateMonthlyNet($incomeData, $costsData);

        $datasets = [];

        if ($incomeData) {
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

    private static function getMonthlyUserIncome(int $year, $user_id = null): array {
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
}
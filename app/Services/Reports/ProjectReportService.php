<?php 
namespace App\Services\Reports;

use App\Models\Income;
use App\Models\Project;
use Carbon\Carbon;
use DB;

class ProjectReportService {
    public function getProjectYears($user_id = null) {
        $years = Project::query()
        ->when($user_id, function ($query, $user_id) {
            return $query->where('created_by_user_id', $user_id);
        })
        ->distinct()
        ->selectRaw('YEAR(created_at) as year')
        ->orderBy('year')
        ->pluck('year');

        return $years;
    }

    public function getProjectTypeBreakdown($year, $user_id = null) {
        $types = DB::table('project_types')
            ->pluck('name', 'id');

        $query = DB::table('projects')
            ->select('type_id', DB::raw('COUNT(*) as count'))
            ->whereNull('deleted_at')
            ->whereYear('created_at', $year)
            ->when($user_id, function ($query, $user_id) {
                return $query->where('created_by_user_id', $user_id);
            });

        $rawData = $query->groupBy('type_id')->get()->keyBy('type_id');

        $result = [
            'labels' => [],
            'data' => [],
        ];

        foreach ($types as $typeId => $typeName) {
            $result['labels'][] = $typeName;
            $result['data'][] = isset($rawData[$typeId]) ? (int) $rawData[$typeId]->count : 0;
        }

        return $result;
    }

    public function getMonthlyNewProjectsCount($year, $user_id = null): array {
        $data = ChartHelper::getMonthlyAggregatedData('projects', 'created_at', $year, null, 
            function ($query) use ($user_id) {
                if ($user_id) {
                    $query->where('created_by_user_id', $user_id);
                }
            }
        );
    
        return [
            'labels' => ChartHelper::getMonthLabels(),
            'data' => $data,
            'user_id' => $user_id
        ];
    }

    public function getMonthlyCompletedProjectsCount($year, $user_id = null) {
        $data = ChartHelper::getMonthlyAggregatedData('projects', 'end', $year, null, 
            function ($query) use ($user_id) {
                if ($user_id) {
                    $query->where('created_by_user_id', $user_id);
                }
            }
        );

        return [
            'labels' => ChartHelper::getMonthLabels(),
            'data' => $data,
            'user_id' => $user_id
        ];
    }

    public function getTopProjectsByIncome($limit, $from, $to)
    {
        $projects = Project::query()
            ->with('previewImage')
            ->whereNotNull('end')
            ->whereHas('income', fn($query) => $this->applyIncomeDateFilters($query, $from, $to))
            ->withSum(['income as total_income' => fn($query) => $this->applyIncomeDateFilters($query, $from, $to)], 'price')
            ->orderByDesc('total_income')
            ->limit($limit)
            ->get();
    
        return $projects->map(fn($project) => $this->transformProjectIncomeData($project));
    }
    
    private function applyIncomeDateFilters($query, $from, $to)
    {
        $query->where('status_id', Income::STATUS_SETTLED);
    
        if (!is_null($from)) {
            $query->where('date', '>=', $from);
        }
    
        if (!is_null($to)) {
            $query->where('date', '<=', $to);
        }
    }

    private function transformProjectIncomeData($project)
    {
        return [
            'id' => $project->id,
            'title' => $project->title,
            'total_income' => round($project->total_income, 2),
            'duration_days' => $project->start && $project->end
                ? Carbon::parse($project->end)->diffInDays(Carbon::parse($project->start))
                : null,
            'preview_image_url' => $project->previewImage
                ? route('private.files', ['catalog' => 'projects', 'file' => $project->previewImage->file])
                : null,
        ];
    }
}
<?php
namespace App\Services\Reports\Kpi;

class KpiReportService
{
    public function __construct(
        protected DatePeriodService $dateService,
        protected KpiCalculator $calculator,
        protected KpiFormatter $formatter
    ) {}

    public function generate(string $from, string $to, ?int $user_id = null): array
    {
        $period = $this->dateService->parsePeriod($from, $to);
        $previous = $this->dateService->previousPeriod($period);

        $current_stats = $this->calculator->calculate($period, $user_id);
        $previous_stats = $this->calculator->calculate($previous, $user_id);

        return $this->formatter->format($current_stats, $previous_stats);
    }
}
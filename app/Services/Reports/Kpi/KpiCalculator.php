<?php 
namespace App\Services\Kpi;

use Carbon\CarbonPeriod;

class KpiCalculator
{
    public function __construct(
        protected KpiDataFetcher $fetcher
    ) {}

    public function calculate(CarbonPeriod $period, int $userId): array
    {
        $from = $period->getStartDate();
        $to = $period->getEndDate();

        return $this->fetcher->fetch($from, $to, $userId);
    }
}
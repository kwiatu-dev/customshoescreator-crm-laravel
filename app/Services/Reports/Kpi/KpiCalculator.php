<?php 
namespace App\Services\Reports\Kpi;

use Carbon\CarbonPeriod;

class KpiCalculator
{
    public function __construct(
        protected KpiDataFetcher $fetcher
    ) {}

    public function calculate(CarbonPeriod $period, ?int $user_id = null): array
    {
        $from = $period->getStartDate();
        $to = $period->getEndDate();

        return $this->fetcher->fetch($from, $to, $user_id = null);
    }
}
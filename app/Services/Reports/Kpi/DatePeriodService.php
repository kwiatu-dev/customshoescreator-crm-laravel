<?php
namespace App\Services\Reports\Kpi;

use Carbon\CarbonPeriod;
use Carbon\Carbon;

class DatePeriodService
{
    public function parsePeriod(string $from, string $to): CarbonPeriod
    {
        return CarbonPeriod::create(
            Carbon::createFromFormat('Y-m', $from)->startOfMonth()->startOfDay(),
            Carbon::createFromFormat('Y-m', $to)->endOfMonth()->endOfDay()
        );
    }

    public function previousPeriod(CarbonPeriod $current): CarbonPeriod
    {
        $diff = $current->start->diffInDays($current->end);
        return CarbonPeriod::create(
            $current->start->copy()->subDays($diff + 1),
            $current->start->copy()->subDay()
        );
    }
}
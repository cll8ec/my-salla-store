<?php

declare(strict_types=1);

namespace App\Domains\Dashboard\Enums;

enum DashboardPeriod: string
{
    case Today = 'today';
    case Yesterday = 'yesterday';
    case Last7Days = 'last_7_days';
    case Last30Days = 'last_30_days';
    case Last90Days = 'last_90_days';
    case ThisMonth = 'this_month';
    case LastMonth = 'last_month';
    case ThisYear = 'this_year';
    case AllTime = 'all_time';

    public function label(): string
    {
        return match ($this) {
            self::Today => 'Today',
            self::Yesterday => 'Yesterday',
            self::Last7Days => 'Last 7 Days',
            self::Last30Days => 'Last 30 Days',
            self::Last90Days => 'Last 90 Days',
            self::ThisMonth => 'This Month',
            self::LastMonth => 'Last Month',
            self::ThisYear => 'This Year',
            self::AllTime => 'All Time',
        };
    }

    public function days(): ?int
    {
        return match ($this) {
            self::Today, self::Yesterday => 1,
            self::Last7Days => 7,
            self::Last30Days => 30,
            self::Last90Days => 90,
            self::AllTime => null,
            default => null,
        };
    }
}

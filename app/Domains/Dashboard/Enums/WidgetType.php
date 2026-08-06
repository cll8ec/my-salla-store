<?php

declare(strict_types=1);

namespace App\Domains\Dashboard\Enums;

enum WidgetType: string
{
    case Stats = 'stats';
    case Chart = 'chart';
    case Recent = 'recent';
    case TopList = 'top_list';
    case Alert = 'alert';

    public function label(): string
    {
        return match ($this) {
            self::Stats => 'Statistics',
            self::Chart => 'Chart',
            self::Recent => 'Recent Activity',
            self::TopList => 'Top List',
            self::Alert => 'Alert',
        };
    }
}

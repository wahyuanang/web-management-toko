<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsTotalRevenue extends StatsOverviewWidget
{
    protected array|int|null $columns = 2;

    protected function getStats(): array

    {

        return [
            Stat::make('Total Pendapatan Hari Ini', 'Rp 150.000.000'),
            Stat::make('Rata-rata Pendapatan Bulanan', 'Rp 150.000.000'),
        ];
    }
}

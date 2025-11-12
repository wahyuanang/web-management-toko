<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsIncomeOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pendapatan Tempe Hari Ini', 'Rp 50.000.000'),
            Stat::make('Pendapatan Tahu Hari Ini', 'Rp 600.000.000'),
            Stat::make('Pendapatan Toge Hari Ini', 'Rp 50.000.000'),
        ];
    }
}

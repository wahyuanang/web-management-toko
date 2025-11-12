<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsDeliveryOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Tahu Dikirim (pcs/bks)', '4567'),
            Stat::make('Total Tempe Dikirim (pcs/bks)', '1,234'),
            Stat::make('Total Toge Dikirim (pcs/bks)', '5678'),
        ];
    }
}

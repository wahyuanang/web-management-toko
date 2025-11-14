<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;


class WidgetIncomeChart extends ChartWidget
{
    protected ?string $heading = 'Pemasukan per Hari';

     protected function getData(): array
    {
        // Ambil filter dari Dashboard
        $startDate = $this->filters['startDate'] ?? now()->startOfMonth();
        $endDate = $this->filters['endDate'] ?? now()->endOfMonth();

        // Ambil data pendapatan per hari dari reports yang assignment-nya done
        $data = Trend::query(
            Report::query()
                ->whereHas('assignment', function($query) {
                    $query->where('status', 'done');
                })
        )
        ->between(
            start: $startDate,
            end: $endDate,
        )
        ->perDay()
        ->dateColumn('waktu_laporan')
        ->sum('total_harga');

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan (Rp)',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => date('d M', strtotime($value->date))),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
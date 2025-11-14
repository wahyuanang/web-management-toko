<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Report;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;


class WidgetExpanseChart extends ChartWidget
{
    protected ?string $heading = 'Penjualan Barang per Hari';

    protected function getData(): array
    {
        // Ambil filter dari Dashboard
        $startDate = $this->filters['startDate'] ?? now()->startOfMonth();
        $endDate = $this->filters['endDate'] ?? now()->endOfMonth();

        // Ambil data penjualan barang per hari dari reports yang assignment-nya done
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
        ->sum('jumlah_barang_dikirim');

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Barang Terjual',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#0BA6DF',
                    'backgroundColor' => 'rgba(140, 228, 255, 0.2)',
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

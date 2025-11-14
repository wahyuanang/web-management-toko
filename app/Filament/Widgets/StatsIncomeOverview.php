<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Report;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class StatsIncomeOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        // Ambil filter dari Dashboard
        $startDate = $this->filters['startDate'] ?? now()->startOfMonth();
        $endDate = $this->filters['endDate'] ?? now()->endOfMonth();

        // Pendapatan Tahu - langsung dari Reports
        $pendapatanTahu = Report::whereBetween('waktu_laporan', [$startDate, $endDate])
            ->whereHas('assignment', function ($query) {
                $query->where('status', 'done')
                    ->whereHas('product', function ($q) {
                        $q->whereRaw('LOWER(nama_barang) like ?', ['%tahu%']);
                    });
            })
            ->sum('total_harga');

        // Pendapatan Tempe - langsung dari Reports
        $pendapatanTempe = Report::whereBetween('waktu_laporan', [$startDate, $endDate])
            ->whereHas('assignment', function ($query) {
                $query->where('status', 'done')
                    ->whereHas('product', function ($q) {
                        $q->whereRaw('LOWER(nama_barang) like ?', ['%tempe%']);
                    });
            })
            ->sum('total_harga');

        // Pendapatan Toge - langsung dari Reports
        $pendapatanToge = Report::whereBetween('waktu_laporan', [$startDate, $endDate])
            ->whereHas('assignment', function ($query) {
                $query->where('status', 'done')
                    ->whereHas('product', function ($q) {
                        $q->whereRaw('LOWER(nama_barang) like ?', ['%toge%']);
                    });
            })
            ->sum('total_harga');

        return [
            Stat::make('Pendapatan Tahu', 'Rp ' . number_format($pendapatanTahu, 0, ',', '.')),
            Stat::make('Pendapatan Tempe', 'Rp ' . number_format($pendapatanTempe, 0, ',', '.')),
            Stat::make('Pendapatan Toge', 'Rp ' . number_format($pendapatanToge, 0, ',', '.')),
        ];
    }
}

<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Report;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class StatsDeliveryOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        // Ambil filter dari Dashboard
        $startDate = $this->filters['startDate'] ?? now()->startOfMonth();
        $endDate = $this->filters['endDate'] ?? now()->endOfMonth();

        // Total Tahu - langsung dari Reports
        $totalTahu = Report::whereBetween('waktu_laporan', [$startDate, $endDate])
            ->whereHas('assignment', function ($query) {
                $query->where('status', 'done')
                    ->whereHas('product', function ($q) {
                        $q->whereRaw('LOWER(nama_barang) like ?', ['%tahu%']);
                    });
            })
            ->sum('jumlah_barang_dikirim');

        // Total Tempe - langsung dari Reports
        $totalTempe = Report::whereBetween('waktu_laporan', [$startDate, $endDate])
            ->whereHas('assignment', function ($query) {
                $query->where('status', 'done')
                    ->whereHas('product', function ($q) {
                        $q->whereRaw('LOWER(nama_barang) like ?', ['%tempe%']);
                    });
            })
            ->sum('jumlah_barang_dikirim');

        // Total Toge - langsung dari Reports
        $totalToge = Report::whereBetween('waktu_laporan', [$startDate, $endDate])
            ->whereHas('assignment', function ($query) {
                $query->where('status', 'done')
                    ->whereHas('product', function ($q) {
                        $q->whereRaw('LOWER(nama_barang) like ?', ['%toge%']);
                    });
            })
            ->sum('jumlah_barang_dikirim');

        return [
            Stat::make('Total Tahu Dikirim (pcs/bks)', number_format($totalTahu, 0, ',', '.')),
            Stat::make('Total Tempe Dikirim (pcs/bks)', number_format($totalTempe, 0, ',', '.')),
            Stat::make('Total Toge Dikirim (pcs/bks)', number_format($totalToge, 0, ',', '.')),
        ];
    }
}

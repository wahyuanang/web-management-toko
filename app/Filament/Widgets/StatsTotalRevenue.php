<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Assignment;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Carbon\Carbon;

class StatsTotalRevenue extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected int|array|null $columns = 2;

    protected function getStats(): array
    {
        // Ambil filter dari Dashboard
        $startDate = $this->filters['startDate'] ?? now()->startOfMonth();
        $endDate = $this->filters['endDate'] ?? now()->endOfMonth();

        // Total pendapatan sesuai filter tanggal
        $totalPendapatan = Assignment::where('status', 'done')
            ->whereHas('reports', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('waktu_laporan', [$startDate, $endDate]);
            })
            ->with(['reports' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('waktu_laporan', [$startDate, $endDate]);
            }])
            ->get()
            ->sum(function ($assignment) {
                return $assignment->reports->sum('total_harga');
            });

        // Format bulan untuk label
        $startCarbon = Carbon::parse($startDate)->startOfDay();
        $endCarbon = Carbon::parse($endDate)->startOfDay();

        // Cek apakah sama bulan atau beda bulan
        if ($startCarbon->format('Y-m') === $endCarbon->format('Y-m')) {
            // Sama bulan
            $periodeLabel = $startCarbon->translatedFormat('F Y');
        } else {
            // Beda bulan
            $periodeLabel = $startCarbon->translatedFormat('F Y') . ' - ' . $endCarbon->translatedFormat('F Y');
        }

        // Hitung rata-rata pendapatan per hari (gunakan diffInDays untuk menghitung hari kalender)
        $jumlahHari = $startCarbon->diffInDays($endCarbon) + 1;
        $rataRataPerHari = $jumlahHari > 0 ? $totalPendapatan / $jumlahHari : 0;

        return [
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-calendar')
                ->description($periodeLabel),
            Stat::make('Rata-rata Pendapatan per Hari', 'Rp ' . number_format($rataRataPerHari, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-chart-bar')
                ->description('Dari ' . number_format($jumlahHari, 0) . ' hari'),
        ];
    }
}

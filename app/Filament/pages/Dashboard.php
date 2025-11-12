<?php
// filepath: c:\laragon\www\web-keuangan\app\Filament\Pages\Dashboard.php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    use BaseDashboard\Concerns\HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        DatePicker::make('startDate')
                            ->label('Tanggal Mulai')
                            ->default(now()->startOfMonth()),
                        DatePicker::make('endDate')
                            ->label('Tanggal Akhir')
                            ->default(now()->endOfMonth()),
                    ])
                    ->columns(2),
            ]);
    }
}
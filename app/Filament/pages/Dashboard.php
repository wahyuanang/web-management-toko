<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Schemas\Schema;

class Dashboard extends BaseDashboard
{
    use BaseDashboard\Concerns\HasFiltersForm;

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        DatePicker::make('startDate')
                            ->label('Tanggal Mulai')
                            ->default(now()->startOfMonth())
                            ->native(false),
                        DatePicker::make('endDate')
                            ->label('Tanggal Akhir')
                            ->default(now()->endOfMonth())
                            ->native(false),
                    ])
                    ->columns(2),
            ]);
    }
}

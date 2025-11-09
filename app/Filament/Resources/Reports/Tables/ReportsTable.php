<?php

namespace App\Filament\Resources\Reports\Tables;

use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class ReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')
                ->label('ID')
                ->sortable(),

            TextColumn::make('assignment.title')
                ->label('Tugas')
                ->sortable()
                ->searchable(),

            TextColumn::make('user.name')
                ->label('Dibuat oleh')
                ->sortable()
                ->searchable(),

            TextColumn::make('jumlah_barang_dikirim')
                ->label('Jumlah Dikirim'),

            TextColumn::make('lokasi')
                ->label('Lokasi')
                ->limit(20),

            ImageColumn::make('foto_bukti')
                ->label('Bukti 1'),

            ImageColumn::make('foto_bukti_2')
                ->label('Bukti 2'),

            TextColumn::make('waktu_laporan')
                ->label('Waktu Laporan')
                ->dateTime('d M Y H:i'),

            TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime('d M Y H:i')
                ->toggleable(isToggledHiddenByDefault: true),
        ]);
    }
}

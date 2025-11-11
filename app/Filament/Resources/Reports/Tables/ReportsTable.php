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
                ->searchable()
                ->description(fn ($record) => 'Status: ' . ucfirst($record->assignment->status)),

            TextColumn::make('user.name')
                ->label('Karyawan')
                ->sortable()
                ->searchable()
                ->badge()
                ->color('info'),

            TextColumn::make('jumlah_barang_dikirim')
                ->label('Jumlah Dikirim')
                ->badge()
                ->color('success')
                ->formatStateUsing(fn ($state) => $state . ' unit'),

            TextColumn::make('harga_per_pcs')
                ->label('Harga/Pcs')
                ->money('IDR')
                ->sortable(),

            TextColumn::make('total_harga')
                ->label('Total Harga')
                ->money('IDR')
                ->sortable()
                ->summarize([
                    \Filament\Tables\Columns\Summarizers\Sum::make()
                        ->money('IDR')
                        ->label('Total Keseluruhan'),
                ]),

            TextColumn::make('assignment.status')
                ->label('Status Tugas')
                ->badge()
                ->color(function ($state) {
                    return match($state) {
                        'done' => 'success',
                        'in_progress' => 'warning',
                        'pending' => 'gray',
                        'cancelled' => 'danger',
                        default => 'gray',
                    };
                })
                ->formatStateUsing(fn ($state) => ucfirst(str_replace('_', ' ', $state))),

            TextColumn::make('lokasi')
                ->label('Lokasi')
                ->limit(30)
                ->tooltip(fn ($record) => $record->lokasi),

            ImageColumn::make('foto_bukti')
                ->label('Bukti 1')
                ->disk('public'),

            ImageColumn::make('foto_bukti_2')
                ->label('Bukti 2')
                ->disk('public'),

            TextColumn::make('waktu_laporan')
                ->label('Waktu Laporan')
                ->dateTime('d M Y H:i')
                ->sortable(),

            TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime('d M Y H:i')
                ->toggleable(isToggledHiddenByDefault: true),
        ]);
    }
}

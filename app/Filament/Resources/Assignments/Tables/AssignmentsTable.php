<?php

namespace App\Filament\Resources\Assignments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssignmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Tugas')
                    ->searchable(),

                TextColumn::make('assignedUser.name')
                    ->label('Karyawan')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('product.nama_barang')
                    ->label('Produk')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('qty_target')
                    ->label('Target Qty')
                    ->sortable(),

                TextColumn::make('total_dikirim')
                    ->label('Total Terkirim')
                    ->getStateUsing(fn ($record) => $record->total_dikirim)
                    ->badge()
                    ->color(fn ($record) => $record->total_dikirim >= $record->qty_target ? 'success' : 'warning'),

                TextColumn::make('progress')
                    ->label('Progress')
                    ->getStateUsing(fn ($record) => number_format($record->progress, 1) . '%')
                    ->badge()
                    ->color(function ($record) {
                        $progress = $record->progress;
                        if ($progress >= 100) return 'success';
                        if ($progress >= 50) return 'warning';
                        return 'danger';
                    }),

                TextColumn::make('priority')
                    ->label('Prioritas')
                    ->badge(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(function ($state) {
                        return match($state) {
                            'done' => 'success',
                            'in_progress' => 'warning',
                            'pending' => 'gray',
                            'cancelled' => 'danger',
                            default => 'gray',
                        };
                    }),

                TextColumn::make('deadline')
                    ->label('Batas Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('createdBy.name')
                    ->label('Dibuat Oleh')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

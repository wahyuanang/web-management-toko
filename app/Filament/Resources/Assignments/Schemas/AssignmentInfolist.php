<?php

namespace App\Filament\Resources\Assignments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AssignmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Judul Tugas'),

                TextEntry::make('description')
                    ->label('Deskripsi')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('assignedUser.name')
                    ->label('Karyawan yang Ditugaskan')
                    ->placeholder('-'),

                TextEntry::make('product.nama_barang')
                    ->label('Produk')
                    ->placeholder('-'),

                TextEntry::make('qty_target')
                    ->label('Target Qty'),

                // 🎯 Priority Badge dengan warna dinamis
                TextEntry::make('priority')
                    ->label('Prioritas')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'low' => 'gray',
                        'medium' => 'warning',
                        'high' => 'danger',
                        default => 'secondary',
                    }),

                // ⚙️ Status Badge dengan warna dinamis
                TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'in_progress' => 'info',
                        'done' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    }),

                TextEntry::make('deadline')
                    ->label('Batas Waktu')
                    ->date()
                    ->placeholder('-'),

                TextEntry::make('createdBy.name')
                    ->label('Dibuat oleh')
                    ->placeholder('-'),

                TextEntry::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->label('Diperbarui pada')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
<?php

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Schema;

class ReportInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('assignment.title')
                ->label('Tugas'),

            TextEntry::make('user.name')
                ->label('Dibuat oleh'),

            TextEntry::make('jumlah_barang_dikirim')
                ->label('Jumlah Dikirim'),

            TextEntry::make('lokasi')
                ->label('Lokasi')
                ->columnSpanFull(),

            TextEntry::make('catatan')
                ->label('Catatan')
                ->placeholder('-')
                ->columnSpanFull(),

            ImageEntry::make('foto_bukti')
                ->label('Foto Bukti'),

            ImageEntry::make('foto_bukti_2')
                ->label('Foto Bukti 2')
                ->placeholder('Tidak ada'),

            TextEntry::make('waktu_laporan')
                ->label('Waktu Laporan')
                ->dateTime(),

            TextEntry::make('created_at')
                ->label('Dibuat pada')
                ->dateTime(),
        ]);
    }
}
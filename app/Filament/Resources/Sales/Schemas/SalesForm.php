<?php

namespace App\Filament\Resources\Sales\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SalesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Nama Karyawan')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Select::make('product_id')
                    ->label('Nama Produk')
                    ->relationship('product', 'nama_barang') 
                    ->searchable()
                    ->required(),
                TextInput::make('qty')
                    ->required()
                    ->numeric(),
                TextInput::make('harga_per_pcs')
                    ->required()
                    ->numeric(),
                TextInput::make('total_harga')
                    ->required()
                    ->numeric(),
                TextInput::make('lokasi_penjualan')
                    ->required(),
                DatePicker::make('tanggal_penjualan')
                    ->required(),
                Textarea::make('note')
                    ->columnSpanFull(),
                Select::make('status_pengiriman')
                    ->options(['pending' => 'Pending', 'proses' => 'Proses', 'diantar' => 'Diantar', 'selesai' => 'Selesai'])
                    ->default('pending')
                    ->required(),
            ]);
    }
}

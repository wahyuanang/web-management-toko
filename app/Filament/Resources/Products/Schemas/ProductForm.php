<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_barang')
                    ->label('Nama Barang')
                    ->required()
                    ->maxLength(255),

                TextInput::make('kode_barang')
                    ->label('Kode Barang')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Select::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'Makanan' => 'Makanan',
                        'Minuman' => 'Minuman',
                        'Elektronik' => 'Elektronik',
                        'Pakaian' => 'Pakaian',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->required(),

                TextInput::make('stok')
                    ->label('Stok')
                    ->numeric()
                    ->default(0)
                    ->required(),

                TextInput::make('stok_minimum')
                    ->label('Stok Minimum')
                    ->numeric()
                    ->default(0)
                    ->required(),

                TextInput::make('satuan')
                    ->label('Satuan')
                    ->required()
                    ->maxLength(255),

                TextInput::make('harga_per_pcs')
                    ->label('Harga per PCS')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->columnSpanFull(),

                FileUpload::make('image')
                    ->label('Gambar')
                    ->disk('public')
                    ->image()
                    ->directory('products')
                    ->visibility('public')
                    ->columnSpanFull(),
            ]);
    }
}

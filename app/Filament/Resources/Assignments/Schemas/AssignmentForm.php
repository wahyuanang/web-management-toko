<?php

namespace App\Filament\Resources\Assignments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;

class AssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Title')
                    ->required(),

                Textarea::make('description')
                    ->label('Description')
                    ->placeholder('Tulis deskripsi tugas...')
                    ->columnSpanFull(),

                Textarea::make('lokasi_tujuan')
                    ->label('Lokasi Tujuan')
                    ->placeholder('Alamat lengkap lokasi pengiriman...')
                    ->helperText('Lokasi ini akan otomatis terisi saat karyawan membuat laporan')
                    ->columnSpanFull(),

                // Ambil user dari tabel users yang memiliki role karyawan saja
                Select::make('assigned_to')
                    ->label('Assigned To')
                    ->options(function () {
                        return User::role('karyawan')->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required()
                    ->helperText('Pilih karyawan yang akan menerima tugas ini'),

                // Ambil product dari tabel products
                Select::make('product_id')
                    ->label('Product')
                    ->options(function () {
                        return Product::all()->pluck('nama_barang', 'id');
                    })
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {

                        // Reset qty_target ketika product berubah
                        $set('qty_target', null);
                    })
                    ->helperText('Pilih produk yang akan dikirim'),

                TextInput::make('qty_target')
                    ->label('Qty Target')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->helperText('Masukkan jumlah target pengiriman untuk karyawan'),

                // Komentar: Validasi stok telah dihapus agar admin bebas menginput qty_target

                Select::make('priority')
                    ->label('Priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                    ])
                    ->default('medium')
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'done' => 'Done',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
                    ->required(),

                DateTimePicker::make('deadline')
                    ->label('Deadline')
                    ->seconds(false)
                    ->helperText('Pilih tanggal dan waktu deadline'),

                // Otomatis isi created_by dari user login
                Hidden::make('created_by')
                    ->default(fn() => Auth::id()),
            ]);
    }
}

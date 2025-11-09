<?php

namespace App\Filament\Resources\Assignments\Schemas;

use Filament\Forms\Components\DatePicker;
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

                // Ambil user dari tabel users
                Select::make('assigned_to')
                    ->label('Assigned To')
                    ->relationship('assignedUser', 'name')
                    ->searchable()
                    ->required(),

                // Ambil product dari tabel products
                Select::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'nama_barang')
                    ->searchable()
                    ->required(),

                TextInput::make('qty_target')
                    ->label('Qty Target')
                    ->numeric()
                    ->required(),

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

                DatePicker::make('deadline')
                    ->label('Deadline'),

                // Otomatis isi created_by dari user login
                Hidden::make('created_by')
                    ->default(fn() => Auth::id()),
            ]);
    }
}
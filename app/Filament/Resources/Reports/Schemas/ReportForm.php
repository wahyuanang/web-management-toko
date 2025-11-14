<?php

namespace App\Filament\Resources\Reports\Schemas;

use App\Models\Assignment;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // Pilihan Assignment hanya yang ditugaskan ke user login
            Select::make('assignment_id')
                ->label('Tugas')
                ->options(function () {
                    return Assignment::where('assigned_to', Auth::id())
                        ->pluck('title', 'id');
                })
                ->searchable()
                ->required()
                ->reactive()
                ->afterStateUpdated(function (callable $set, $state) {
                    // otomatis isi jumlah_barang_dikirim dan lokasi berdasarkan assignment
                    $assignment = Assignment::find($state);
                    if ($assignment) {
                        $set('jumlah_barang_dikirim', $assignment->qty_target);
                        $set('lokasi', $assignment->lokasi_tujuan ?? '');
                    }
                }),

            // otomatis isi user_id dari user yang login
            Hidden::make('user_id')
                ->default(fn() => Auth::id()),

            TextInput::make('jumlah_barang_dikirim')
                ->label('Jumlah barang dikirim')
                ->numeric()
                ->required(),

            Textarea::make('lokasi')
                ->required(),

            Textarea::make('catatan'),

            // Foto bukti wajib upload
            FileUpload::make('foto_bukti')
                ->label('Foto bukti')
                ->image()
                ->directory('reports')
                ->required(),

            // Foto bukti 2 opsional
            FileUpload::make('foto_bukti_2')
                ->label('Foto bukti 2')
                ->image()
                ->directory('reports')
                ->nullable(),

            DateTimePicker::make('waktu_laporan')
                ->label('Waktu laporan')
                ->required()
                ->default(now()),
        ]);
    }
}

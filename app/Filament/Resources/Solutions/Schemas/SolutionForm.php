<?php

namespace App\Filament\Resources\Solutions\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SolutionForm
{
    public const ICONS = [
        'heroicon-o-presentation-chart-line' => 'Grafik strategi',
        'heroicon-o-scale' => 'Timbangan (legal)',
        'heroicon-o-globe-asia-australia' => 'Bumi (ESG)',
        'heroicon-o-building-office-2' => 'Gedung (korporat)',
        'heroicon-o-server-stack' => 'Server (TI)',
        'heroicon-o-sparkles' => 'Sparkles (AI)',
        'heroicon-o-shield-check' => 'Perisai',
        'heroicon-o-chart-bar' => 'Grafik batang',
        'heroicon-o-clipboard-document-check' => 'Checklist',
        'heroicon-o-users' => 'Pengguna',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi solusi')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Dipakai di alamat halaman: /solusi/slug'),
                        TextInput::make('tagline')
                            ->label('Deskripsi singkat')
                            ->required()
                            ->columnSpanFull(),
                        TagsInput::make('features')
                            ->label('Fitur utama')
                            ->placeholder('Ketik fitur lalu Enter')
                            ->reorderable()
                            ->columnSpanFull(),
                        RichEditor::make('description')
                            ->label('Deskripsi lengkap (halaman detail)')
                            ->columnSpanFull(),
                    ]),
                Section::make('Tampilan')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('icon')
                            ->label('Ikon')
                            ->options(self::ICONS),
                        FileUpload::make('image')
                            ->label('Gambar / screenshot')
                            ->image()
                            ->directory('solutions'),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Tampilkan di website')
                            ->default(true),
                    ]),
            ]);
    }
}

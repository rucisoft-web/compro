<?php

namespace App\Filament\Resources\CatalogItems\Schemas;

use App\Models\CatalogItem;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CatalogItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('type')
                            ->label('Jenis')
                            ->options(CatalogItem::TYPES)
                            ->required()
                            ->live(),
                        Select::make('solution_id')
                            ->label('Terkait solusi')
                            ->relationship('solution', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Dipakai di alamat halaman: /katalog/slug'),
                        Textarea::make('summary')
                            ->label('Ringkasan')
                            ->rows(2)
                            ->maxLength(500)
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->label('Isi lengkap')
                            ->columnSpanFull(),
                    ]),
                Section::make('Jadwal workshop')
                    ->columns(2)
                    ->columnSpanFull()
                    ->visible(fn (Get $get) => $get('type') === 'workshop')
                    ->schema([
                        TextInput::make('event_date')
                            ->label('Tanggal')
                            ->placeholder('contoh: 12-13 November 2026'),
                        TextInput::make('location')
                            ->label('Lokasi')
                            ->placeholder('contoh: Jakarta / Online'),
                    ]),
                Section::make('Tampilan & WhatsApp')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('cover_image')
                            ->label('Gambar sampul')
                            ->image()
                            ->disk('public')
                            ->directory('catalog'),
                        Textarea::make('whatsapp_message')
                            ->label('Pesan WhatsApp otomatis')
                            ->helperText('Kosongkan untuk memakai pesan bawaan berisi judul item.')
                            ->rows(3),
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

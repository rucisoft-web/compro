<?php

namespace App\Filament\Resources\CatalogItems\Schemas;

use App\Models\CatalogItem;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
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
                        TextInput::make('subtitle')
                            ->label('Subjudul')
                            ->placeholder('contoh: Praktik menggunakan perangkat lunak Ruci BSC')
                            ->columnSpanFull(),
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
                Section::make('Detail Rucisoft Academy')
                    ->description('Untuk webinar, workshop, dan in-house. Tonjolkan hasil kerja yang dibawa pulang peserta.')
                    ->columns(2)
                    ->columnSpanFull()
                    ->visible(fn (Get $get) => in_array($get('type'), CatalogItem::ACADEMY_TYPES, true))
                    ->schema([
                        TextInput::make('event_date')
                            ->label('Jadwal')
                            ->placeholder('contoh: 12 November 2026, 09.00-16.00 WIB'),
                        TextInput::make('location')
                            ->label('Format & lokasi')
                            ->placeholder('contoh: Online (Zoom) / Jakarta'),
                        TextInput::make('duration')
                            ->label('Durasi')
                            ->placeholder('contoh: 1 hari'),
                        TextInput::make('price_label')
                            ->label('Biaya')
                            ->placeholder('contoh: Gratis / Rp1.500.000 per peserta'),
                        TextInput::make('audience')
                            ->label('Cocok untuk')
                            ->columnSpanFull(),
                        TagsInput::make('outcomes')
                            ->label('Hasil yang dibawa pulang')
                            ->placeholder('Ketik hasil lalu Enter')
                            ->reorderable()
                            ->columnSpanFull(),
                        Repeater::make('agenda')
                            ->label('Susunan acara')
                            ->schema([
                                TextInput::make('part')->label('Bagian')->required(),
                                TextInput::make('portion')->label('Porsi')->placeholder('20%'),
                                TextInput::make('content')->label('Isi')->columnSpan(2),
                            ])
                            ->columns(4)
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0)
                            ->columnSpanFull(),
                        Toggle::make('registration_open')
                            ->label('Pendaftaran dibuka')
                            ->helperText('Jika dimatikan, formulir berubah menjadi "daftar minat".')
                            ->default(true),
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

<?php

namespace App\Filament\Resources\Leads\Schemas;

use App\Models\Lead;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kontak')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('kind')->label('Jenis permintaan')->options(collect(Lead::KINDS)->map(fn ($k) => $k['label']))->required(),
                        TextInput::make('organization_name')->label('Organisasi (sesuai isian)')->required(),
                        TextInput::make('name')->label('Nama')->required(),
                        TextInput::make('position')->label('Jabatan / peran'),
                        TextInput::make('email')->email()->required(),
                        TextInput::make('phone')->label('WhatsApp')->required(),
                    ]),
                Section::make('Kebutuhan')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('solution_id')->label('Produk')->relationship('solution', 'name'),
                        Select::make('catalog_item_id')->label('Acara / item')->relationship('catalogItem', 'title'),
                        Textarea::make('message')->label('Pesan')->rows(3)->columnSpanFull(),
                        KeyValue::make('answers')->label('Jawaban asesmen')->keyLabel('Pertanyaan')->valueLabel('Jawaban')->columnSpanFull(),
                        TextInput::make('source_url')->label('Dikirim dari halaman')->disabled()->columnSpanFull(),
                        DateTimePicker::make('handled_at')->label('Ditindaklanjuti pada'),
                    ]),
            ]);
    }
}

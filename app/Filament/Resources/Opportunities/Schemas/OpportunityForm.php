<?php

namespace App\Filament\Resources\Opportunities\Schemas;

use App\Models\Opportunity;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OpportunityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Peluang')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('organization_id')
                            ->label('Organisasi')
                            ->relationship('organization', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')->label('Nama organisasi')->required(),
                                TextInput::make('sector')->label('Sektor'),
                            ])
                            ->required(),
                        Select::make('solution_id')->label('Produk')->relationship('solution', 'name'),
                        Select::make('stage')->label('Tahap funnel')->options(Opportunity::STAGES)->default('edukasi')->required(),
                        TextInput::make('estimated_value')->label('Perkiraan nilai')->numeric()->prefix('Rp'),
                    ]),
                Section::make('Tindak lanjut')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('next_action')->label('Langkah berikutnya'),
                        DatePicker::make('next_action_at')->label('Tanggal'),
                        Textarea::make('notes')
                            ->label('Catatan (sponsor, pengambil keputusan, proses pengadaan, ukuran keberhasilan pilot)')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

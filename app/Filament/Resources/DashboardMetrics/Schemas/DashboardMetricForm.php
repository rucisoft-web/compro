<?php

namespace App\Filament\Resources\DashboardMetrics\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DashboardMetricForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->label('Label')
                    ->required(),
                TextInput::make('value')
                    ->label('Nilai (%)')
                    ->minValue(0)
                    ->maxValue(100)
                    ->required()
                    ->numeric(),
                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}

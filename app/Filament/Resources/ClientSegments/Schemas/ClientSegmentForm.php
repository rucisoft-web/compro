<?php

namespace App\Filament\Resources\ClientSegments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientSegmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}

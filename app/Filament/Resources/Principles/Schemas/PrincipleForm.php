<?php

namespace App\Filament\Resources\Principles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PrincipleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul')
                    ->required(),
                TextInput::make('description')
                    ->label('Deskripsi'),
                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}

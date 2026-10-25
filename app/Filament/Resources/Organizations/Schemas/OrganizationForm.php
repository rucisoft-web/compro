<?php

namespace App\Filament\Resources\Organizations\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrganizationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->label('Nama organisasi')->required()->unique(ignoreRecord: true),
                TextInput::make('sector')->label('Sektor')->placeholder('mis. BUMN, rumah sakit, universitas'),
                Textarea::make('notes')->label('Catatan')->rows(4)->columnSpanFull(),
            ]);
    }
}

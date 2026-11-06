<?php

namespace App\Filament\Resources\SoftwareCategories\Schemas;

use App\Filament\Resources\Solutions\Schemas\SolutionForm;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SoftwareCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->label('Nama kategori')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Textarea::make('description')
                    ->label('Deskripsi singkat')
                    ->rows(2)
                    ->maxLength(500)
                    ->columnSpanFull(),
                Select::make('icon')
                    ->label('Ikon')
                    ->options(SolutionForm::ICONS),
                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
            ]);
    }
}

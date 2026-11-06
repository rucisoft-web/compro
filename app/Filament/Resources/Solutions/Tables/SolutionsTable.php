<?php

namespace App\Filament\Resources\Solutions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SolutionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('logo_preview')
                    ->label('Logo')
                    ->state(fn ($record) => $record->logoUrl())
                    ->imageSize(36),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),
                TextColumn::make('tagline')
                    ->label('Deskripsi singkat')
                    ->limit(60),
                TextColumn::make('catalog_items_count')
                    ->label('Item katalog')
                    ->counts('catalogItems'),
                ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->filters([
                SelectFilter::make('software_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

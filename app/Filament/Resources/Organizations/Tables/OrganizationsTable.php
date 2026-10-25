<?php

namespace App\Filament\Resources\Organizations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrganizationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')->label('Organisasi')->searchable()->weight('bold'),
                TextColumn::make('sector')->label('Sektor')->placeholder('-'),
                TextColumn::make('opportunities_count')->label('Peluang')->counts('opportunities'),
                TextColumn::make('leads_count')->label('Kontak')->counts('leads'),
                TextColumn::make('created_at')->label('Pertama kali')->date('d M Y')->sortable(),
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

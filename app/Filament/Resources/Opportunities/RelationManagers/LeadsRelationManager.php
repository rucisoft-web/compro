<?php

namespace App\Filament\Resources\Opportunities\RelationManagers;

use App\Models\Lead;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeadsRelationManager extends RelationManager
{
    protected static string $relationship = 'leads';

    protected static ?string $title = 'Riwayat kontak & permintaan';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('Tanggal')->dateTime('d M Y H:i'),
                TextColumn::make('kind')->label('Jenis')->badge()
                    ->formatStateUsing(fn (string $state) => Lead::KINDS[$state]['label'] ?? $state),
                TextColumn::make('name')->label('Nama')->description(fn (Lead $record) => $record->position),
                TextColumn::make('phone')->label('WhatsApp')->copyable(),
                TextColumn::make('catalogItem.title')->label('Acara')->placeholder('-'),
                TextColumn::make('message')->label('Pesan')->limit(50)->wrap(),
            ]);
    }
}

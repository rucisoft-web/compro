<?php

namespace App\Filament\Resources\Opportunities\Tables;

use App\Models\Opportunity;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OpportunitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('next_action_at')
            ->columns([
                TextColumn::make('organization.name')->label('Organisasi')->searchable()->weight('bold'),
                TextColumn::make('solution.name')->label('Produk')->placeholder('Belum ditentukan'),
                SelectColumn::make('stage')->label('Tahap')->options(Opportunity::STAGES)->selectablePlaceholder(false),
                TextColumn::make('next_action')->label('Langkah berikutnya')->limit(45)->wrap(),
                TextColumn::make('next_action_at')->label('Tanggal')->date('d M Y')->sortable()
                    ->color(fn (Opportunity $record) => $record->next_action_at?->isPast() && ! $record->next_action_at->isToday() ? 'danger' : null),
                TextColumn::make('leads_count')->label('Kontak')->counts('leads'),
                TextColumn::make('estimated_value')->label('Nilai')->money('IDR')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Diperbarui')->since()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('stage')->label('Tahap')->options(Opportunity::STAGES)->multiple(),
                SelectFilter::make('solution')->label('Produk')->relationship('solution', 'name'),
                Filter::make('due')->label('Perlu ditindaklanjuti hari ini / terlambat')
                    ->query(fn (Builder $query) => $query->whereDate('next_action_at', '<=', today())
                        ->whereNotIn('stage', ['menang', 'batal'])),
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

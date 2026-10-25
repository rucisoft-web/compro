<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Opportunities\OpportunityResource;
use App\Models\Opportunity;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class FollowUps extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Tindak lanjut 7 hari ke depan & yang terlambat';

    public function table(Table $table): Table
    {
        return $table
            ->query(Opportunity::query()
                ->with(['organization', 'solution'])
                ->whereNotIn('stage', ['menang', 'pendampingan', 'perpanjangan', 'batal'])
                ->whereDate('next_action_at', '<=', today()->addDays(7))
                ->orderBy('next_action_at'))
            ->paginated([5, 10])
            ->columns([
                TextColumn::make('next_action_at')->label('Tanggal')->date('d M')
                    ->color(fn (Opportunity $record) => $record->next_action_at?->isPast() && ! $record->next_action_at->isToday() ? 'danger' : null),
                TextColumn::make('organization.name')->label('Organisasi')->weight('bold'),
                TextColumn::make('solution.name')->label('Produk')->placeholder('-'),
                TextColumn::make('stage')->label('Tahap')->badge()
                    ->formatStateUsing(fn (string $state) => Opportunity::STAGES[$state] ?? $state)
                    ->color(fn (string $state) => Opportunity::STAGE_COLORS[$state] ?? 'gray'),
                TextColumn::make('next_action')->label('Langkah')->wrap(),
            ])
            ->recordActions([
                Action::make('open')->label('Buka')->url(fn (Opportunity $record) => OpportunityResource::getUrl('edit', ['record' => $record])),
            ])
            ->emptyStateHeading('Tidak ada tindak lanjut yang jatuh tempo');
    }
}

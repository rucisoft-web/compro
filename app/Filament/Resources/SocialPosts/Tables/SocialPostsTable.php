<?php

namespace App\Filament\Resources\SocialPosts\Tables;

use App\Models\SocialPost;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Js;

class SocialPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort(fn (Builder $query) => $query->orderByRaw('scheduled_at is null')->orderBy('scheduled_at')->orderByDesc('id'))
            ->columns([
                ImageColumn::make('image')->label('')->disk('public')->square()->size(44),
                TextColumn::make('title')->label('Judul')->searchable(['title', 'body'])->weight('bold')->wrap()->grow(),
                TextColumn::make('solution.name')->label('Produk')->placeholder('Umum')->sortable(),
                TextColumn::make('funnel_stage')->label('Tahap')->badge()->color('gray')
                    ->formatStateUsing(fn (string $state) => SocialPost::STAGES[$state] ?? $state)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('gallery')->label('Gambar')->state(fn (SocialPost $record) => count($record->gallery ?? []) + ($record->image ? 1 : 0))->suffix(' gambar')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('platforms')->label('Platform')->badge()
                    ->formatStateUsing(fn (string $state) => SocialPost::PLATFORMS[$state] ?? $state),
                TextColumn::make('status')->badge()
                    ->formatStateUsing(fn (string $state) => SocialPost::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => SocialPost::STATUS_COLORS[$state] ?? 'gray'),
                IconColumn::make('published_on_site')->label('Di website')->boolean(),
                TextColumn::make('scheduled_at')->label('Rencana tayang')->dateTime('d M Y H:i')->sortable()->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('solution')->label('Produk')->relationship('solution', 'name'),
                SelectFilter::make('status')->options(SocialPost::STATUSES),
                SelectFilter::make('funnel_stage')->label('Tahap funnel')->options(SocialPost::STAGES),
                SelectFilter::make('platform')->options(SocialPost::PLATFORMS)
                    ->query(fn (Builder $query, array $data) => $query->when($data['value'], fn ($q, $p) => $q->whereJsonContains('platforms', $p))),
            ])
            ->recordActions([
                Action::make('copy')
                    ->label('Salin teks')
                    ->icon('heroicon-o-clipboard-document')
                    ->color('gray')
                    ->alpineClickHandler(fn (SocialPost $record) => 'window.navigator.clipboard.writeText('.Js::from($record->fullText()).').then(() => $tooltip('.Js::from('Teks, link, dan tagar tersalin').', { theme: $store.theme, timeout: 2000 }))'),
                EditAction::make()->iconButton(),
                ActionGroup::make([
                    Action::make('posted')
                        ->label('Tandai sudah diposting')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->hidden(fn (SocialPost $record) => $record->status === 'terposting')
                        ->schema([TextInput::make('posted_url')->label('Link postingan (opsional)')->url()])
                        ->action(fn (SocialPost $record, array $data) => $record->update(['status' => 'terposting', 'posted_at' => now()] + $data)),
                    Action::make('view')
                        ->label('Lihat di website')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->visible(fn (SocialPost $record) => $record->published_on_site && $record->slug)
                        ->url(fn (SocialPost $record) => route('articles.show', $record))
                        ->openUrlInNewTab(),
                    ReplicateAction::make()
                        ->label('Duplikat')
                        ->excludeAttributes(['posted_at', 'posted_url', 'scheduled_at', 'slug', 'published_on_site', 'published_at'])
                        ->beforeReplicaSaved(fn (SocialPost $replica) => $replica->fill(['status' => 'draf', 'title' => $replica->title.' (salinan)'])),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('ready')
                        ->label('Tandai siap diposting')
                        ->icon('heroicon-o-paper-airplane')
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'siap']))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('export')
                        ->label('Ekspor CSV')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn (Collection $records) => self::exportCsv($records)),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function exportCsv(Collection $records)
    {
        $records->load('solution');

        return response()->streamDownload(function () use ($records) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Rencana tayang', 'Produk', 'Judul', 'Tahap', 'Platform', 'Status', 'Teks lengkap']);
            foreach ($records as $post) {
                fputcsv($out, [
                    $post->scheduled_at?->format('Y-m-d H:i'), $post->solution?->name ?? 'Umum', $post->title,
                    SocialPost::STAGES[$post->funnel_stage] ?? $post->funnel_stage,
                    collect($post->platforms)->map(fn ($p) => SocialPost::PLATFORMS[$p] ?? $p)->implode(', '),
                    SocialPost::STATUSES[$post->status] ?? $post->status, $post->fullText(),
                ]);
            }
            fclose($out);
        }, 'konten-sosmed-rucisoft-'.now()->format('Ymd-His').'.csv', ['Content-Type' => 'text/csv']);
    }
}

<?php

namespace App\Filament\Resources\Leads\Tables;

use App\Models\Lead;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class LeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('Masuk')->since()->sortable(),
                TextColumn::make('kind')->label('Jenis')->badge()
                    ->formatStateUsing(fn (string $state) => Lead::KINDS[$state]['label'] ?? $state),
                TextColumn::make('organization_name')->label('Organisasi')->searchable()->weight('bold'),
                TextColumn::make('name')->label('Nama')->searchable()
                    ->description(fn (Lead $record) => $record->position),
                TextColumn::make('phone')->label('WhatsApp')->copyable()->toggleable(),
                TextColumn::make('email')->copyable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('solution.name')->label('Produk')->placeholder('-'),
                TextColumn::make('catalogItem.title')->label('Acara')->limit(30)->placeholder('-')->toggleable(),
                IconColumn::make('handled_at')->label('Ditindaklanjuti')->boolean()
                    ->state(fn (Lead $record) => filled($record->handled_at)),
            ])
            ->filters([
                SelectFilter::make('kind')->label('Jenis')->options(collect(Lead::KINDS)->map(fn ($k) => $k['label'])),
                SelectFilter::make('solution')->label('Produk')->relationship('solution', 'name'),
                TernaryFilter::make('handled_at')->label('Ditindaklanjuti')->nullable(),
            ])
            ->recordActions([
                Action::make('whatsapp')
                    ->label('WA')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn (Lead $record) => self::whatsappUrl($record))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('handled')
                        ->label('Tandai sudah ditindaklanjuti')
                        ->icon('heroicon-o-check')
                        ->action(fn (Collection $records) => $records->each->update(['handled_at' => now()]))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('export')
                        ->label('Ekspor CSV')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn (Collection $records) => self::exportCsv($records)),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function whatsappUrl(Lead $lead): string
    {
        $number = preg_replace('/\D/', '', $lead->phone);
        if (str_starts_with($number, '0')) {
            $number = '62'.substr($number, 1);
        }
        $text = "Halo {$lead->name}, terima kasih sudah menghubungi ".Setting::get('site_name')
            .($lead->solution ? " tentang {$lead->solution->name}" : '').'.';

        return 'https://wa.me/'.$number.'?text='.rawurlencode($text);
    }

    private static function exportCsv(Collection $records)
    {
        $records->load(['solution', 'catalogItem']);

        return response()->streamDownload(function () use ($records) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Tanggal', 'Jenis', 'Organisasi', 'Nama', 'Jabatan', 'Email', 'WhatsApp', 'Produk', 'Acara', 'Pesan']);
            foreach ($records as $lead) {
                fputcsv($out, [
                    $lead->created_at->format('Y-m-d H:i'), $lead->kindLabel(), $lead->organization_name, $lead->name,
                    $lead->position, $lead->email, $lead->phone, $lead->solution?->name, $lead->catalogItem?->title, $lead->message,
                ]);
            }
            fclose($out);
        }, 'prospek-rucisoft-'.now()->format('Ymd-His').'.csv', ['Content-Type' => 'text/csv']);
    }
}

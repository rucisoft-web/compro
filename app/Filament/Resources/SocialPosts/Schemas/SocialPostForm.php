<?php

namespace App\Filament\Resources\SocialPosts\Schemas;

use App\Models\CatalogItem;
use App\Models\SocialPost;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class SocialPostForm
{
    public static function configure(Schema $schema): Schema
    {
        $limits = collect(SocialPost::LIMITS)->map(fn ($n, $p) => SocialPost::PLATFORMS[$p].' '.number_format($n, 0, ',', '.'))->implode(' · ');

        return $schema
            ->columns(3)
            ->components([
                Section::make('Isi konten')
                    ->columnSpan(2)
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')->label('Judul internal')->required()->maxLength(255)->columnSpanFull()
                            ->helperText('Untuk memudahkan pencarian di admin; tidak ikut diposting.'),
                        Select::make('solution_id')->label('Produk')->relationship('solution', 'name')->live()->placeholder('Rucisoft (umum)'),
                        Select::make('catalog_item_id')->label('Acara / program terkait')
                            ->options(fn (Get $get) => CatalogItem::query()
                                ->when($get('solution_id'), fn ($q, $id) => $q->where('solution_id', $id))
                                ->orderBy('title')->pluck('title', 'id'))
                            ->searchable()->placeholder('Tidak ada'),
                        Select::make('funnel_stage')->label('Tahap funnel yang dituju')->options(SocialPost::STAGES)->default('edukasi')->required(),
                        Select::make('format')->label('Format')->options(SocialPost::FORMATS)->default('post')->required(),
                        CheckboxList::make('platforms')->label('Platform')->options(SocialPost::PLATFORMS)->columns(3)->columnSpanFull(),
                        Textarea::make('body')->label('Teks / caption')->rows(12)->required()->columnSpanFull()
                            ->helperText('Batas karakter: '.$limits.'. Link dan tagar ditambahkan otomatis saat disalin.'),
                        TextInput::make('hashtags')->label('Tagar')->placeholder('#Rucisoft #BalancedScorecard')->columnSpanFull(),
                        TextInput::make('link_url')->label('Link tujuan')->url()->columnSpanFull()
                            ->helperText('Kosongkan untuk memakai halaman produk atau acara di rucisoft.id. Link rucisoft.id otomatis diberi kode UTM agar prospek dari postingan ini bisa dilacak.'),
                    ]),
                Section::make('Jadwal & status')
                    ->columnSpan(1)
                    ->schema([
                        Select::make('status')->options(SocialPost::STATUSES)->default('draf')->required()->live(),
                        DateTimePicker::make('scheduled_at')->label('Rencana tayang')->seconds(false),
                        DateTimePicker::make('posted_at')->label('Diposting pada')->seconds(false)
                            ->visible(fn (Get $get) => $get('status') === 'terposting'),
                        TextInput::make('posted_url')->label('Link postingan')->url()
                            ->visible(fn (Get $get) => $get('status') === 'terposting'),
                        FileUpload::make('image')->label('Gambar utama')->image()->disk('public')->directory('sosmed')->imageEditor()
                            ->helperText('Infografis atau desain sampul.'),
                        FileUpload::make('gallery')->label('Screenshot & grafik pendukung')->image()->multiple()->reorderable()
                            ->appendFiles()->panelLayout('grid')->disk('public')->directory('sosmed')->maxFiles(10)->downloadable()
                            ->helperText('Untuk carousel atau lampiran artikel. Urutan bisa digeser.'),
                        Textarea::make('notes')->label('Catatan')->rows(3),
                    ]),
            ]);
    }
}

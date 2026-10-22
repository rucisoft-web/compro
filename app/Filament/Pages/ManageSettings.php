<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class ManageSettings extends Page
{
    protected string $view = 'filament.pages.manage-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Situs';

    protected static ?string $title = 'Pengaturan Situs';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(collect(array_keys(Setting::DEFAULTS))
            ->mapWithKeys(fn (string $key) => [$key => Setting::get($key)])
            ->all());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Kontak & WhatsApp')
                    ->columns(2)
                    ->schema([
                        TextInput::make('site_name')->label('Nama situs')->required(),
                        TextInput::make('email')->label('Email')->email()->required(),
                        TextInput::make('whatsapp_number')
                            ->label('Nomor WhatsApp')
                            ->helperText('Boleh diawali 08 atau 62, contoh 081199892939.')
                            ->required(),
                        TextInput::make('whatsapp_display')->label('Nomor WhatsApp yang ditampilkan'),
                        Textarea::make('whatsapp_default_message')->label('Pesan WhatsApp bawaan')->rows(2)->columnSpanFull(),
                        TextInput::make('address')->label('Alamat / lokasi')->columnSpanFull(),
                    ]),
                Section::make('Hero beranda')
                    ->columns(2)
                    ->schema([
                        TextInput::make('hero_eyebrow')->label('Label kecil'),
                        TextInput::make('hero_title')->label('Judul utama'),
                        Textarea::make('hero_subtitle')->label('Subjudul')->rows(2)->columnSpanFull(),
                        TextInput::make('hero_badges')->label('Badge (pisahkan dengan koma)')->columnSpanFull(),
                        TextInput::make('dashboard_alert')->label('Teks peringatan dasbor')->columnSpanFull(),
                    ]),
                Section::make('Tentang & ajakan')
                    ->schema([
                        TextInput::make('about_title')->label('Judul Tentang'),
                        Textarea::make('about_body')->label('Isi Tentang')->rows(3),
                        TextInput::make('cta_title')->label('Judul ajakan (CTA)'),
                    ]),
                Section::make('SEO')
                    ->schema([
                        Textarea::make('meta_description')->label('Meta description')->rows(2)->maxLength(300),
                    ]),
            ]);
    }

    public function save(): void
    {
        foreach ($this->form->getState() as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Notification::make()->title('Pengaturan disimpan')->success()->send();
    }
}

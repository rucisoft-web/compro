<?php

namespace App\Filament\Resources\SocialPosts;

use App\Filament\Resources\SocialPosts\Pages\CreateSocialPost;
use App\Filament\Resources\SocialPosts\Pages\EditSocialPost;
use App\Filament\Resources\SocialPosts\Pages\ListSocialPosts;
use App\Filament\Resources\SocialPosts\Schemas\SocialPostForm;
use App\Filament\Resources\SocialPosts\Tables\SocialPostsTable;
use App\Models\SocialPost;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SocialPostResource extends Resource
{
    protected static ?string $model = SocialPost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static string|UnitEnum|null $navigationGroup = 'Pemasaran';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Konten Media Sosial';

    protected static ?string $pluralModelLabel = 'Konten Media Sosial';

    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationBadge(): ?string
    {
        $count = SocialPost::where('status', 'siap')->count();

        return $count ? (string) $count : null;
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Siap diposting';
    }

    public static function form(Schema $schema): Schema
    {
        return SocialPostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SocialPostsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSocialPosts::route('/'),
            'create' => CreateSocialPost::route('/create'),
            'edit' => EditSocialPost::route('/{record}/edit'),
        ];
    }
}

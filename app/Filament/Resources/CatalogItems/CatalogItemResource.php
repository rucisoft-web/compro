<?php

namespace App\Filament\Resources\CatalogItems;

use App\Filament\Resources\CatalogItems\Pages\CreateCatalogItem;
use App\Filament\Resources\CatalogItems\Pages\EditCatalogItem;
use App\Filament\Resources\CatalogItems\Pages\ListCatalogItems;
use App\Filament\Resources\CatalogItems\Schemas\CatalogItemForm;
use App\Filament\Resources\CatalogItems\Tables\CatalogItemsTable;
use App\Models\CatalogItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CatalogItemResource extends Resource
{
    protected static ?string $model = CatalogItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string|UnitEnum|null $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Item Katalog';

    protected static ?string $pluralModelLabel = 'Katalog';

    public static function form(Schema $schema): Schema
    {
        return CatalogItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CatalogItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCatalogItems::route('/'),
            'create' => CreateCatalogItem::route('/create'),
            'edit' => EditCatalogItem::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\ClientSegments;

use App\Filament\Resources\ClientSegments\Pages\CreateClientSegment;
use App\Filament\Resources\ClientSegments\Pages\EditClientSegment;
use App\Filament\Resources\ClientSegments\Pages\ListClientSegments;
use App\Filament\Resources\ClientSegments\Schemas\ClientSegmentForm;
use App\Filament\Resources\ClientSegments\Tables\ClientSegmentsTable;
use App\Models\ClientSegment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ClientSegmentResource extends Resource
{
    protected static ?string $model = ClientSegment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|UnitEnum|null $navigationGroup = 'Beranda';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Segmen Klien';

    protected static ?string $pluralModelLabel = 'Segmen Klien';

    public static function form(Schema $schema): Schema
    {
        return ClientSegmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClientSegmentsTable::configure($table);
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
            'index' => ListClientSegments::route('/'),
            'create' => CreateClientSegment::route('/create'),
            'edit' => EditClientSegment::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\DashboardMetrics;

use App\Filament\Resources\DashboardMetrics\Pages\CreateDashboardMetric;
use App\Filament\Resources\DashboardMetrics\Pages\EditDashboardMetric;
use App\Filament\Resources\DashboardMetrics\Pages\ListDashboardMetrics;
use App\Filament\Resources\DashboardMetrics\Schemas\DashboardMetricForm;
use App\Filament\Resources\DashboardMetrics\Tables\DashboardMetricsTable;
use App\Models\DashboardMetric;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DashboardMetricResource extends Resource
{
    protected static ?string $model = DashboardMetric::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Beranda';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Metrik Dasbor';

    protected static ?string $pluralModelLabel = 'Metrik Dasbor';

    public static function form(Schema $schema): Schema
    {
        return DashboardMetricForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DashboardMetricsTable::configure($table);
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
            'index' => ListDashboardMetrics::route('/'),
            'create' => CreateDashboardMetric::route('/create'),
            'edit' => EditDashboardMetric::route('/{record}/edit'),
        ];
    }
}

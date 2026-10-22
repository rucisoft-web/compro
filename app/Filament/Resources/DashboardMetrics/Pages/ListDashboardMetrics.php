<?php

namespace App\Filament\Resources\DashboardMetrics\Pages;

use App\Filament\Resources\DashboardMetrics\DashboardMetricResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDashboardMetrics extends ListRecords
{
    protected static string $resource = DashboardMetricResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

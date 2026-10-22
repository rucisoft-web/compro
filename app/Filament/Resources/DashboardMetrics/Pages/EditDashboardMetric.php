<?php

namespace App\Filament\Resources\DashboardMetrics\Pages;

use App\Filament\Resources\DashboardMetrics\DashboardMetricResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDashboardMetric extends EditRecord
{
    protected static string $resource = DashboardMetricResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

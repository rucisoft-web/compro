<?php

namespace App\Filament\Widgets;

use App\Models\CatalogItem;
use App\Models\Solution;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Solusi aktif', Solution::where('is_active', true)->count()),
            Stat::make('Software', CatalogItem::where('type', 'software')->where('is_active', true)->count()),
            Stat::make('Buku', CatalogItem::where('type', 'buku')->where('is_active', true)->count()),
            Stat::make('Workshop', CatalogItem::where('type', 'workshop')->where('is_active', true)->count()),
        ];
    }
}

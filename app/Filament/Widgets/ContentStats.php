<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\Opportunity;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $workshop = Opportunity::reachedCount('workshop');
        $pilot = Opportunity::reachedCount('pilot');
        $won = Opportunity::reachedCount('menang');

        return [
            Stat::make('Prospek baru (7 hari)', Lead::where('created_at', '>=', now()->subDays(7))->count())
                ->description(Lead::whereNull('handled_at')->count().' belum ditindaklanjuti'),
            Stat::make('Organisasi di funnel', Opportunity::whereNotIn('stage', ['batal'])->distinct('organization_id')->count('organization_id')),
            Stat::make('Workshop → pilot', $workshop ? round($pilot / $workshop * 100).'%' : '-')
                ->description("{$pilot} dari {$workshop} organisasi"),
            Stat::make('Pilot → pembelian', $pilot ? round($won / $pilot * 100).'%' : '-')
                ->description("{$won} organisasi membeli"),
        ];
    }
}

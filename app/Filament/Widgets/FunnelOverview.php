<?php

namespace App\Filament\Widgets;

use App\Models\Opportunity;
use App\Models\Solution;
use Filament\Widgets\Widget;

class FunnelOverview extends Widget
{
    protected static ?int $sort = 2;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.funnel-overview';

    public ?string $solution = null;

    protected function getViewData(): array
    {
        $solutionId = $this->solution ? (int) $this->solution : null;
        $stages = collect(Opportunity::STAGES)->except('batal')->map(fn ($label, $key) => [
            'label' => $label,
            'count' => Opportunity::reachedCount($key, $solutionId),
            'current' => Opportunity::where('stage', $key)->when($solutionId, fn ($q) => $q->where('solution_id', $solutionId))->count(),
        ]);

        return [
            'stages' => $stages,
            'max' => max(1, $stages->max('count')),
            'lost' => Opportunity::where('stage', 'batal')->when($solutionId, fn ($q) => $q->where('solution_id', $solutionId))->count(),
            'solutions' => Solution::orderBy('sort_order')->pluck('name', 'id'),
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CatalogItem;
use App\Models\ClientSegment;
use App\Models\DashboardMetric;
use App\Models\Principle;
use App\Models\Solution;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'solutions' => Solution::active()->get(),
            'catalog' => CatalogItem::active()->with('solution')->get(),
            'stages' => Stage::orderBy('sort_order')->get(),
            'segments' => ClientSegment::orderBy('sort_order')->get(),
            'principles' => Principle::orderBy('sort_order')->get(),
            'metrics' => DashboardMetric::orderBy('sort_order')->get(),
        ]);
    }

    public function solution(Solution $solution): View
    {
        abort_unless($solution->is_active, 404);

        return view('pages.solution', [
            'solution' => $solution,
            'related' => $solution->catalogItems()->active()->get(),
            'others' => Solution::active()->whereKeyNot($solution->id)->get(),
        ]);
    }

    public function catalog(Request $request): View
    {
        $type = $request->string('type')->toString();
        $type = array_key_exists($type, CatalogItem::TYPES) ? $type : null;

        return view('pages.catalog', [
            'type' => $type,
            'items' => CatalogItem::active()->with('solution')->when($type, fn ($q) => $q->where('type', $type))->get(),
        ]);
    }

    public function catalogItem(CatalogItem $item): View
    {
        abort_unless($item->is_active, 404);

        return view('pages.catalog-item', [
            'item' => $item->load('solution'),
            'related' => CatalogItem::active()->whereKeyNot($item->id)->where('type', $item->type)->limit(3)->get(),
        ]);
    }
}

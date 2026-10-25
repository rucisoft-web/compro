<?php

namespace App\Http\Controllers;

use App\Models\CatalogItem;
use App\Models\SocialPost;
use App\Models\Solution;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = collect([route('home'), route('academy'), route('articles.index'), route('catalog.index')])
            ->merge(Solution::active()->get()->map(fn ($s) => route('solutions.show', $s)))
            ->merge(CatalogItem::active()->get()->map(fn ($i) => route('catalog.show', $i)))
            ->merge(SocialPost::published()->get()->map(fn ($a) => route('articles.show', $a)));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($urls as $url) {
            $xml .= '<url><loc>'.e($url).'</loc></url>';
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}

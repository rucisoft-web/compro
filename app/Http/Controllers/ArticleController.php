<?php

namespace App\Http\Controllers;

use App\Models\SocialPost;
use App\Models\Solution;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $solution = Solution::active()->where('slug', $request->string('produk')->toString())->first();

        return view('pages.articles', [
            'solution' => $solution,
            'solutions' => Solution::active()->whereHas('socialPosts', fn ($q) => $q->published())->get(),
            'articles' => SocialPost::published()
                ->with('solution')
                ->when($solution, fn ($q) => $q->where('solution_id', $solution->id))
                ->paginate(9)
                ->withQueryString(),
        ]);
    }

    public function show(SocialPost $article): View
    {
        abort_unless($article->published_on_site && $article->published_at?->isPast(), 404);
        $article->load('solution');

        return view('pages.article', [
            'article' => $article,
            'events' => $article->solution?->academyItems()->active()->limit(3)->get() ?? collect(),
            'more' => SocialPost::published()->with('solution')->whereKeyNot($article->id)->limit(3)->get(),
        ]);
    }
}

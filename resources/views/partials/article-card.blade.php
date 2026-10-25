<article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:border-brand-400 hover:shadow-lg">
    <a href="{{ route('articles.show', $article) }}" class="block aspect-[16/10] overflow-hidden bg-slate-100">
        @if ($article->image)
            <img src="{{ $article->imageUrl() }}" alt="" loading="lazy" class="size-full object-cover object-top transition duration-500 group-hover:scale-105">
        @endif
    </a>
    <div class="flex flex-1 flex-col p-5">
        <div class="flex items-center justify-between gap-3 text-xs">
            @if ($article->solution)
                <span class="rounded-full bg-brand-50 px-3 py-1 font-semibold text-brand-700">{{ $article->solution->name }}</span>
            @endif
            <span class="text-slate-400">{{ $article->published_at?->translatedFormat('d M Y') }}</span>
        </div>
        <h3 class="mt-3 text-base leading-snug font-bold text-navy-900">
            <a href="{{ route('articles.show', $article) }}" class="hover:text-brand-700">{{ $article->headline() }}</a>
        </h3>
        <p class="mt-2 line-clamp-3 text-sm text-slate-600">{{ $article->excerpt(180) }}</p>
        <a href="{{ route('articles.show', $article) }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-brand-600 hover:underline">
            Baca artikel <x-heroicon-o-arrow-right class="size-4" />
        </a>
    </div>
</article>

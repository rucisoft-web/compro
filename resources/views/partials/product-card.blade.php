@php($fallbackImage = $solution->fallbackImageUrl())
<article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-brand-400 hover:shadow-lg hover:shadow-navy-900/5">
    <a href="{{ route('solutions.show', $solution) }}" class="block aspect-[16/9] overflow-hidden border-b border-slate-100 bg-slate-100">
        @if ($solution->imageUrl())
            <img src="{{ $solution->imageUrl() }}" alt="Tampilan {{ $solution->name }}" loading="lazy"
                 class="size-full object-cover object-top transition duration-500 group-hover:scale-[1.03]"
                 @if ($fallbackImage) onerror="this.onerror=null;this.src='{{ $fallbackImage }}'" @endif>
        @endif
    </a>
    <div class="flex flex-1 flex-col p-5">
        <div class="flex h-10 items-center">
            @include('partials.product-logo', ['solution' => $solution, 'class' => 'h-10'])
        </div>
        <h3 class="mt-3 text-base font-bold text-navy-900">{{ $solution->name }}</h3>
        <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $solution->tagline }}</p>
        <ul class="mt-4 flex-1 space-y-1.5 text-[13px] text-slate-700">
            @foreach (array_slice($solution->features ?? [], 0, 4) as $feature)
                <li class="flex gap-2"><x-heroicon-o-check-circle class="mt-0.5 size-4 shrink-0 text-brand-500" /> {{ $feature }}</li>
            @endforeach
        </ul>
        <div class="mt-5 flex items-center justify-between gap-3 border-t border-slate-100 pt-4">
            <a href="{{ route('solutions.show', $solution) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-brand-600 group-hover:gap-2">
                Lihat detail <x-heroicon-o-arrow-right class="size-4 transition-all" />
            </a>
            @if ($solution->website_url)
                <a href="{{ $solution->website_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-sm font-semibold text-slate-500 hover:text-navy-900">
                    Coba demo <x-heroicon-o-arrow-top-right-on-square class="size-4" />
                </a>
            @endif
        </div>
    </div>
</article>

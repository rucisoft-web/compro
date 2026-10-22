@php($typeIcon = ['software' => 'heroicon-o-computer-desktop', 'buku' => 'heroicon-o-book-open', 'workshop' => 'heroicon-o-academic-cap'][$item->type] ?? 'heroicon-o-squares-2x2')
<article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:border-brand-400 hover:shadow-xl hover:shadow-navy-900/5">
    <a href="{{ route('catalog.show', $item) }}" class="relative block aspect-[16/9] overflow-hidden bg-gradient-to-br from-navy-900 to-brand-600">
        @if ($item->cover_image)
            <img src="{{ Storage::url($item->cover_image) }}" alt="{{ $item->title }}" class="size-full object-cover transition group-hover:scale-105" loading="lazy">
        @else
            <div class="flex size-full items-center justify-center">
                <x-dynamic-component :component="$typeIcon" class="size-14 text-white/80" />
            </div>
        @endif
        <span class="absolute top-3 left-3 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-navy-900">{{ $item->typeLabel() }}</span>
    </a>
    <div class="flex flex-1 flex-col p-5">
        @if ($item->solution)
            <p class="mb-1 text-xs font-semibold text-brand-600">{{ $item->solution->name }}</p>
        @endif
        <h3 class="text-base font-bold text-navy-900"><a href="{{ route('catalog.show', $item) }}" class="hover:text-brand-700">{{ $item->title }}</a></h3>
        <p class="mt-2 line-clamp-3 flex-1 text-sm text-slate-500">{{ $item->summary }}</p>
        @if ($item->event_date || $item->location)
            <p class="mt-3 text-xs text-slate-500">{{ collect([$item->event_date, $item->location])->filter()->implode(' · ') }}</p>
        @endif
        <div class="mt-5 flex items-center justify-between gap-3">
            <a href="{{ route('catalog.show', $item) }}" class="text-sm font-semibold text-navy-900 hover:text-brand-600">Detail</a>
            <a href="{{ $item->whatsappUrl() }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#1a9e4b] hover:underline">
                @include('partials.wa-icon', ['class' => 'size-4']) Tanya via WA
            </a>
        </div>
    </div>
</article>

@php
    $tierStyle = [
        'webinar' => 'bg-sky-50 text-sky-700',
        'workshop' => 'bg-brand-50 text-brand-700',
        'inhouse' => 'bg-amber-50 text-amber-700',
    ][$item->type] ?? 'bg-slate-100 text-slate-700';
@endphp
<article class="flex flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-brand-400 hover:shadow-lg">
    <div class="flex items-center justify-between gap-3">
        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $tierStyle }}">{{ $item->typeLabel() }}</span>
        @if ($item->solution)
            <div class="flex h-7 items-center">@include('partials.product-logo', ['solution' => $item->solution, 'class' => 'h-7'])</div>
        @endif
    </div>
    <h3 class="mt-4 text-base leading-snug font-bold text-navy-900">
        <a href="{{ route('catalog.show', $item) }}" class="hover:text-brand-700">{{ $item->title }}</a>
    </h3>
    @if ($item->subtitle)
        <p class="mt-1 text-xs font-semibold text-brand-600">{{ $item->subtitle }}</p>
    @endif
    @if ($item->summary)
        <p class="mt-2 line-clamp-3 text-sm text-slate-600">{{ $item->summary }}</p>
    @endif
    <dl class="mt-4 grid grid-cols-2 gap-x-3 gap-y-2 text-xs">
        @foreach (['Jadwal' => $item->event_date ?: 'Segera diumumkan', 'Format' => $item->location, 'Durasi' => $item->duration, 'Biaya' => $item->price_label] as $term => $value)
            @if ($value)
                <div>
                    <dt class="text-slate-400">{{ $term }}</dt>
                    <dd class="font-semibold text-navy-900">{{ $value }}</dd>
                </div>
            @endif
        @endforeach
    </dl>
    <div class="mt-5 flex flex-1 items-end">
        <a href="{{ route('catalog.show', $item) }}#daftar" class="btn-primary w-full !py-2.5">
            {{ $item->type === 'inhouse' ? 'Ajukan untuk organisasi' : ($item->registration_open ? 'Daftar sekarang' : 'Daftar minat') }}
        </a>
    </div>
</article>

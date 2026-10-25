<div class="my-8 grid gap-4 sm:grid-cols-2">
    @foreach ($gallery as $image)
        <figure @class(['sm:col-span-2' => $loop->first])>
            <a href="{{ $image['url'] }}" target="_blank" rel="noopener" class="block overflow-hidden rounded-xl border border-slate-200 shadow-sm transition hover:shadow-lg">
                <img src="{{ $image['url'] }}" alt="{{ $image['caption'] }}" loading="lazy" class="w-full">
            </a>
            <figcaption class="mt-2 text-xs text-slate-500">{{ $image['caption'] }}@if ($solution && ! str_contains(strtolower($image['caption']), 'grafik')) · tampilan {{ $solution->name }} dengan data demo @endif</figcaption>
        </figure>
    @endforeach
</div>

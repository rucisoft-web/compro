@extends('layouts.app')

@section('title', $article->headline())
@section('description', $article->excerpt(160))
@section('image', $article->imageUrl())
@section('og_type', 'article')

@php
    $solution = $article->solution;
    $gallery = collect($article->gallery)->map(fn ($path) => [
        'url' => \Illuminate\Support\Facades\Storage::disk('public')->url($path),
        'caption' => str(pathinfo($path, PATHINFO_FILENAME))->replaceMatches('/^\d+-/', '')->replace('-', ' ')->ucfirst(),
    ]);
    $galleryShown = false;
    $afterHeading = false;
    $shareUrl = rawurlencode(url()->current());
    $shareText = rawurlencode($article->headline());
@endphp

@section('content')
    <section class="border-b border-slate-100 bg-slate-50">
        <div class="container-page py-10 lg:py-14">
            <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
                <a href="{{ route('articles.index') }}" class="hover:text-brand-600">Artikel</a>
                @if ($solution)
                    <x-heroicon-o-chevron-right class="size-3.5" />
                    <a href="{{ route('articles.index', ['produk' => $solution->slug]) }}" class="hover:text-brand-600">{{ $solution->name }}</a>
                @endif
            </nav>
            <h1 class="mt-4 max-w-4xl text-3xl leading-tight font-extrabold text-navy-900 sm:text-4xl">{{ $article->headline() }}</h1>
            <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                @if ($solution)
                    <span class="rounded-full bg-brand-50 px-3 py-1 font-semibold text-brand-700">{{ $solution->name }}</span>
                @endif
                <span>{{ $article->published_at?->translatedFormat('d F Y') }}</span>
                <span aria-hidden="true">·</span>
                <span>{{ max(1, (int) ceil(str_word_count($article->body) / 200)) }} menit baca</span>
            </div>
        </div>
    </section>

    <div class="container-page grid gap-12 py-12 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <article class="max-w-3xl text-[1.02rem] leading-relaxed text-slate-700">
            @foreach ($article->blocks() as $i => $block)
                @if ($block['type'] === 'h2')
                    @php($afterHeading = str_starts_with(mb_strtolower($block['text']), 'bagaimana'))
                    <h2 class="mt-10 mb-3 text-xl font-extrabold text-navy-900 sm:text-2xl">{{ $block['text'] }}</h2>
                @elseif ($block['type'] === 'ul')
                    <ul class="my-4 space-y-2">
                        @foreach ($block['items'] as $item)
                            <li class="flex gap-3"><x-heroicon-s-check-circle class="mt-1 size-5 shrink-0 text-brand-500" /><span>{{ $item }}</span></li>
                        @endforeach
                    </ul>
                    {{-- Screenshots go right after the list of what the application does. --}}
                    @if ($afterHeading && ! $galleryShown && $gallery->isNotEmpty())
                        @php($galleryShown = true)
                        @include('partials.article-gallery', ['gallery' => $gallery, 'solution' => $solution])
                    @endif
                @elseif ($block['type'] === 'ol')
                    <ol class="my-4 space-y-3">
                        @foreach ($block['items'] as $n => $item)
                            <li class="flex gap-3"><span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-navy-900 text-xs font-bold text-white">{{ $n + 1 }}</span><span class="pt-0.5">{{ $item }}</span></li>
                        @endforeach
                    </ol>
                @else
                    <p class="my-4">{{ $block['text'] }}</p>
                @endif

                @if ($i === 0 && $article->image)
                    <figure class="my-8">
                        <img src="{{ $article->imageUrl() }}" alt="Ringkasan: {{ $article->headline() }}" class="mx-auto w-full max-w-lg rounded-2xl border border-slate-200 shadow-sm">
                    </figure>
                @endif
            @endforeach

            @if (! $galleryShown && $gallery->isNotEmpty())
                @include('partials.article-gallery', ['gallery' => $gallery, 'solution' => $solution])
            @endif

            <div class="mt-10 flex flex-wrap items-center gap-3 border-t border-slate-200 pt-6 text-sm">
                <span class="font-semibold text-navy-900">Bagikan:</span>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener" class="rounded-full border border-slate-200 px-4 py-1.5 hover:border-brand-500">LinkedIn</a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" class="rounded-full border border-slate-200 px-4 py-1.5 hover:border-brand-500">Facebook</a>
                <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" rel="noopener" class="rounded-full border border-slate-200 px-4 py-1.5 hover:border-brand-500">WhatsApp</a>
            </div>
        </article>

        <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
            @if ($solution)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex h-10 items-center">@include('partials.product-logo', ['solution' => $solution, 'class' => 'h-10'])</div>
                    <p class="mt-4 font-bold text-navy-900">{{ $solution->tagline }}</p>
                    <p class="mt-2 text-sm text-slate-600">{{ $solution->summary }}</p>
                    <div class="mt-5 grid gap-2">
                        <a href="{{ route('academy', ['produk' => $solution->slug]) }}" class="btn-primary !py-2.5">Ikuti webinar gratis</a>
                        <a href="{{ route('solutions.show', ['solution' => $solution, 'langkah' => 'demo']) }}#mulai" class="btn-outline !py-2.5">Jadwalkan klinik &amp; demo</a>
                        <a href="{{ route('solutions.show', ['solution' => $solution, 'langkah' => 'asesmen']) }}#mulai" class="text-center text-sm font-semibold text-brand-600 hover:underline">Asesmen kebutuhan gratis</a>
                    </div>
                </div>
            @endif
            @if ($events->isNotEmpty())
                <div class="rounded-2xl bg-navy-950 p-6 text-white">
                    <p class="eyebrow !text-brand-400">Rucisoft Academy</p>
                    <ul class="mt-4 space-y-4 text-sm">
                        @foreach ($events as $event)
                            <li>
                                <span class="text-xs font-semibold text-brand-400">{{ $event->typeLabel() }}</span>
                                <a href="{{ route('catalog.show', $event) }}" class="mt-0.5 block font-semibold hover:text-brand-300">{{ $event->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </aside>
    </div>

    @if ($more->isNotEmpty())
        <section class="border-t border-slate-100 bg-slate-50 py-14">
            <div class="container-page">
                <h2 class="text-2xl font-extrabold text-navy-900">Artikel lainnya</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($more as $other)
                        @include('partials.article-card', ['article' => $other])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

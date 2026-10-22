@extends('layouts.app')

@section('title', $item->title)
@section('description', $item->summary)

@section('content')
    <section class="bg-gradient-to-b from-navy-50 to-white">
        <div class="container-page grid gap-12 py-16 lg:grid-cols-5">
            <div class="lg:col-span-3">
                <a href="{{ route('catalog.index', ['type' => $item->type]) }}" class="inline-flex items-center gap-1 text-sm font-medium text-slate-500 hover:text-brand-600">
                    <x-heroicon-o-arrow-left class="size-4" /> Katalog {{ $item->typeLabel() }}
                </a>
                <span class="mt-6 inline-block rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700">{{ $item->typeLabel() }}</span>
                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-navy-900 sm:text-4xl">{{ $item->title }}</h1>
                @if ($item->summary)
                    <p class="mt-4 text-lg text-slate-600">{{ $item->summary }}</p>
                @endif
                <div class="prose-content mt-8">{!! $item->body !!}</div>
            </div>

            <aside class="lg:col-span-2">
                <div class="sticky top-24 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-navy-900/5">
                    @if ($item->cover_image)
                        <img src="{{ Storage::url($item->cover_image) }}" alt="{{ $item->title }}" class="aspect-[4/3] w-full object-cover">
                    @endif
                    <div class="space-y-4 p-6">
                        @if ($item->solution)
                            <p class="text-sm text-slate-500">Solusi: <a href="{{ route('solutions.show', $item->solution) }}" class="font-semibold text-brand-600 hover:underline">{{ $item->solution->name }}</a></p>
                        @endif
                        @if ($item->event_date)
                            <p class="text-sm text-slate-500">Jadwal: <span class="font-semibold text-navy-900">{{ $item->event_date }}</span></p>
                        @endif
                        @if ($item->location)
                            <p class="text-sm text-slate-500">Lokasi: <span class="font-semibold text-navy-900">{{ $item->location }}</span></p>
                        @endif
                        <a href="{{ $item->whatsappUrl() }}" target="_blank" rel="noopener" class="btn-wa w-full">
                            @include('partials.wa-icon', ['class' => 'size-5']) Tanya via WhatsApp
                        </a>
                        <a href="mailto:{{ \App\Models\Setting::get('email') }}?subject={{ rawurlencode($item->title) }}" class="btn-outline w-full">Kirim Email</a>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="py-16">
            <div class="container-page">
                <h2 class="text-2xl font-extrabold text-navy-900">{{ $item->typeLabel() }} lainnya</h2>
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $other)
                        @include('partials.catalog-card', ['item' => $other])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@extends('layouts.app')

@section('title', $solution->name)
@section('description', $solution->tagline)

@section('content')
    <section class="bg-gradient-to-b from-navy-50 to-white">
        <div class="container-page grid items-center gap-12 py-16 lg:grid-cols-2 lg:py-20">
            <div>
                <a href="{{ route('home') }}#solusi" class="inline-flex items-center gap-1 text-sm font-medium text-slate-500 hover:text-brand-600">
                    <x-heroicon-o-arrow-left class="size-4" /> Semua solusi
                </a>
                <div class="mt-6 flex size-14 items-center justify-center rounded-2xl bg-gradient-to-br from-navy-900 to-brand-600 text-white">
                    <x-dynamic-component :component="$solution->icon ?: 'heroicon-o-squares-2x2'" class="size-7" />
                </div>
                <h1 class="mt-5 text-4xl font-extrabold tracking-tight text-navy-900 sm:text-5xl">{{ $solution->name }}</h1>
                <p class="mt-4 text-lg text-slate-600">{{ $solution->tagline }}</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ \App\Models\Setting::whatsappUrl("Halo Rucisoft, saya ingin demo {$solution->name}.") }}" target="_blank" rel="noopener" class="btn-wa">
                        @include('partials.wa-icon', ['class' => 'size-5']) Jadwalkan Demo
                    </a>
                    <a href="{{ \App\Models\Setting::whatsappUrl("Halo Rucisoft, saya ingin penawaran {$solution->name}.") }}" target="_blank" rel="noopener" class="btn-outline">Minta Penawaran</a>
                </div>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-navy-900/5">
                @if ($solution->image)
                    <img src="{{ Storage::disk('public')->url($solution->image) }}" alt="{{ $solution->name }}" class="mb-6 w-full rounded-2xl">
                @endif
                <p class="text-sm font-bold text-navy-900">Fitur utama</p>
                <ul class="mt-4 space-y-3">
                    @foreach ($solution->features ?? [] as $feature)
                        <li class="flex gap-3 rounded-xl bg-navy-50 px-4 py-3 text-sm font-medium text-navy-900">
                            <x-heroicon-o-check-circle class="size-5 shrink-0 text-brand-500" /> {{ $feature }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    @if ($solution->description)
        <section class="py-16">
            <div class="container-page max-w-3xl prose-content">{!! $solution->description !!}</div>
        </section>
    @endif

    @if ($related->isNotEmpty())
        <section class="py-16">
            <div class="container-page">
                <h2 class="text-2xl font-extrabold text-navy-900">Penawaran terkait</h2>
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        @include('partials.catalog-card', ['item' => $item])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="bg-navy-50 py-16">
        <div class="container-page">
            <h2 class="text-2xl font-extrabold text-navy-900">Solusi lainnya</h2>
            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ($others as $other)
                    <a href="{{ route('solutions.show', $other) }}" class="rounded-2xl bg-white p-5 transition hover:shadow-lg">
                        <x-dynamic-component :component="$other->icon ?: 'heroicon-o-squares-2x2'" class="size-6 text-brand-600" />
                        <p class="mt-3 text-sm font-bold text-navy-900">{{ $other->name }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @include('partials.cta')
@endsection

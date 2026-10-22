@extends('layouts.app')

@section('content')
    {{-- Hero --}}
    <section id="beranda" class="relative overflow-hidden bg-gradient-to-b from-navy-50 to-white">
        <div class="pointer-events-none absolute -top-40 right-0 size-[36rem] rounded-full bg-brand-100/60 blur-3xl"></div>
        <div class="container-page relative grid items-center gap-14 py-16 lg:grid-cols-2 lg:py-24">
            <div>
                <p class="eyebrow">{{ \App\Models\Setting::get('hero_eyebrow') }}</p>
                <h1 class="mt-4 text-4xl leading-tight font-extrabold tracking-tight text-navy-900 sm:text-5xl lg:text-6xl">
                    {{ \App\Models\Setting::get('hero_title') }}
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-relaxed text-slate-600">{{ \App\Models\Setting::get('hero_subtitle') }}</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#solusi" class="btn-primary">Jelajahi Solusi <x-heroicon-o-arrow-right class="size-4" /></a>
                    <a href="{{ \App\Models\Setting::whatsappUrl() }}" target="_blank" rel="noopener" class="btn-outline">Konsultasi Kebutuhan</a>
                </div>
                <ul class="mt-8 flex flex-wrap gap-x-6 gap-y-2 text-sm font-medium text-slate-600">
                    @foreach (array_filter(array_map('trim', explode(',', (string) \App\Models\Setting::get('hero_badges')))) as $badge)
                        <li class="flex items-center gap-1.5"><x-heroicon-o-check-circle class="size-5 text-brand-500" /> {{ $badge }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- Executive Control Center --}}
            <div class="relative">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl shadow-navy-900/10 sm:p-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold tracking-wider text-slate-400 uppercase">Dashboard</p>
                            <p class="text-lg font-bold text-navy-900">Executive Control Center</p>
                        </div>
                        <span class="rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700">Live</span>
                    </div>

                    <div class="mt-6 grid grid-cols-3 gap-3">
                        @foreach ($metrics as $metric)
                            <div class="rounded-2xl bg-navy-50 p-4">
                                <p class="text-2xl font-extrabold text-navy-900 sm:text-3xl">{{ $metric->value }}%</p>
                                <p class="mt-1 text-xs leading-snug text-slate-500">{{ $metric->label }}</p>
                                <div class="mt-3 h-1.5 rounded-full bg-white">
                                    <div class="h-1.5 rounded-full bg-brand-500" style="width: {{ $metric->value }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 rounded-2xl border border-slate-100 p-4">
                        <p class="text-xs font-semibold text-slate-500">Tren kinerja</p>
                        <div class="mt-4 flex h-28 items-end gap-3">
                            @foreach (['Q1' => 52, 'Q2' => 64, 'Q3' => 73, 'Q4' => 86] as $q => $h)
                                <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
                                    <div class="w-full rounded-t-lg bg-gradient-to-t from-navy-900 to-brand-500" style="height: {{ $h }}%"></div>
                                    <span class="text-xs text-slate-400">{{ $q }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-2 rounded-xl bg-amber-50 px-4 py-3 text-sm font-medium text-amber-800">
                        <x-heroicon-o-exclamation-triangle class="size-5" /> {{ \App\Models\Setting::get('dashboard_alert') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Solusi --}}
    <section id="solusi" class="py-20">
        <div class="container-page">
            <div class="max-w-2xl">
                <p class="eyebrow">Solusi</p>
                <h2 class="mt-3 text-3xl font-extrabold text-navy-900 sm:text-4xl">Enam solusi fokus Rucisoft</h2>
                <p class="mt-4 text-slate-600">Software terpadu untuk mengendalikan strategi, kepatuhan, keberlanjutan, layanan korporat, TI, dan pengetahuan perusahaan.</p>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($solutions as $solution)
                    <article class="group flex flex-col rounded-2xl border border-slate-200 p-6 transition hover:-translate-y-1 hover:border-brand-400 hover:shadow-xl hover:shadow-navy-900/5">
                        <div class="flex size-12 items-center justify-center rounded-xl bg-gradient-to-br from-navy-900 to-brand-600 text-white">
                            <x-dynamic-component :component="$solution->icon ?: 'heroicon-o-squares-2x2'" class="size-6" />
                        </div>
                        <h3 class="mt-5 text-lg font-bold text-navy-900">{{ $solution->name }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $solution->tagline }}</p>
                        <ul class="mt-5 flex-1 space-y-2 text-sm text-slate-700">
                            @foreach ($solution->features ?? [] as $feature)
                                <li class="flex gap-2"><x-heroicon-o-check-circle class="mt-0.5 size-4 shrink-0 text-brand-500" /> {{ $feature }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ route('solutions.show', $solution) }}" class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-brand-600 group-hover:gap-2">
                            Lihat penawaran <x-heroicon-o-arrow-right class="size-4 transition-all" />
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Knowledge-to-Implementation --}}
    <section id="model" class="bg-navy-950 py-20 text-white">
        <div class="container-page">
            <div class="max-w-2xl">
                <p class="eyebrow !text-brand-400">Knowledge-to-Implementation</p>
                <h2 class="mt-3 text-3xl font-extrabold sm:text-4xl">Dari pemahaman hingga penerapan</h2>
                <p class="mt-4 text-slate-300">Buku, workshop, dan implementasi software yang saling terhubung agar perubahan benar-benar berjalan.</p>
            </div>
            <div class="mt-12 grid gap-6 md:grid-cols-3">
                @foreach ($stages as $stage)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-7">
                        <p class="text-4xl font-extrabold text-brand-400">{{ $stage->number }}</p>
                        <p class="mt-4 text-xs font-bold tracking-[0.2em] text-slate-400 uppercase">{{ $stage->label }}</p>
                        <h3 class="mt-1 text-xl font-bold">{{ $stage->title }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-slate-300">{{ $stage->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Katalog --}}
    <section id="katalog" class="py-20" x-data="{ filter: 'semua' }">
        <div class="container-page">
            <div class="flex flex-wrap items-end justify-between gap-6">
                <div class="max-w-2xl">
                    <p class="eyebrow">Katalog</p>
                    <h2 class="mt-3 text-3xl font-extrabold text-navy-900 sm:text-4xl">Software, buku, dan workshop</h2>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach (['semua' => 'Semua'] + \App\Models\CatalogItem::TYPES as $key => $label)
                        <button type="button" @click="filter = '{{ $key }}'"
                                :class="filter === '{{ $key }}' ? 'bg-navy-900 text-white border-navy-900' : 'bg-white text-slate-600 border-slate-200 hover:border-brand-500'"
                                class="rounded-full border px-4 py-2 text-sm font-semibold transition">{{ $label }}</button>
                    @endforeach
                </div>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($catalog as $item)
                    <div x-show="filter === 'semua' || filter === '{{ $item->type }}'" x-transition>
                        @include('partials.catalog-card', ['item' => $item])
                    </div>
                @empty
                    <p class="text-slate-500">Katalog akan segera tersedia.</p>
                @endforelse
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('catalog.index') }}" class="btn-outline">Lihat seluruh katalog <x-heroicon-o-arrow-right class="size-4" /></a>
            </div>
        </div>
    </section>

    {{-- Tentang --}}
    <section id="tentang" class="bg-navy-50 py-20">
        <div class="container-page grid gap-12 lg:grid-cols-2">
            <div>
                <p class="eyebrow">Tentang Rucisoft</p>
                <h2 class="mt-3 text-3xl font-extrabold text-navy-900 sm:text-4xl">{{ \App\Models\Setting::get('about_title') }}</h2>
                <p class="mt-5 leading-relaxed text-slate-600">{{ \App\Models\Setting::get('about_body') }}</p>

                <p class="mt-8 text-sm font-bold text-navy-900">Dipercaya untuk kebutuhan</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($segments as $segment)
                        <span class="rounded-full border border-navy-100 bg-white px-4 py-2 text-sm font-medium text-navy-800">{{ $segment->name }}</span>
                    @endforeach
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ($principles as $principle)
                    <div class="rounded-2xl bg-white p-6 shadow-sm">
                        <x-heroicon-o-shield-check class="size-8 text-brand-600" />
                        <h3 class="mt-4 font-bold text-navy-900">{{ $principle->title }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $principle->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('partials.cta')
@endsection

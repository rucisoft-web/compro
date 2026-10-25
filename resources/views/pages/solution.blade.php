@extends('layouts.app')

@section('title', $solution->name)
@section('description', $solution->tagline)

@section('content')
    @php($fallbackImage = $solution->fallbackImageUrl())

    {{-- Hero --}}
    <section class="border-b border-slate-100 bg-gradient-to-b from-navy-50 to-white">
        <div class="container-page py-10 lg:py-14">
            <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
                <a href="{{ route('home') }}" class="hover:text-brand-600">Beranda</a>
                <x-heroicon-o-chevron-right class="size-3.5" />
                <a href="{{ route('home') }}#solusi" class="hover:text-brand-600">Solusi</a>
                <x-heroicon-o-chevron-right class="size-3.5" />
                <span class="font-medium text-navy-900">{{ $solution->name }}</span>
            </nav>

            <div class="grid items-center gap-10 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <div class="flex h-14 items-center">
                        @include('partials.product-logo', ['solution' => $solution, 'class' => 'h-14'])
                    </div>
                    <h1 class="mt-5 text-3xl font-extrabold tracking-tight text-navy-900 sm:text-4xl">{{ $solution->name }}</h1>
                    <p class="mt-3 text-lg leading-relaxed text-slate-600">{{ $solution->tagline }}</p>
                    @if ($solution->problem_statement)
                        <p class="mt-3 border-l-4 border-brand-400 pl-3 text-sm text-slate-600">{{ $solution->problem_statement }}</p>
                    @endif

                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="#mulai" class="btn-primary">Jadwalkan demo</a>
                        <a href="#academy" class="btn-outline">Ikuti workshop</a>
                        @if ($solution->website_url)
                            <a href="{{ $solution->website_url }}" target="_blank" rel="noopener" class="btn-outline">
                                Coba demo aplikasi <x-heroicon-o-arrow-top-right-on-square class="size-4" />
                            </a>
                        @endif
                    </div>
                    <a href="#mulai" @click="$dispatch('open-tab', 'proposal')" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-brand-600 hover:underline">
                        Kebutuhan sudah jelas? Minta proposal <x-heroicon-o-arrow-right class="size-4" />
                    </a>
                </div>

                <div class="lg:col-span-7">
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-navy-900/10">
                        <div class="flex items-center gap-1.5 border-b border-slate-100 bg-slate-50 px-4 py-2.5">
                            <span class="size-2.5 rounded-full bg-red-400"></span>
                            <span class="size-2.5 rounded-full bg-amber-400"></span>
                            <span class="size-2.5 rounded-full bg-emerald-400"></span>
                            @if ($solution->website_url)
                                <span class="ml-3 truncate text-xs text-slate-400">{{ parse_url($solution->website_url, PHP_URL_HOST) }}</span>
                            @endif
                        </div>
                        @if ($solution->imageUrl())
                            <img src="{{ $solution->imageUrl() }}" alt="Tampilan {{ $solution->name }}" class="aspect-[16/9] w-full object-cover object-top"
                                 @if ($fallbackImage) onerror="this.onerror=null;this.src='{{ $fallbackImage }}'" @endif>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Fitur & deskripsi --}}
    <section class="py-12">
        <div class="container-page">
            <div class="max-w-2xl">
                <p class="eyebrow">Fitur utama</p>
                <h2 class="mt-2 text-2xl font-extrabold text-navy-900 sm:text-3xl">Apa yang bisa dilakukan {{ $solution->name }}</h2>
            </div>
            <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($solution->features ?? [] as $feature)
                    <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-4">
                        <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                            <x-heroicon-o-check class="size-4" />
                        </span>
                        <p class="text-sm font-semibold text-navy-900">{{ $feature }}</p>
                    </div>
                @endforeach
            </div>

            @if ($solution->description)
                <div class="mt-10 grid gap-8 lg:grid-cols-3">
                    <div class="prose-content lg:col-span-2">{!! $solution->description !!}</div>
                    <aside class="h-fit rounded-2xl bg-navy-900 p-6 text-white">
                        <p class="text-lg font-bold">Ingin melihat langsung?</p>
                        <p class="mt-2 text-sm text-slate-300">Kami siap mendemokan {{ $solution->name }} sesuai kebutuhan organisasi Anda, termasuk pendampingan implementasi.</p>
                        <div class="mt-5 space-y-2">
                            <a href="#mulai" class="btn-primary w-full">Jadwalkan klinik & demo</a>
                            @if ($solution->website_url)
                                <a href="{{ $solution->website_url }}" target="_blank" rel="noopener" class="btn w-full border border-white/20 text-white hover:bg-white/10">
                                    Buka {{ parse_url($solution->website_url, PHP_URL_HOST) }}
                                </a>
                            @endif
                        </div>
                    </aside>
                </div>
            @endif
        </div>
    </section>

    @include('partials.solution-funnel')

    @if ($related->isNotEmpty())
        <section class="pb-12">
            <div class="container-page">
                <h2 class="text-xl font-extrabold text-navy-900">Buku &amp; panduan terkait</h2>
                <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($related as $item)
                        @include('partials.catalog-card', ['item' => $item])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Solusi lainnya --}}
    <section class="border-t border-slate-100 bg-navy-50 py-12">
        <div class="container-page">
            <h2 class="text-xl font-extrabold text-navy-900">Produk Rucisoft lainnya</h2>
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ($others as $other)
                    <a href="{{ route('solutions.show', $other) }}" class="group flex flex-col rounded-2xl border border-transparent bg-white p-5 transition hover:border-brand-400 hover:shadow-lg">
                        <div class="flex h-10 items-center">
                            @include('partials.product-logo', ['solution' => $other, 'class' => 'h-10'])
                        </div>
                        <p class="mt-4 text-sm font-bold text-navy-900">{{ $other->name }}</p>
                        <p class="mt-1 line-clamp-2 text-xs leading-relaxed text-slate-500">{{ $other->tagline }}</p>
                        <span class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-brand-600 group-hover:gap-2">
                            Lihat detail <x-heroicon-o-arrow-right class="size-3.5 transition-all" />
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

@endsection

@extends('layouts.app')

@section('content')
    {{-- Hero --}}
    <section id="beranda" class="relative overflow-hidden bg-gradient-to-b from-navy-50 to-white">
        <div class="pointer-events-none absolute -top-40 right-0 size-[36rem] rounded-full bg-brand-100/60 blur-3xl"></div>
        <div class="container-page relative grid items-center gap-10 py-12 lg:grid-cols-2 lg:py-20">
            <div>
                <p class="eyebrow">{{ \App\Models\Setting::get('hero_eyebrow') }}</p>
                <h1 class="mt-4 text-4xl leading-tight font-extrabold tracking-tight text-navy-900 sm:text-5xl lg:text-6xl">
                    {{ \App\Models\Setting::get('hero_title') }}
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-relaxed text-slate-600">{{ \App\Models\Setting::get('hero_subtitle') }}</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#solusi" class="btn-primary">Jelajahi Produk <x-heroicon-o-arrow-right class="size-4" /></a>
                    <a href="{{ route('academy') }}" class="btn-outline">Ikuti Webinar Gratis</a>
                    <a href="#demo" class="btn-outline">Jadwalkan Demo</a>
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
    <section id="solusi" class="py-16">
        <div class="container-page">
            <div class="max-w-2xl">
                <p class="eyebrow">Solusi</p>
                <h2 class="mt-3 text-3xl font-extrabold text-navy-900 sm:text-4xl">Produk aplikasi Rucisoft</h2>
                <p class="mt-4 text-slate-600">Aplikasi siap pakai untuk strategi perusahaan, akreditasi perguruan tinggi, fasilitas kantor, kantor notaris, klinik, hingga rapat Direksi dan Dewan Komisaris.</p>
            </div>

            <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($solutions as $solution)
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
                @endforeach
            </div>
        </div>
    </section>

    {{-- Cara kerja sama --}}
    <section id="cara-kerja" class="bg-navy-950 py-14 text-white">
        <div class="container-page">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div class="max-w-2xl">
                    <p class="eyebrow !text-brand-400">Cara bekerja sama</p>
                    <h2 class="mt-2 text-3xl font-extrabold sm:text-4xl">Dari memahami masalah sampai sistem benar-benar dipakai</h2>
                    <p class="mt-3 text-slate-300">Mulai dari tahap mana pun. Organisasi yang kebutuhannya sudah jelas bisa langsung ke demo dan proposal.</p>
                </div>
                <a href="#demo" class="btn-primary">Langsung demo & proposal</a>
            </div>
            <ol @class(['mt-8 grid gap-3 sm:grid-cols-2', 'lg:grid-cols-3' => $stages->count() <= 3, 'lg:grid-cols-6' => $stages->count() > 3])>
                @foreach ($stages as $stage)
                    <li class="rounded-2xl border border-white/10 bg-white/5 p-5">
                        <p class="text-3xl font-extrabold text-brand-400">{{ $stage->number }}</p>
                        <p class="mt-3 text-[11px] font-bold tracking-[0.2em] text-slate-400 uppercase">{{ $stage->label }}</p>
                        <h3 class="mt-1 font-bold">{{ $stage->title }}</h3>
                        <p class="mt-2 text-xs leading-relaxed text-slate-300">{{ $stage->description }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Academy --}}
    <section id="model" class="py-14">
        <div class="container-page">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div class="max-w-2xl">
                    <p class="eyebrow">Rucisoft Academy</p>
                    <h2 class="mt-2 text-3xl font-extrabold text-navy-900 sm:text-4xl">Workshop yang menghasilkan pekerjaan nyata</h2>
                    <p class="mt-3 text-slate-600">Webinar pengenalan gratis, workshop praktik langsung di aplikasi, hingga workshop in-house dan pilot untuk organisasi Anda.</p>
                </div>
                <a href="{{ route('academy') }}" class="btn-outline">Lihat semua program <x-heroicon-o-arrow-right class="size-4" /></a>
            </div>
            <div class="mt-8 grid gap-4 md:grid-cols-3">
                @foreach ([['Webinar pengenalan', 'Gratis, dengan pendaftaran', 'Memahami masalah dan pendekatan penyelesaiannya lewat studi kasus.', 'heroicon-o-video-camera'], ['Workshop praktik', 'Berbayar per peserta atau per tim', 'Praktik terpandu di aplikasi; hasil kerja bisa diekspor dan dibawa pulang.', 'heroicon-o-wrench-screwdriver'], ['In-house & pilot', 'Sesuai lingkup organisasi', 'Workshop khusus tim Anda, dilanjutkan pilot dengan ukuran keberhasilan.', 'heroicon-o-building-office']] as [$title, $model, $desc, $icon])
                    <div class="flex gap-4 rounded-2xl border border-slate-200 p-5">
                        <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600"><x-dynamic-component :component="$icon" class="size-6" /></span>
                        <div>
                            <p class="font-bold text-navy-900">{{ $title }}</p>
                            <p class="text-xs font-semibold text-brand-600">{{ $model }}</p>
                            <p class="mt-2 text-sm text-slate-600">{{ $desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($events->isNotEmpty())
                <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($events as $item)
                        @include('partials.academy-card', ['item' => $item])
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Katalog --}}
    <section id="katalog" class="py-16" x-data="{ filter: 'semua' }">
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

            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($catalog as $item)
                    <div x-show="filter === 'semua' || filter === '{{ $item->type }}'" x-transition>
                        @include('partials.catalog-card', ['item' => $item])
                    </div>
                @empty
                    <p class="text-slate-500">Katalog akan segera tersedia.</p>
                @endforelse
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('catalog.index') }}" class="btn-outline">Lihat seluruh katalog <x-heroicon-o-arrow-right class="size-4" /></a>
            </div>
        </div>
    </section>

    {{-- Tentang --}}
    <section id="tentang" class="bg-navy-50 py-16">
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

    {{-- Demo & kontak --}}
    <section id="demo" class="scroll-mt-20 pb-14">
        <div class="container-page">
            <div class="grid overflow-hidden rounded-3xl border border-slate-200 shadow-xl shadow-navy-900/5 lg:grid-cols-5">
                <div class="relative bg-gradient-to-br from-navy-900 via-navy-800 to-brand-700 p-8 text-white lg:col-span-2">
                    <p class="eyebrow !text-brand-400">Klinik kebutuhan & demo</p>
                    <h2 class="mt-3 text-2xl font-extrabold sm:text-3xl">{{ \App\Models\Setting::get('cta_title') }}</h2>
                    <p class="mt-3 text-sm text-slate-200">Sesi 30-45 menit untuk memetakan masalah, dampak, pengguna, sponsor, kesiapan, dan proses pengadaan, dilanjutkan demo sesuai kebutuhan organisasi Anda.</p>
                    <ul class="mt-6 space-y-2 text-sm text-slate-200">
                        @foreach (['Libatkan pengambil keputusan dan PIC implementasi', 'Dukungan evaluasi TI, keamanan, pengadaan, dan legal', 'Proposal dengan komponen biaya yang terpisah jelas'] as $point)
                            <li class="flex gap-2"><x-heroicon-o-check-circle class="mt-0.5 size-4 shrink-0 text-brand-400" /> {{ $point }}</li>
                        @endforeach
                    </ul>
                    <div class="mt-8 space-y-2 text-sm">
                        <a href="{{ \App\Models\Setting::whatsappUrl() }}" target="_blank" rel="noopener" class="flex items-center gap-2 hover:text-brand-400">@include('partials.wa-icon', ['class' => 'size-4']) WhatsApp {{ \App\Models\Setting::get('whatsapp_display') }}</a>
                        <a href="mailto:{{ \App\Models\Setting::get('email') }}" class="flex items-center gap-2 hover:text-brand-400"><x-heroicon-o-envelope class="size-4" /> {{ \App\Models\Setting::get('email') }}</a>
                    </div>
                </div>
                <div class="bg-white p-8 lg:col-span-3">
                    @include('partials.lead-form', ['kind' => 'demo', 'formId' => 'home_demo', 'chooseSolution' => true, 'button' => 'Jadwalkan klinik & demo', 'messageLabel' => 'Masalah yang ingin diselesaikan'])
                </div>
            </div>
        </div>
    </section>
@endsection

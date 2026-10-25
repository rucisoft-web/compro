@php($home = request()->routeIs('home') ? '' : route('home'))
<header x-data="{ open: false }" class="sticky top-0 z-40 border-b border-slate-100 bg-white/90 backdrop-blur">
    <div class="container-page flex h-18 items-center justify-between gap-6 py-3">
        <a href="{{ route('home') }}" class="shrink-0">
            <img src="{{ asset('images/logo.svg') }}" alt="Rucisoft Indonesia" class="h-10 w-auto">
        </a>

        <nav class="hidden items-center gap-7 text-sm font-medium text-slate-600 lg:flex">
            <a href="{{ $home }}#solusi" class="hover:text-brand-600">Produk</a>
            <a href="{{ route('academy') }}" class="hover:text-brand-600">Academy</a>
            <a href="{{ $home }}#cara-kerja" class="hover:text-brand-600">Cara Kerja Sama</a>
            <a href="{{ route('catalog.index') }}" class="hover:text-brand-600">Katalog</a>
            <a href="{{ $home }}#tentang" class="hover:text-brand-600">Tentang</a>
            <a href="{{ $home }}#demo" class="hover:text-brand-600">Kontak</a>
        </nav>

        <div class="hidden items-center gap-3 lg:flex">
            <a href="{{ route('academy') }}" class="btn-outline !py-2.5">Webinar Gratis</a>
            <a href="{{ $home }}#demo" class="btn-primary !py-2.5">Jadwalkan Demo</a>
        </div>

        <button type="button" class="rounded-lg p-2 text-navy-900 lg:hidden" @click="open = !open" aria-label="Menu">
            <x-heroicon-o-bars-3 class="size-6" x-show="!open" />
            <x-heroicon-o-x-mark class="size-6" x-show="open" x-cloak />
        </button>
    </div>

    <div x-show="open" x-cloak x-transition class="border-t border-slate-100 bg-white lg:hidden">
        <nav class="container-page flex flex-col gap-1 py-4 text-sm font-medium text-slate-700" @click="open = false">
            <a href="{{ $home }}#beranda" class="rounded-lg px-3 py-2 hover:bg-slate-50">Beranda</a>
            <a href="{{ $home }}#solusi" class="rounded-lg px-3 py-2 hover:bg-slate-50">Produk</a>
            <a href="{{ route('academy') }}" class="rounded-lg px-3 py-2 hover:bg-slate-50">Academy</a>
            <a href="{{ $home }}#cara-kerja" class="rounded-lg px-3 py-2 hover:bg-slate-50">Cara Kerja Sama</a>
            <a href="{{ route('catalog.index') }}" class="rounded-lg px-3 py-2 hover:bg-slate-50">Katalog</a>
            <a href="{{ $home }}#tentang" class="rounded-lg px-3 py-2 hover:bg-slate-50">Tentang</a>
            <a href="{{ $home }}#demo" class="rounded-lg px-3 py-2 hover:bg-slate-50">Kontak</a>
            <a href="{{ \App\Models\Setting::whatsappUrl() }}" target="_blank" rel="noopener" class="btn-wa mt-3">
                @include('partials.wa-icon', ['class' => 'size-4']) WhatsApp Konsultasi
            </a>
        </nav>
    </div>
</header>

@php($footerSolutions = \App\Models\Solution::active()->get(['name', 'slug']))
<footer id="kontak" class="bg-navy-950 text-slate-300">
    <div class="container-page grid gap-10 py-16 md:grid-cols-2 lg:grid-cols-4">
        <div>
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-mark.svg') }}" alt="" class="size-10">
                <div>
                    <p class="text-lg font-extrabold text-white">Rucisoft</p>
                    <p class="text-[10px] font-semibold tracking-[0.3em] text-brand-400">INDONESIA</p>
                </div>
            </div>
            <p class="mt-5 text-sm leading-relaxed text-slate-400">Buku, workshop, dan software terpadu untuk strategi, kepatuhan, dan kinerja organisasi.</p>
        </div>

        <div>
            <p class="mb-4 text-sm font-bold text-white">Solusi</p>
            <ul class="space-y-2 text-sm">
                @foreach ($footerSolutions as $s)
                    <li><a href="{{ route('solutions.show', $s) }}" class="hover:text-brand-400">{{ $s->name }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <p class="mb-4 text-sm font-bold text-white">Penawaran</p>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('catalog.index', ['type' => 'buku']) }}" class="hover:text-brand-400">Buku &amp; Panduan</a></li>
                <li><a href="{{ route('catalog.index', ['type' => 'workshop']) }}" class="hover:text-brand-400">Workshop</a></li>
                <li><a href="{{ route('catalog.index', ['type' => 'software']) }}" class="hover:text-brand-400">Implementasi Software</a></li>
                <li><a href="{{ route('academy') }}" class="hover:text-brand-400">Rucisoft Academy</a></li>
                <li><a href="{{ route('articles.index') }}" class="hover:text-brand-400">Artikel</a></li>
            </ul>
        </div>

        <div>
            <p class="mb-4 text-sm font-bold text-white">Kontak</p>
            <ul class="space-y-3 text-sm">
                <li class="flex items-center gap-2">
                    <x-heroicon-o-envelope class="size-4 text-brand-400" />
                    <a href="mailto:{{ \App\Models\Setting::get('email') }}" class="hover:text-brand-400">{{ \App\Models\Setting::get('email') }}</a>
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-brand-400">@include('partials.wa-icon', ['class' => 'size-4'])</span>
                    <a href="{{ \App\Models\Setting::whatsappUrl() }}" target="_blank" rel="noopener" class="hover:text-brand-400">WhatsApp {{ \App\Models\Setting::get('whatsapp_display', '0811-9989-2939') }}</a>
                </li>
                <li class="flex items-center gap-2">
                    <x-heroicon-o-map-pin class="size-4 text-brand-400" />
                    <span>{{ \App\Models\Setting::get('address') }}</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10">
        <p class="container-page py-6 text-xs text-slate-500">&copy; {{ date('Y') }} Rucisoft Indonesia. Hak cipta dilindungi.</p>
    </div>
</footer>

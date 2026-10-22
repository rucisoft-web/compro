<section class="py-20">
    <div class="container-page">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-navy-900 via-navy-800 to-brand-700 px-6 py-14 text-center sm:px-12">
            <div class="pointer-events-none absolute -top-24 -right-24 size-72 rounded-full bg-brand-400/20 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-24 size-72 rounded-full bg-brand-500/20 blur-3xl"></div>
            <h2 class="relative mx-auto max-w-2xl text-3xl font-extrabold text-white sm:text-4xl">{{ \App\Models\Setting::get('cta_title') }}</h2>
            <p class="relative mx-auto mt-4 max-w-xl text-slate-200">Ceritakan tantangan organisasi Anda. Tim kami akan membantu memetakan solusi yang tepat.</p>
            <div class="relative mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ \App\Models\Setting::whatsappUrl() }}" target="_blank" rel="noopener" class="btn-wa">
                    @include('partials.wa-icon', ['class' => 'size-5']) Hubungi via WhatsApp
                </a>
                <a href="mailto:{{ \App\Models\Setting::get('email') }}" class="btn border border-white/30 text-white hover:bg-white/10">
                    <x-heroicon-o-envelope class="size-5" /> {{ \App\Models\Setting::get('email') }}
                </a>
            </div>
        </div>
    </div>
</section>

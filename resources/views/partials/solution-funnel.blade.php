{{-- Funnel sections for a product page: path, academy, pilot, proposal, and the start forms. --}}
@php
    $steps = [
        ['Pahami masalahnya', 'Webinar & materi edukasi gratis', '#academy', 'heroicon-o-light-bulb'],
        ['Asesmen kebutuhan', 'Ketahui titik perbaikan utama', '#mulai', 'heroicon-o-clipboard-document-list'],
        ['Workshop praktik', 'Hasilkan rancangan kerja di aplikasi', '#academy', 'heroicon-o-wrench-screwdriver'],
        ['Klinik & demo', '30-45 menit bersama sponsor/PIC', '#mulai', 'heroicon-o-presentation-chart-bar'],
        ['Pilot terukur', 'Buktikan manfaat dengan ukuran jelas', '#pilot', 'heroicon-o-beaker'],
        ['Pembelian & pendampingan', 'Implementasi sampai benar-benar dipakai', '#biaya', 'heroicon-o-rocket-launch'],
    ];
    $tabs = [
        'demo' => ['Klinik & demo', 'Jadwalkan klinik kebutuhan 30-45 menit dan demo khusus. Sebaiknya libatkan pengambil keputusan dan PIC implementasi.', 'Jadwalkan klinik & demo', 'Waktu yang Anda inginkan & siapa yang akan hadir'],
        'pilot' => ['Ajukan pilot', 'Pilot terbatas dengan pendampingan, ukuran keberhasilan, dan jadwal evaluasi yang disepakati sebelum dimulai.', 'Ajukan pilot', 'Unit yang ingin diuji & target waktu'],
        'proposal' => ['Minta proposal', 'Kebutuhan sudah jelas? Kami siapkan proposal yang memisahkan lisensi, implementasi, migrasi, pelatihan, dan pendampingan.', 'Minta proposal', 'Lingkup, jumlah pengguna/entitas, dan proses pengadaan'],
        'asesmen' => ['Asesmen kebutuhan', 'Belum yakin? Jawab beberapa pertanyaan singkat, kami bantu menyarankan langkah yang paling sesuai.', 'Kirim asesmen', 'Catatan tambahan'],
    ];
    $openTab = collect(array_keys($tabs))->first(fn ($kind) => $errors->getBag('sol_'.$kind)->any()) ?? 'demo';
@endphp

{{-- Jalur --}}
<section class="bg-navy-950 py-12 text-white">
    <div class="container-page">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="max-w-2xl">
                <p class="eyebrow !text-brand-400">Jalur menuju penerapan</p>
                <h2 class="mt-2 text-2xl font-extrabold sm:text-3xl">Mulai dari tahap mana pun yang sesuai</h2>
            </div>
            <a href="#mulai" class="text-sm font-semibold text-brand-400 hover:underline">Sudah siap? Langsung demo & proposal →</a>
        </div>
        <ol class="mt-8 grid gap-3 sm:grid-cols-2 lg:grid-cols-6">
            @foreach ($steps as [$title, $desc, $href, $icon])
                <li>
                    <a href="{{ $href }}" @if ($href === '#mulai') @click="$dispatch('open-tab', '{{ $loop->iteration === 2 ? 'asesmen' : 'demo' }}')" @endif
                       class="group flex h-full flex-col rounded-2xl border border-white/10 bg-white/5 p-4 transition hover:border-brand-400 hover:bg-white/10">
                        <div class="flex items-center justify-between">
                            <span class="flex size-9 items-center justify-center rounded-lg bg-brand-500/20 text-brand-400"><x-dynamic-component :component="$icon" class="size-5" /></span>
                            <span class="text-xs font-bold text-slate-500">0{{ $loop->iteration }}</span>
                        </div>
                        <p class="mt-3 text-sm font-bold">{{ $title }}</p>
                        <p class="mt-1 text-xs text-slate-400">{{ $desc }}</p>
                    </a>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- Academy --}}
<section id="academy" class="scroll-mt-20 py-12">
    <div class="container-page">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="eyebrow">Rucisoft Academy</p>
                <h2 class="mt-2 text-2xl font-extrabold text-navy-900 sm:text-3xl">Webinar & workshop {{ $solution->name }}</h2>
            </div>
            <a href="{{ route('academy', ['produk' => $solution->slug]) }}" class="text-sm font-semibold text-brand-600 hover:underline">Semua program Academy →</a>
        </div>
        @if ($events->isNotEmpty())
            <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($events as $item)
                    @include('partials.academy-card', ['item' => $item])
                @endforeach
            </div>
        @else
            <div class="mt-6 grid items-center gap-6 rounded-2xl border border-slate-200 p-6 lg:grid-cols-2">
                <div>
                    <p class="text-lg font-bold text-navy-900">Jadwal workshop {{ $solution->name }} segera dibuka</p>
                    <p class="mt-2 text-sm text-slate-600">Daftarkan minat Anda. Kami kabari lebih dulu saat webinar pengenalan dan workshop praktik dibuka, atau kami siapkan workshop in-house untuk tim Anda.</p>
                </div>
                @include('partials.lead-form', ['kind' => 'minat', 'formId' => 'sol_minat', 'solution' => $solution, 'button' => 'Daftarkan minat', 'messageLabel' => 'Topik yang ingin dipelajari'])
            </div>
        @endif
    </div>
</section>

{{-- Pilot & biaya --}}
<section class="border-t border-slate-100 bg-navy-50 py-12">
    <div class="container-page grid gap-8 lg:grid-cols-2">
        <div id="pilot" class="scroll-mt-20">
            <p class="eyebrow">Pilot terukur</p>
            <h2 class="mt-2 text-2xl font-extrabold text-navy-900">Buktikan manfaatnya sebelum memutuskan</h2>
            <p class="mt-2 text-sm text-slate-600">Bukan sekadar akun percobaan. Pilot didampingi, dibatasi lingkupnya, dan jadwal evaluasi serta pembahasan pembelian disepakati sebelum dimulai.</p>
            <div class="mt-5 overflow-hidden rounded-xl border border-slate-200 bg-white">
                <table class="w-full text-left text-sm">
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($solution->pilotScope() as $row)
                            <tr>
                                <th class="w-40 px-4 py-3 align-top font-semibold text-navy-900">{{ $row['element'] ?? '' }}</th>
                                <td class="px-4 py-3 text-slate-600">{{ $row['scope'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="#mulai" @click="$dispatch('open-tab', 'pilot')" class="btn-primary mt-5 !py-2.5">Ajukan pilot</a>
        </div>
        <div id="biaya" class="scroll-mt-20">
            <p class="eyebrow">Transparan sejak awal</p>
            <h2 class="mt-2 text-2xl font-extrabold text-navy-900">Jelas apa yang dibayar dan apa yang diterima</h2>
            <p class="mt-2 text-sm text-slate-600">Proposal kami memisahkan biaya awal, biaya berulang, cakupan dukungan, dan hasil implementasi.</p>
            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                @foreach (\App\Models\Solution::PROPOSAL_COMPONENTS as [$title, $desc, $icon])
                    <div class="flex gap-3 rounded-xl bg-white p-4 shadow-sm">
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600"><x-dynamic-component :component="$icon" class="size-5" /></span>
                        <div>
                            <p class="text-sm font-bold text-navy-900">{{ $title }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">{{ $desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="#mulai" @click="$dispatch('open-tab', 'proposal')" class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-brand-600 hover:underline">Minta proposal {{ $solution->name }} <x-heroicon-o-arrow-right class="size-4" /></a>
        </div>
    </div>
</section>

{{-- Mulai --}}
<section id="mulai" class="scroll-mt-20 py-12" x-data="{ tab: '{{ $openTab }}' }" @open-tab.window="tab = $event.detail">
    <div class="container-page grid gap-8 lg:grid-cols-3">
        <div>
            <p class="eyebrow">Mulai sekarang</p>
            <h2 class="mt-2 text-2xl font-extrabold text-navy-900">Pilih langkah berikutnya</h2>
            <div class="mt-5 space-y-2">
                @foreach ($tabs as $kind => [$label, $desc])
                    <button type="button" @click="tab = '{{ $kind }}'"
                            :class="tab === '{{ $kind }}' ? 'border-brand-500 bg-brand-50' : 'border-slate-200 bg-white hover:border-brand-400'"
                            class="w-full rounded-xl border p-4 text-left transition">
                        <p class="text-sm font-bold text-navy-900">{{ $label }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $desc }}</p>
                    </button>
                @endforeach
            </div>
            <a href="{{ \App\Models\Setting::whatsappUrl("Halo Rucisoft, saya ingin bertanya tentang {$solution->name}.") }}" target="_blank" rel="noopener" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-[#1a9e4b] hover:underline">
                @include('partials.wa-icon', ['class' => 'size-4']) Atau tanya langsung via WhatsApp
            </a>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xl shadow-navy-900/5 lg:col-span-2">
            @foreach ($tabs as $kind => [$label, $desc, $button, $messageLabel])
                <div x-show="tab === '{{ $kind }}'" @if ($kind !== $openTab) x-cloak @endif>
                    <p class="mb-5 text-lg font-extrabold text-navy-900">{{ $label }} {{ $solution->name }}</p>
                    @include('partials.lead-form', ['kind' => $kind, 'formId' => 'sol_'.$kind, 'solution' => $solution, 'assessment' => $kind === 'asesmen', 'button' => $button, 'messageLabel' => $messageLabel])
                </div>
            @endforeach
        </div>
    </div>
</section>

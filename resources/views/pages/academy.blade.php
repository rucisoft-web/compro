@extends('layouts.app')

@section('title', 'Rucisoft Academy')
@section('description', 'Webinar, workshop praktik, dan workshop in-house Rucisoft Academy. Peserta pulang membawa hasil kerja nyata yang bisa dilanjutkan di aplikasinya.')

@section('content')
    @php
        $tiers = [
            'webinar' => ['icon' => 'heroicon-o-video-camera', 'title' => 'Webinar pengenalan', 'for' => 'Untuk yang baru mengenal masalah dan solusinya', 'model' => 'Gratis, dengan pendaftaran', 'desc' => '60-90 menit studi kasus: mengapa masalah terjadi dan bagaimana pendekatan penyelesaiannya.'],
            'workshop' => ['icon' => 'heroicon-o-wrench-screwdriver', 'title' => 'Workshop praktik', 'for' => 'Untuk profesional atau tim yang ingin menghasilkan rancangan kerja', 'model' => 'Berbayar per peserta atau per tim', 'desc' => 'Setengah hari atau satu hari praktik terpandu langsung di aplikasi. Hasilnya bisa diekspor dan dibawa pulang.'],
            'inhouse' => ['icon' => 'heroicon-o-building-office', 'title' => 'In-house & pilot', 'for' => 'Untuk organisasi yang sedang mempertimbangkan penerapan', 'model' => 'Berbayar sesuai lingkup', 'desc' => 'Workshop khusus tim Anda dilanjutkan pilot terbatas dengan ukuran keberhasilan dan titik keputusan yang jelas.'],
        ];
    @endphp

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-navy-950 text-white">
        <div class="pointer-events-none absolute -top-32 right-0 size-[30rem] rounded-full bg-brand-500/20 blur-3xl"></div>
        <div class="container-page relative grid items-center gap-10 py-14 lg:grid-cols-2 lg:py-16">
            <div>
                <p class="eyebrow !text-brand-400">Rucisoft Academy</p>
                <h1 class="mt-3 text-3xl leading-tight font-extrabold sm:text-5xl">Belajar menyelesaikan masalah nyata, langsung dengan aplikasinya.</h1>
                <p class="mt-5 max-w-xl text-slate-300">Program edukasi Rucisoft: webinar, workshop praktik, klinik implementasi, dan pelatihan. Setiap workshop berfokus pada satu masalah utama, dan peserta pulang membawa hasil kerja yang bisa dilanjutkan di organisasinya.</p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="#jadwal" class="btn-primary">Lihat jadwal <x-heroicon-o-arrow-down class="size-4" /></a>
                    <a href="#asesmen" class="btn border border-white/20 text-white hover:bg-white/10">Asesmen kebutuhan gratis</a>
                </div>
            </div>
            <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
                @foreach ($tiers as $type => $tier)
                    <div class="flex gap-4 rounded-2xl border border-white/10 bg-white/5 p-5">
                        <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-brand-500/20 text-brand-400">
                            <x-dynamic-component :component="$tier['icon']" class="size-6" />
                        </span>
                        <div>
                            <p class="font-bold">{{ $tier['title'] }}</p>
                            <p class="mt-0.5 text-xs font-semibold text-brand-400">{{ $tier['model'] }}</p>
                            <p class="mt-2 text-sm text-slate-300">{{ $tier['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Jadwal --}}
    <section id="jadwal" class="py-14">
        <div class="container-page">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="eyebrow">Jadwal & program</p>
                    <h2 class="mt-2 text-2xl font-extrabold text-navy-900 sm:text-3xl">Pilih program sesuai tahap Anda</h2>
                </div>
                @if ($solutions->isNotEmpty())
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('academy') }}#jadwal" @class(['rounded-full border px-4 py-2 text-sm font-semibold', 'border-navy-900 bg-navy-900 text-white' => ! $solution, 'border-slate-200 bg-white text-slate-600 hover:border-brand-500' => $solution])>Semua produk</a>
                        @foreach ($solutions as $option)
                            <a href="{{ route('academy', ['produk' => $option->slug]) }}#jadwal" @class(['rounded-full border px-4 py-2 text-sm font-semibold', 'border-navy-900 bg-navy-900 text-white' => $solution?->is($option), 'border-slate-200 bg-white text-slate-600 hover:border-brand-500' => ! $solution?->is($option)])>{{ $option->name }}</a>
                        @endforeach
                    </div>
                @endif
            </div>

            @foreach ($tiers as $type => $tier)
                <div class="mt-10">
                    <div class="flex items-baseline justify-between gap-4 border-b border-slate-200 pb-3">
                        <h3 class="text-lg font-bold text-navy-900">{{ $tier['title'] }}</h3>
                        <p class="text-xs text-slate-500">{{ $tier['for'] }}</p>
                    </div>
                    @if (($events[$type] ?? collect())->isNotEmpty())
                        <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($events[$type] as $item)
                                @include('partials.academy-card', ['item' => $item])
                            @endforeach
                        </div>
                    @else
                        <p class="mt-5 rounded-xl bg-slate-50 px-5 py-4 text-sm text-slate-500">Jadwal {{ strtolower($tier['title']) }}{{ $solution ? ' untuk '.$solution->name : '' }} segera dibuka. Daftarkan minat Anda di bawah agar kami kabari lebih dulu.</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    {{-- Format workshop --}}
    <section class="bg-navy-50 py-14">
        <div class="container-page grid gap-10 lg:grid-cols-2">
            <div>
                <p class="eyebrow">Format workshop praktik</p>
                <h2 class="mt-2 text-2xl font-extrabold text-navy-900 sm:text-3xl">Separuh waktu untuk praktik, bukan sekadar materi</h2>
                <p class="mt-4 text-slate-600">Sejak awal peserta tahu workshop menggunakan aplikasi Rucisoft. Hasil praktik bisa diekspor dan dibawa pulang, sehingga tetap bernilai walaupun organisasi belum memutuskan membeli.</p>
                <a href="#asesmen" class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-brand-600 hover:underline">Belum yakin program mana yang cocok? Isi asesmen <x-heroicon-o-arrow-right class="size-4" /></a>
            </div>
            <div class="space-y-3">
                @foreach ([['Masalah & pendekatan', 20, 'Mengapa masalah sulit diselesaikan dan kerangka penyelesaiannya'], ['Praktik terpandu', 50, 'Menyusun hasil kerja langsung di aplikasi'], ['Pembahasan hasil', 20, 'Menguji kualitas pekerjaan peserta dan kesiapan penerapan'], ['Rencana implementasi', 10, 'Tahapan penerapan, dukungan, dan pilihan paket']] as [$part, $portion, $desc])
                    <div class="rounded-xl bg-white p-4 shadow-sm">
                        <div class="flex items-center justify-between text-sm">
                            <p class="font-bold text-navy-900">{{ $part }}</p>
                            <p class="font-extrabold text-brand-600">{{ $portion }}%</p>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-navy-50"><div class="h-2 rounded-full bg-brand-500" style="width: {{ $portion * 2 }}%"></div></div>
                        <p class="mt-2 text-xs text-slate-500">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Asesmen & minat --}}
    <section id="asesmen" class="py-14">
        <div class="container-page grid gap-8 lg:grid-cols-5">
            <div class="lg:col-span-3">
                <div class="rounded-2xl border border-slate-200 p-6 shadow-sm sm:p-8">
                    <p class="eyebrow">Asesmen kebutuhan</p>
                    <h2 class="mt-2 text-2xl font-extrabold text-navy-900">Ketahui program dan produk yang paling sesuai</h2>
                    <p class="mt-2 mb-6 text-sm text-slate-600">5 menit. Jawaban Anda membantu kami menyarankan webinar, workshop, atau langsung demo.</p>
                    @include('partials.lead-form', ['kind' => 'asesmen', 'formId' => 'academy_asesmen', 'assessment' => true, 'chooseSolution' => true, 'button' => 'Kirim asesmen', 'messageLabel' => 'Catatan tambahan'])
                </div>
            </div>
            <div class="space-y-5 lg:col-span-2">
                <div class="rounded-2xl bg-navy-900 p-6 text-white">
                    <p class="text-lg font-bold">Kabari saya jadwal berikutnya</p>
                    <p class="mt-1 mb-5 text-sm text-slate-300">Daftarkan minat untuk webinar atau workshop produk tertentu.</p>
                    @include('partials.lead-form', ['kind' => 'minat', 'formId' => 'academy_minat', 'chooseSolution' => true, 'dark' => true, 'button' => 'Daftarkan minat', 'messageLabel' => 'Topik yang ingin dipelajari'])
                </div>
                <div class="rounded-2xl border border-brand-100 bg-brand-50 p-6">
                    <p class="font-bold text-navy-900">Kebutuhan sudah jelas?</p>
                    <p class="mt-1 text-sm text-slate-600">Tidak perlu mengikuti semua tahap. Langsung jadwalkan klinik kebutuhan, demo, dan proposal.</p>
                    <a href="{{ route('home') }}#demo" class="btn-primary mt-4 !py-2.5">Jadwalkan demo</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('title', $item->title)
@section('description', $item->summary)

@section('content')
    @php
        $isAcademy = $item->isAcademy();
        $formKind = $isAcademy ? ($item->registration_open ? $item->type : 'minat') : 'demo';
        $formTitle = [
            'webinar' => 'Daftar webinar',
            'workshop' => 'Daftar workshop',
            'inhouse' => 'Ajukan untuk organisasi Anda',
            'minat' => 'Daftar minat jadwal berikutnya',
            'demo' => 'Minta informasi & demo',
        ][$formKind];
        $backUrl = $isAcademy ? route('academy') : route('catalog.index', ['type' => $item->type]);
    @endphp

    <section class="border-b border-slate-100 bg-gradient-to-b from-navy-50 to-white">
        <div class="container-page py-10 lg:py-12">
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-1 text-sm font-medium text-slate-500 hover:text-brand-600">
                <x-heroicon-o-arrow-left class="size-4" /> {{ $isAcademy ? 'Rucisoft Academy' : 'Katalog '.$item->typeLabel() }}
            </a>
            <div class="mt-5 flex flex-wrap items-center gap-3">
                <span class="rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700">{{ $item->typeLabel() }}</span>
                @if ($item->solution)
                    <a href="{{ route('solutions.show', $item->solution) }}" class="flex h-7 items-center">@include('partials.product-logo', ['solution' => $item->solution, 'class' => 'h-7'])</a>
                @endif
            </div>
            <h1 class="mt-4 max-w-4xl text-3xl font-extrabold tracking-tight text-navy-900 sm:text-4xl">{{ $item->title }}</h1>
            @if ($item->subtitle)
                <p class="mt-2 text-base font-semibold text-brand-600">{{ $item->subtitle }}</p>
            @endif
            @if ($item->summary)
                <p class="mt-4 max-w-3xl text-lg text-slate-600">{{ $item->summary }}</p>
            @endif

            @if ($isAcademy)
                <dl class="mt-7 grid max-w-4xl gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach (['Jadwal' => $item->event_date ?: 'Segera diumumkan', 'Format & lokasi' => $item->location, 'Durasi' => $item->duration, 'Biaya' => $item->price_label] as $term => $value)
                        @if ($value)
                            <div class="rounded-xl border border-slate-200 bg-white px-4 py-3">
                                <dt class="text-xs text-slate-400">{{ $term }}</dt>
                                <dd class="mt-0.5 text-sm font-bold text-navy-900">{{ $value }}</dd>
                            </div>
                        @endif
                    @endforeach
                </dl>
            @endif
        </div>
    </section>

    <section class="py-12">
        <div class="container-page grid gap-10 lg:grid-cols-5">
            <div class="space-y-10 lg:col-span-3">
                @if ($item->audience)
                    <div class="rounded-xl bg-navy-50 px-5 py-4 text-sm"><span class="font-bold text-navy-900">Cocok untuk:</span> <span class="text-slate-600">{{ $item->audience }}</span></div>
                @endif

                @if (! empty($item->outcomes))
                    <div>
                        <h2 class="text-xl font-extrabold text-navy-900">Hasil yang Anda bawa pulang</h2>
                        <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                            @foreach ($item->outcomes as $outcome)
                                <li class="flex items-start gap-3 rounded-xl border border-slate-200 p-4 text-sm font-medium text-navy-900">
                                    <x-heroicon-o-check-badge class="size-5 shrink-0 text-brand-500" /> {{ $outcome }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (! empty($item->agenda))
                    <div>
                        <h2 class="text-xl font-extrabold text-navy-900">Susunan acara</h2>
                        <div class="mt-4 overflow-hidden rounded-xl border border-slate-200">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                                    <tr><th class="px-4 py-3">Bagian</th><th class="px-4 py-3">Porsi</th><th class="px-4 py-3">Isi</th></tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($item->agenda as $row)
                                        <tr>
                                            <td class="px-4 py-3 font-semibold text-navy-900">{{ $row['part'] ?? '' }}</td>
                                            <td class="px-4 py-3 font-bold text-brand-600">{{ $row['portion'] ?? '' }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ $row['content'] ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($item->body)
                    <div class="prose-content">{!! $item->body !!}</div>
                @endif

                @if (! $isAcademy && $item->coverUrl())
                    <img src="{{ $item->coverUrl() }}" alt="{{ $item->title }}" class="w-full rounded-2xl border border-slate-200"
                         @if ($item->coverFallbackUrl()) onerror="this.onerror=null;this.src='{{ $item->coverFallbackUrl() }}'" @endif>
                @endif
            </div>

            <aside id="daftar" class="scroll-mt-24 lg:col-span-2">
                <div class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-6 shadow-xl shadow-navy-900/5">
                    <p class="text-lg font-extrabold text-navy-900">{{ $formTitle }}</p>
                    @if ($item->type === 'workshop')
                        <p class="mt-1 text-xs text-slate-500">Lima peserta dari satu organisasi? Cukup satu pendaftaran, sebutkan jumlah peserta di catatan.</p>
                    @endif
                    <div class="mt-5">
                        @include('partials.lead-form', [
                            'kind' => $formKind,
                            'formId' => 'item_'.$item->id,
                            'item' => $item,
                            'button' => $formTitle,
                            'messageLabel' => $item->type === 'workshop' ? 'Jumlah peserta & catatan' : 'Catatan',
                        ])
                    </div>
                    <a href="{{ $item->whatsappUrl() }}" target="_blank" rel="noopener" class="mt-4 flex items-center justify-center gap-2 text-sm font-semibold text-[#1a9e4b] hover:underline">
                        @include('partials.wa-icon', ['class' => 'size-4']) Tanya dulu via WhatsApp
                    </a>
                </div>
            </aside>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="border-t border-slate-100 bg-navy-50 py-12">
            <div class="container-page">
                <h2 class="text-xl font-extrabold text-navy-900">{{ $item->typeLabel() }} lainnya</h2>
                <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $other)
                        @include($other->isAcademy() ? 'partials.academy-card' : 'partials.catalog-card', ['item' => $other])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

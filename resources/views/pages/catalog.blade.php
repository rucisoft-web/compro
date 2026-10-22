@extends('layouts.app')

@section('title', $type ? 'Katalog '.\App\Models\CatalogItem::TYPES[$type] : 'Katalog')
@section('description', 'Katalog software, buku, dan workshop Rucisoft Indonesia.')

@section('content')
    <section class="bg-gradient-to-b from-navy-50 to-white">
        <div class="container-page py-16">
            <p class="eyebrow">Katalog</p>
            <h1 class="mt-3 text-4xl font-extrabold text-navy-900">Software, buku, dan workshop</h1>
            <p class="mt-4 max-w-2xl text-slate-600">Pilih penawaran yang sesuai, lalu hubungi kami via WhatsApp untuk informasi lengkap.</p>

            <div class="mt-8 flex flex-wrap gap-2">
                <a href="{{ route('catalog.index') }}" @class(['rounded-full border px-4 py-2 text-sm font-semibold', 'bg-navy-900 text-white border-navy-900' => ! $type, 'bg-white text-slate-600 border-slate-200 hover:border-brand-500' => $type])>Semua</a>
                @foreach (\App\Models\CatalogItem::TYPES as $key => $label)
                    <a href="{{ route('catalog.index', ['type' => $key]) }}" @class(['rounded-full border px-4 py-2 text-sm font-semibold', 'bg-navy-900 text-white border-navy-900' => $type === $key, 'bg-white text-slate-600 border-slate-200 hover:border-brand-500' => $type !== $key])>{{ $label }}</a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="pb-20">
        <div class="container-page grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($items as $item)
                @include('partials.catalog-card', ['item' => $item])
            @empty
                <p class="text-slate-500">Belum ada item pada kategori ini.</p>
            @endforelse
        </div>
    </section>

    @include('partials.cta')
@endsection

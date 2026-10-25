@extends('layouts.app')

@section('title', $solution ? 'Artikel '.$solution->name : 'Artikel')
@section('description', 'Artikel Rucisoft: masalah yang sering dihadapi organisasi, penyebabnya, solusi yang perlu dilakukan, dan bagaimana aplikasi Rucisoft membantu.')

@section('content')
    <section class="relative overflow-hidden bg-navy-950 text-white">
        <div class="pointer-events-none absolute -top-32 right-0 size-[30rem] rounded-full bg-brand-500/20 blur-3xl"></div>
        <div class="container-page relative py-14 lg:py-16">
            <p class="eyebrow !text-brand-400">Artikel</p>
            <h1 class="mt-3 max-w-3xl text-3xl leading-tight font-extrabold sm:text-5xl">Memahami masalahnya dulu, baru memilih alatnya.</h1>
            <p class="mt-5 max-w-2xl text-slate-300">Setiap artikel membahas masalah yang sering dihadapi organisasi, penyebabnya, solusi yang perlu dilakukan, dan bagian mana yang bisa dibantu aplikasi Rucisoft.</p>
        </div>
    </section>

    <section class="py-14">
        <div class="container-page">
            @if ($solutions->count() > 1)
                <div class="mb-8 flex flex-wrap gap-2">
                    <a href="{{ route('articles.index') }}" @class(['rounded-full border px-4 py-2 text-sm font-semibold', 'border-navy-900 bg-navy-900 text-white' => ! $solution, 'border-slate-200 bg-white text-slate-600 hover:border-brand-500' => $solution])>Semua</a>
                    @foreach ($solutions as $option)
                        <a href="{{ route('articles.index', ['produk' => $option->slug]) }}" @class(['rounded-full border px-4 py-2 text-sm font-semibold', 'border-navy-900 bg-navy-900 text-white' => $solution?->is($option), 'border-slate-200 bg-white text-slate-600 hover:border-brand-500' => ! $solution?->is($option)])>{{ $option->name }}</a>
                    @endforeach
                </div>
            @endif

            @if ($articles->isEmpty())
                <p class="rounded-2xl border border-dashed border-slate-300 p-10 text-center text-slate-500">Belum ada artikel.</p>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($articles as $article)
                        @include('partials.article-card', ['article' => $article])
                    @endforeach
                </div>
                <div class="mt-10">{{ $articles->links() }}</div>
            @endif
        </div>
    </section>
@endsection

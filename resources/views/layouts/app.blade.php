@php
    $siteName = \App\Models\Setting::get('site_name');
    $pageTitle = trim($__env->yieldContent('title'));
    $pageDescription = trim($__env->yieldContent('description')) ?: \App\Models\Setting::get('meta_description');
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $pageTitle ? $pageTitle.' | '.$siteName : $siteName.' | '.\App\Models\Setting::get('hero_title') }}</title>
        <meta name="description" content="{{ $pageDescription }}">
        <meta property="og:title" content="{{ $pageTitle ?: $siteName }}">
        <meta property="og:description" content="{{ $pageDescription }}">
        <meta property="og:image" content="{{ asset('images/logo.svg') }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <link rel="canonical" href="{{ url()->current() }}">
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white font-sans text-slate-800 antialiased">
        @include('partials.header')

        <main x-data>
            @yield('content')
        </main>

        @include('partials.footer')

        <a href="{{ \App\Models\Setting::whatsappUrl() }}" target="_blank" rel="noopener"
           class="fixed right-5 bottom-5 z-50 flex size-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-xl shadow-green-700/30 transition hover:scale-105"
           aria-label="Chat WhatsApp">
            @include('partials.wa-icon', ['class' => 'size-7'])
        </a>
    </body>
</html>

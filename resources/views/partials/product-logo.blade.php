{{-- Product logo with a bundled fallback when the external logo cannot load. --}}
@php($fallback = $solution->fallbackLogoUrl())
@if ($solution->logoUrl())
    <img src="{{ $solution->logoUrl() }}" alt="Logo {{ $solution->name }}" loading="lazy"
         class="{{ $class ?? 'size-12' }} object-contain"
         @if ($fallback) onerror="this.onerror=null;this.src='{{ $fallback }}'" @endif>
@else
    <div class="{{ $class ?? 'size-12' }} flex items-center justify-center rounded-xl bg-gradient-to-br from-navy-900 to-brand-600 text-white">
        <x-dynamic-component :component="$solution->icon ?: 'heroicon-o-squares-2x2'" class="size-1/2" />
    </div>
@endif

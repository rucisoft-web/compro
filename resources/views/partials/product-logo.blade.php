{{-- Product logo kept at a fixed height so wide wordmarks and square icons read equally well. --}}
@php($fallback = $solution->fallbackLogoUrl())
@if ($solution->logoUrl())
    <img src="{{ $solution->logoUrl() }}" alt="Logo {{ $solution->name }}" loading="lazy"
         class="{{ $class ?? 'h-10' }} w-auto max-w-full object-contain object-left"
         @if ($fallback) onerror="this.onerror=null;this.src='{{ $fallback }}'" @endif>
@else
    <span class="{{ $class ?? 'h-10' }} inline-flex aspect-square items-center justify-center rounded-xl bg-gradient-to-br from-navy-900 to-brand-600 text-white">
        <x-dynamic-component :component="$solution->icon ?: 'heroicon-o-squares-2x2'" class="size-1/2" />
    </span>
@endif

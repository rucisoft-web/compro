@extends('layouts.app')

@section('title', 'Terima kasih')

@section('content')
    @php
        $next = [
            'asesmen' => 'Tim kami akan mempelajari jawaban asesmen Anda dan menghubungi dalam 1-3 hari kerja untuk membahas langkah yang paling sesuai.',
            'minat' => 'Kami akan mengabari Anda saat jadwal webinar atau workshop berikutnya dibuka.',
            'webinar' => 'Detail akses webinar akan dikirim melalui email dan WhatsApp menjelang acara.',
            'workshop' => 'Tim kami akan mengonfirmasi kursi, biaya, dan persiapan workshop melalui WhatsApp.',
            'inhouse' => 'Kami akan menghubungi Anda untuk membahas lingkup, peserta, dan jadwal workshop in-house.',
            'demo' => 'Kami akan menjadwalkan klinik kebutuhan 30-45 menit. Sebaiknya libatkan sponsor dan PIC implementasi di organisasi Anda.',
            'pilot' => 'Kami akan menyiapkan rancangan pilot: unit, pengguna, data, pendampingan, dan ukuran keberhasilan.',
            'proposal' => 'Kami akan menyiapkan proposal yang memisahkan lisensi, implementasi, migrasi data, pelatihan, dan pendampingan.',
        ][$lead?->kind] ?? 'Tim kami akan segera menghubungi Anda.';
        $waMessage = $lead
            ? "Halo Rucisoft, saya {$lead->name} dari {$lead->organization_name}. Saya baru mengisi formulir {$lead->kindLabel()}".($lead->solution ? " untuk {$lead->solution->name}" : '').'.'
            : null;
    @endphp

    <section class="bg-gradient-to-b from-navy-50 to-white">
        <div class="container-page max-w-2xl py-16 text-center">
            <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-brand-50 text-brand-600">
                <x-heroicon-o-check-circle class="size-9" />
            </div>
            <h1 class="mt-6 text-3xl font-extrabold text-navy-900">Terima kasih{{ $lead ? ', '.$lead->name : '' }}!</h1>
            <p class="mt-3 text-slate-600">
                {{ $lead ? $lead->kindLabel().' Anda sudah kami terima.' : 'Data Anda sudah kami terima.' }}
                {{ $next }}
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ \App\Models\Setting::whatsappUrl($waMessage) }}" target="_blank" rel="noopener" class="btn-wa">
                    @include('partials.wa-icon', ['class' => 'size-5']) Percepat via WhatsApp
                </a>
                <a href="{{ $lead?->solution ? route('solutions.show', $lead->solution) : route('home') }}" class="btn-outline">Kembali</a>
            </div>
        </div>
    </section>
@endsection

<x-filament-widgets::widget>
    <x-filament::section heading="Funnel penjualan (jumlah organisasi)" description="Organisasi yang pernah mencapai setiap tahap. Angka di kanan: yang saat ini berada di tahap tersebut.">
        <x-slot name="afterHeader">
            <x-filament::input.wrapper>
                <x-filament::input.select wire:model.live="solution">
                    <option value="">Semua produk</option>
                    @foreach ($solutions as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </x-filament::input.select>
            </x-filament::input.wrapper>
        </x-slot>

        <div style="display: grid; gap: .6rem;">
            @foreach ($stages as $stage)
                <div style="display: grid; grid-template-columns: 11rem 1fr 4.5rem; align-items: center; gap: .75rem; font-size: .875rem;">
                    <span style="font-weight: 600;">{{ $loop->iteration }}. {{ $stage['label'] }}</span>
                    <div style="height: 1.4rem; border-radius: .4rem; background: rgba(148,163,184,.15); overflow: hidden;">
                        <div style="height: 100%; width: {{ round($stage['count'] / $max * 100) }}%; min-width: {{ $stage['count'] ? '1.6rem' : '0' }}; background: #0d9488; border-radius: .4rem; color: #fff; font-size: .75rem; font-weight: 700; display: flex; align-items: center; padding-left: .5rem;">{{ $stage['count'] ?: '' }}</div>
                    </div>
                    <span style="text-align: right; color: #64748b;">{{ $stage['current'] }} aktif</span>
                </div>
            @endforeach
            <p style="margin-top: .5rem; font-size: .8rem; color: #64748b;">Tidak melanjutkan: {{ $lost }} peluang.</p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

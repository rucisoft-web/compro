{{--
    Lead capture form shared by every funnel step.
    Params: $kind, $formId, optional $solution, $item, $button, $assessment (bool), $chooseSolution (bool), $messageLabel, $dark (bool)
--}}
@php
    $formId = $formId ?? $kind;
    $bag = $errors->getBag($formId);
    $mine = old('form_id') === $formId;
    $val = fn (string $field, $default = null) => $mine ? old($field, $default) : $default;
    $dark = $dark ?? false;
    $label = $dark ? 'text-slate-200' : 'text-slate-700';
    $input = 'mt-1 block w-full rounded-lg border px-3 py-2.5 text-sm text-navy-900 shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none '.($dark ? 'border-white/10 bg-white' : 'border-slate-300 bg-white');
    $solutions = ($chooseSolution ?? false) ? \App\Models\Solution::active()->get(['id', 'name']) : collect();
@endphp

<form method="POST" action="{{ route('leads.store') }}" class="space-y-4" id="form-{{ $formId }}">
    @csrf
    <input type="hidden" name="kind" value="{{ $kind }}">
    <input type="hidden" name="form_id" value="{{ $formId }}">
    @isset($item)<input type="hidden" name="catalog_item_id" value="{{ $item->id }}">@endisset
    @if (isset($solution) && $solution)<input type="hidden" name="solution_id" value="{{ $solution->id }}">@endif
    <div class="hidden" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>

    @if ($bag->any())
        <div class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700" x-data x-init="$el.scrollIntoView({ block: 'center' })">
            <p class="font-semibold">Mohon periksa kembali isian Anda:</p>
            <ul class="mt-1 list-disc pl-5">
                @foreach ($bag->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2">
        <label class="block text-sm font-medium {{ $label }}">Nama lengkap *
            <input type="text" name="name" value="{{ $val('name') }}" required maxlength="120" class="{{ $input }}">
        </label>
        <label class="block text-sm font-medium {{ $label }}">Jabatan / peran
            <input type="text" name="position" value="{{ $val('position') }}" maxlength="120" placeholder="mis. Kepala Divisi Strategi" class="{{ $input }}">
        </label>
        <label class="block text-sm font-medium {{ $label }} sm:col-span-2">Nama organisasi *
            <input type="text" name="organization_name" value="{{ $val('organization_name') }}" required maxlength="160" class="{{ $input }}">
        </label>
        <label class="block text-sm font-medium {{ $label }}">Email kantor *
            <input type="email" name="email" value="{{ $val('email') }}" required maxlength="160" class="{{ $input }}">
        </label>
        <label class="block text-sm font-medium {{ $label }}">Nomor WhatsApp *
            <input type="tel" name="phone" value="{{ $val('phone') }}" required maxlength="30" placeholder="08xx" class="{{ $input }}">
        </label>
        @if ($solutions->isNotEmpty())
            <label class="block text-sm font-medium {{ $label }} sm:col-span-2">Produk yang diminati
                <select name="solution_id" class="{{ $input }}">
                    <option value="">Belum tahu / ingin konsultasi</option>
                    @foreach ($solutions as $option)
                        <option value="{{ $option->id }}" @selected((string) $val('solution_id') === (string) $option->id)>{{ $option->name }}</option>
                    @endforeach
                </select>
            </label>
        @endif
    </div>

    @if ($assessment ?? false)
        <div class="space-y-3 rounded-xl {{ $dark ? 'bg-white/5' : 'bg-navy-50' }} p-4">
            <p class="text-sm font-bold {{ $dark ? 'text-white' : 'text-navy-900' }}">Asesmen singkat (boleh dijawab sebagian)</p>
            @foreach (\App\Models\Lead::ASSESSMENT_QUESTIONS as $key => $question)
                <label class="block text-sm {{ $label }}">{{ $loop->iteration }}. {{ $question }}
                    <input type="text" name="answers[{{ $key }}]" value="{{ $val('answers.'.$key) }}" maxlength="1000" class="{{ $input }}">
                </label>
            @endforeach
        </div>
    @endif

    <label class="block text-sm font-medium {{ $label }}">{{ $messageLabel ?? 'Ceritakan kebutuhan Anda' }}
        <textarea name="message" rows="3" maxlength="2000" class="{{ $input }}">{{ $val('message') }}</textarea>
    </label>

    <label class="flex items-start gap-2 text-xs {{ $dark ? 'text-slate-300' : 'text-slate-500' }}">
        <input type="checkbox" name="consent" value="1" required @checked($val('consent')) class="mt-0.5 rounded border-slate-300 text-brand-600">
        <span>Saya bersedia dihubungi tim Rucisoft dan menerima informasi lanjutan yang relevan. Data hanya digunakan untuk keperluan ini.</span>
    </label>

    <button type="submit" class="btn-primary w-full sm:w-auto">{{ $button ?? 'Kirim' }} <x-heroicon-o-arrow-right class="size-4" /></button>
</form>

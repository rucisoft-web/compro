<?php

namespace App\Http\Controllers;

use App\Models\CatalogItem;
use App\Models\Lead;
use App\Models\Setting;
use App\Services\LeadIntake;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class LeadController extends Controller
{
    public function store(Request $request, LeadIntake $intake): RedirectResponse
    {
        // Honeypot: real visitors never fill this hidden field.
        if ($request->filled('website')) {
            return redirect()->route('leads.thanks');
        }

        // Each form on a page has its own id so validation errors land on the right one.
        $bag = preg_replace('/[^a-z0-9_]/', '', strtolower((string) $request->input('form_id', 'lead'))) ?: 'lead';

        $data = $request->validateWithBag($bag, [
            'kind' => ['required', Rule::in(array_keys(Lead::KINDS))],
            'name' => ['required', 'string', 'max:120'],
            'position' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['required', 'string', 'max:30'],
            'organization_name' => ['required', 'string', 'max:160'],
            'solution_id' => ['nullable', 'exists:solutions,id'],
            'catalog_item_id' => ['nullable', 'exists:catalog_items,id'],
            'answers' => ['nullable', 'array'],
            'answers.*' => ['nullable', 'string', 'max:1000'],
            'message' => ['nullable', 'string', 'max:2000'],
            'consent' => ['accepted'],
        ], [
            'consent.accepted' => 'Mohon setujui agar tim Rucisoft dapat menghubungi Anda.',
        ], [
            'name' => 'nama', 'email' => 'email', 'phone' => 'nomor WhatsApp',
            'organization_name' => 'nama organisasi',
        ]);

        unset($data['form_id']);
        $data['answers'] = array_filter(
            array_intersect_key($data['answers'] ?? [], Lead::ASSESSMENT_QUESTIONS),
            fn ($answer) => filled($answer),
        ) ?: null;
        $data['consent'] = true;
        $data['source_url'] = substr((string) url()->previous(), 0, 500);

        if (! empty($data['catalog_item_id']) && empty($data['solution_id'])) {
            $data['solution_id'] = CatalogItem::find($data['catalog_item_id'])?->solution_id;
        }

        $lead = $intake->record($data);

        $this->notifyTeam($lead);

        return redirect()->route('leads.thanks')->with('lead_id', $lead->id);
    }

    public function thanks(Request $request): View
    {
        $lead = Lead::with(['solution', 'catalogItem'])->find($request->session()->get('lead_id'));

        return view('pages.thanks', ['lead' => $lead]);
    }

    private function notifyTeam(Lead $lead): void
    {
        if (in_array(config('mail.default'), ['log', 'array'], true)) {
            return;
        }

        try {
            $lines = [
                'Jenis: '.$lead->kindLabel(),
                'Produk: '.($lead->solution?->name ?? '-'),
                'Acara: '.($lead->catalogItem?->title ?? '-'),
                'Nama: '.$lead->name.' ('.($lead->position ?: '-').')',
                'Organisasi: '.$lead->organization_name,
                'Email: '.$lead->email,
                'WhatsApp: '.$lead->phone,
                'Pesan: '.($lead->message ?: '-'),
            ];

            Mail::raw(implode("\n", $lines), function ($message) use ($lead) {
                $message->to(Setting::get('email'))
                    ->subject('[Prospek baru] '.$lead->kindLabel().' - '.$lead->organization_name);
            });
        } catch (Throwable $e) {
            Log::warning('Notifikasi prospek gagal dikirim: '.$e->getMessage());
        }
    }
}

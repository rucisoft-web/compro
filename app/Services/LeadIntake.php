<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

/**
 * Turns a website form submission into CRM records: one organization, one
 * opportunity per organization and product, and the lead itself.
 */
class LeadIntake
{
    public function record(array $data): Lead
    {
        return DB::transaction(function () use ($data) {
            $kind = Lead::KINDS[$data['kind']];

            $organization = Organization::firstOrCreate(['name' => trim($data['organization_name'])]);

            $opportunity = Opportunity::firstOrCreate(
                ['organization_id' => $organization->id, 'solution_id' => $data['solution_id'] ?? null],
                ['stage' => $kind['stage'], 'source' => $data['kind']],
            );

            if (! $opportunity->wasRecentlyCreated) {
                $opportunity->advanceTo($kind['stage']);
            }

            // Always surface the newest request as the next thing to do.
            $opportunity->next_action = $kind['action'];
            $opportunity->next_action_at = now()->addDays($kind['days'])->toDateString();
            $opportunity->save();

            return Lead::create($data + [
                'organization_id' => $organization->id,
                'opportunity_id' => $opportunity->id,
            ]);
        });
    }
}

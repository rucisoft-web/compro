<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Opportunity extends Model
{
    /**
     * Funnel stages in order. Incoming leads only ever move an opportunity forward.
     */
    public const STAGES = [
        'edukasi' => 'Edukasi masalah',
        'asesmen' => 'Asesmen kebutuhan',
        'workshop' => 'Workshop praktik',
        'klinik' => 'Klinik & demo',
        'pilot' => 'Pilot',
        'proposal' => 'Proposal & pengadaan',
        'menang' => 'Pembelian',
        'pendampingan' => 'Pendampingan',
        'perpanjangan' => 'Perpanjangan',
        'batal' => 'Tidak melanjutkan',
    ];

    public const STAGE_COLORS = [
        'edukasi' => 'gray',
        'asesmen' => 'gray',
        'workshop' => 'info',
        'klinik' => 'info',
        'pilot' => 'warning',
        'proposal' => 'warning',
        'menang' => 'success',
        'pendampingan' => 'success',
        'perpanjangan' => 'success',
        'batal' => 'danger',
    ];

    protected $fillable = [
        'organization_id', 'solution_id', 'stage', 'source', 'next_action',
        'next_action_at', 'estimated_value', 'notes', 'won_at',
    ];

    protected function casts(): array
    {
        return [
            'next_action_at' => 'date',
            'won_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Opportunity $opportunity) {
            if ($opportunity->stage === 'menang' && ! $opportunity->won_at) {
                $opportunity->won_at = now();
            }
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public static function stageIndex(string $stage): int
    {
        $index = array_search($stage, array_keys(self::STAGES), true);

        return $index === false ? -1 : $index;
    }

    /**
     * Stages from the given one onward, excluding lost deals.
     */
    public static function stagesFrom(string $stage): array
    {
        $open = array_values(array_diff(array_keys(self::STAGES), ['batal']));

        return array_slice($open, max(self::stageIndex($stage), 0));
    }

    /**
     * Organizations that reached at least this stage; the funnel's unit is the organization.
     */
    public static function reachedCount(string $stage, ?int $solutionId = null): int
    {
        return static::query()
            ->whereIn('stage', self::stagesFrom($stage))
            ->when($solutionId, fn ($q) => $q->where('solution_id', $solutionId))
            ->distinct()
            ->count('organization_id');
    }

    /**
     * Move forward to the given stage; never backwards, and never out of a closed deal.
     */
    public function advanceTo(string $stage): bool
    {
        if (in_array($this->stage, ['menang', 'pendampingan', 'perpanjangan', 'batal'], true)) {
            return false;
        }

        if (self::stageIndex($stage) <= self::stageIndex($this->stage)) {
            return false;
        }

        $this->stage = $stage;

        return true;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Support\Traits\HasOrganizationScope;

class InfluencerChannel extends Model
{
    use HasFactory, HasOrganizationScope;

    protected $fillable = [
        'organization_id',
        'influencer_name',
        'platform',
        'handle',
        'url',
        'follower_count',
        'engagement_rate',
        'status',
        'metrics',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'follower_count' => 'integer',
            'engagement_rate' => 'decimal:2',
            'metrics' => 'array',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}

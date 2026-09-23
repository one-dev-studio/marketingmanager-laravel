<?php

namespace App\Services\AI;

use App\Models\AiGeneration;
use App\Models\AiUsageLog;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AiUsageTrackingService
{
    public function logUsage(
        Organization $organization,
        User $user,
        AiGeneration $aiGeneration,
        string $provider,
        string $model,
        string $type,
        int $tokensUsed,
        float $cost
    ): AiUsageLog {
        $usageLog = AiUsageLog::create([
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'ai_generation_id' => $aiGeneration->id,
            'provider' => $provider,
            'model' => $model,
            'type' => $type,
            'tokens_used' => $tokensUsed,
            'cost' => $cost,
            'usage_date' => now()->toDateString(),
        ]);

        $this->incrementUsageTracking(
            $organization->id,
            'tokens',
            (float) $tokensUsed,
            ['provider' => $provider],
        );

        $this->incrementUsageTracking(
            $organization->id,
            'cost',
            $cost,
            ['provider' => $provider],
        );

        return $usageLog;
    }

    public function getUsageStats(Organization $organization, string $period = 'month'): array
    {
        $startDate = match($period) {
            'day' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $stats = DB::table('ai_usage_logs')
            ->where('organization_id', $organization->id)
            ->where('usage_date', '>=', $startDate->toDateString())
            ->selectRaw('
                SUM(tokens_used) as total_tokens,
                SUM(cost) as total_cost,
                COUNT(*) as total_requests,
                provider,
                type
            ')
            ->groupBy('provider', 'type')
            ->get();

        return [
            'period' => $period,
            'start_date' => $startDate->toDateString(),
            'stats' => $stats,
        ];
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function incrementUsageTracking(
        int $organizationId,
        string $metric,
        float $amount,
        array $metadata = [],
    ): void {
        if (DB::connection()->getDriverName() === 'sqlite') {
            $this->incrementUsageTrackingOnSqlite($organizationId, $metric, $amount, $metadata);

            return;
        }

        $this->incrementUsageTrackingAtomically($organizationId, $metric, $amount, $metadata);
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function incrementUsageTrackingOnSqlite(
        int $organizationId,
        string $metric,
        float $amount,
        array $metadata = [],
    ): void {
        $date = now()->toDateString();

        $existing = DB::table('usage_tracking')
            ->where('organization_id', $organizationId)
            ->where('feature', 'ai_generation')
            ->where('metric', $metric)
            ->where('date', $date)
            ->first();

        $nextValue = (float) ($existing?->value ?? 0) + $amount;

        DB::table('usage_tracking')->updateOrInsert(
            [
                'organization_id' => $organizationId,
                'feature' => 'ai_generation',
                'metric' => $metric,
                'date' => $date,
            ],
            [
                'value' => $nextValue,
                'metadata' => json_encode($metadata),
                'updated_at' => now(),
                'created_at' => $existing?->created_at ?? now(),
            ]
        );
    }

    /**
     * Atomic upsert increment for MySQL (and compatible drivers).
     *
     * @param  array<string, mixed>  $metadata
     */
    private function incrementUsageTrackingAtomically(
        int $organizationId,
        string $metric,
        float $amount,
        array $metadata = [],
    ): void {
        $date = now()->toDateString();
        $now = now();
        $metadataJson = json_encode($metadata);

        DB::statement(
            'INSERT INTO usage_tracking (organization_id, feature, metric, date, value, metadata, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
                value = value + ?,
                metadata = ?,
                updated_at = ?',
            [
                $organizationId,
                'ai_generation',
                $metric,
                $date,
                $amount,
                $metadataJson,
                $now,
                $now,
                $amount,
                $metadataJson,
                $now,
            ]
        );
    }
}



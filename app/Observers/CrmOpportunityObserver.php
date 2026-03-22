<?php

namespace App\Observers;

use App\Models\CrmOpportunity;
use App\Services\Crm\OpportunityLogService;
use Carbon\Carbon;

class CrmOpportunityObserver
{
    public function created(CrmOpportunity $opportunity): void
    {
        OpportunityLogService::log(
            $opportunity,
            'created',
            [],
            $opportunity->toArray(),
            "Oportunidade criada por " . (auth()->user()->name ?? 'Sistema')
        );
    }

    public function updating(CrmOpportunity $opportunity): void
    {
        $dirty = $opportunity->getDirty();
        $original = $opportunity->getOriginal();

        foreach ($dirty as $field => $newValue) {
            $oldValue = $original[$field] ?? null;

            // Skip some fields if necessary (like updated_at)
            if (in_array($field, ['updated_at', 'created_at'])) continue;

            $action = $this->determineAction($field);
            $description = $this->formatDescription($field, $oldValue, $newValue);
            $durationSeconds = null;

            if ($field === 'stage_id') {
                $lastChange = $opportunity->updated_at ?? $opportunity->created_at;
                $durationSeconds = now()->diffInSeconds($lastChange);
                $durationStr = $this->formatDuration($durationSeconds);
                $description .= " (Tempo na etapa anterior: {$durationStr})";
            }

            OpportunityLogService::log(
                $opportunity,
                $action,
                [$field => $oldValue],
                [$field => $newValue],
                $description,
                $durationSeconds
            );
        }
    }

    protected function determineAction(string $field): string
    {
        return match ($field) {
            'stage_id' => 'stage_change',
            'status' => 'status_change',
            'seller_id' => 'seller_assigned',
            'owner_id' => 'owner_assigned',
            'estimated_value' => 'value_updated',
            default => 'field_updated',
        };
    }

    protected function formatDescription(string $field, $oldValue, $newValue): string
    {
        $userName = auth()->user()->name ?? 'Sistema';
        
        return match ($field) {
            'stage_id' => "{$userName} alterou a etapa de '{$oldValue}' para '{$newValue}'",
            'status' => "{$userName} alterou o status de '{$oldValue}' para '{$newValue}'",
            'seller_id' => "{$userName} atribuiu o vendedor para id:{$newValue}",
            'owner_id' => "{$userName} alterou o responsável para id:{$newValue}",
            'estimated_value' => "{$userName} alterou o valor estimado de R$ " . number_format((float)$oldValue, 2, ',', '.') . " para R$ " . number_format((float)$newValue, 2, ',', '.'),
            default => "{$userName} alterou o campo '{$field}'",
        };
    }

    protected function formatDuration(int $seconds): string
    {
        if ($seconds < 60) return "{$seconds} segundos";
        if ($seconds < 3600) return round($seconds / 60) . " minutos";
        if ($seconds < 86400) return round($seconds / 3600, 1) . " horas";
        return round($seconds / 86400, 1) . " dias";
    }
}

<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class AuditLogger
{
    /**
     * Record an important activity. Never pass secrets (passwords, tokens)
     * into the description.
     *
     * @param  string  $activity  login|create|update|delete|status_change|payment|handover|return
     * @param  string  $module  users|vehicles|customers|transactions|payments|inspections|maintenances|settings|reports|auth
     */
    public static function log(
        string $activity,
        string $module,
        string $description,
        ?Model $record = null,
        ?Authenticatable $user = null,
    ): AuditLog {
        $user ??= auth()->user();

        return AuditLog::create([
            'user_id' => $user?->getAuthIdentifier(),
            'activity' => $activity,
            'module' => $module,
            'record_type' => $record?->getTable(),
            'record_id' => $record?->getKey(),
            'description' => $description,
            'ip_address' => request()?->ip(),
        ]);
    }
}

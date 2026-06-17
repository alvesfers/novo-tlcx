<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditLogService
{
    public function logAction(
        string $action,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): AuditLog {
        return AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function logLogin($user)
    {
        return $this->logAction('login', $user);
    }

    public function logLogout($user)
    {
        return $this->logAction('logout', $user);
    }

    public function logCreate(Model $model)
    {
        return $this->logAction('create', $model, null, $model->toArray());
    }

    public function logUpdate(Model $model, array $oldValues)
    {
        return $this->logAction('update', $model, $oldValues, $model->toArray());
    }

    public function logDelete(Model $model)
    {
        return $this->logAction('delete', $model, $model->toArray(), null);
    }
}

<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait AuditLogTrait
{
    /**
     * Log an action to audit log
     */
    public static function logAction($action, $model, $oldData = null, $newData = null)
    {
        $user = Auth::user();
        
        AuditLog::create([
            'user_id' => $user ? $user->id : null,
            'action' => $action, // create, update, delete
            'model_type' => get_class($model),
            'model_id' => $model->id ?? null,
            'old_data' => $oldData ? json_encode($oldData) : null,
            'new_data' => $newData ? json_encode($newData) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get changed fields between old and new data
     */
    public static function getChangedFields($oldData, $newData)
    {
        $changed = [];
        
        if (!$oldData) {
            return $newData;
        }
        
        foreach ($newData as $key => $value) {
            if (!isset($oldData[$key]) || $oldData[$key] != $value) {
                $changed[$key] = [
                    'old' => $oldData[$key] ?? null,
                    'new' => $value
                ];
            }
        }
        
        return $changed;
    }
}


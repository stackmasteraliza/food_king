<?php

namespace App\Services;

use App\Models\OperationLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActionTracker
{
    public static function log($action, $model = null, $description = null)
    {
        $before = null;
        $after = null;

        if ($model) {
            $before = method_exists($model, 'getOriginal')
                ? $model->getOriginal()
                : [];

            $changes = $action === 'update'
                ? $model->getChanges()
                : [];

            $after = $action === 'update'
                ? array_merge($before, $changes)
                : null;
        }

        return OperationLog::create([
            'user_id' => Auth::id() ?? null,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'before' => $before,
            'after' => $after,
            'description' => $description ?? self::getDefaultDescription($action, $model),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'seen' => false
        ]);
    }

    private static function getDefaultDescription($action, $model)
    {
        $modelName = $model ? class_basename($model) : 'السجل';
        $actions = [
            'create' => "تم إنشاء $modelName جديد",
            'update' => "تم تحديث $modelName",
            'delete' => "تم حذف $modelName",
            'force_logout' => "تم إجبار المستخدم على الخروج",
            'login' => "تم تسجيل الدخول",
            'logout' => "تم تسجيل الخروج",
        ];

        return $actions[$action] ?? "تم تنفيذ عملية $action";
    }
}

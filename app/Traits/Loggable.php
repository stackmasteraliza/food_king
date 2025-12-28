<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Models\OperationLog;
use App\Events\NewActivity;

trait Loggable
{
    protected static function bootLoggable()
    {
        static::updating(function ($model) {
            $model->logOperation('update', $model);
        });

        static::deleting(function ($model) {
            $model->logOperation('delete', $model);
        });
    }

    protected function logOperation($action, $model)
    {
        $original = method_exists($model, 'getOriginal')
            ? $model->getOriginal()
            : [];

        $changes = $action === 'update'
            ? $model->getDirty()
            : [];

        $log =  OperationLog::create([
            'user_id' => Auth::id() ?? null,
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'before' => $action === 'update' ? $original : $original,
            'after' => $action === 'update' ? array_merge($original, $changes) : null,
            'description' => $this->getOperationDescription($action, $model),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent()
        ]);
        event(new NewActivity($log));
    }

    protected function getOperationDescription($action, $model)
    {
        return match ($action) {
            'update' => "تعديل  {$this->getModelName($model)} السجل",
            'delete' => "حذف {$this->getModelName($model)} السجل",
            default => "Performed action on {$this->getModelName($model)}"
        };
    }

    protected function getModelName($model)
    {
        return class_basename($model);
    }
}

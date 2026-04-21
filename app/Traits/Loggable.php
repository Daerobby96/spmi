<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Loggable
{
    /**
     * Boot the trait to automatically log events.
     */
    protected static function bootLoggable()
    {
        static::created(function ($model) {
            $model->logActivity('created', $model->getLogDescription('created'));
        });

        static::updated(function ($model) {
            // Check if there are changes
            if ($model->isDirty()) {
                $model->logActivity('updated', $model->getLogDescription('updated'), [
                    'old' => array_intersect_key($model->getOriginal(), $model->getDirty()),
                    'new' => $model->getDirty(),
                ]);
            }
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted', $model->getLogDescription('deleted'));
        });
    }

    /**
     * Record a manual activity.
     */
    public function logActivity($action, $description, $properties = null)
    {
        return ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => $action,
            'model_type' => get_class($this),
            'model_id'   => $this->id,
            'description'=> $description,
            'properties' => $properties,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Default description generator (can be overridden in model).
     */
    protected function getLogDescription($event)
    {
        $name = class_basename($this);
        $identifier = $this->id;

        if (isset($this->name)) $identifier = $this->name;
        elseif (isset($this->nama)) $identifier = $this->nama;
        elseif (isset($this->kode_audit)) $identifier = $this->kode_audit;
        elseif (isset($this->kode_temuan)) $identifier = $this->kode_temuan;

        return "{$name} '{$identifier}' was {$event}";
    }
}

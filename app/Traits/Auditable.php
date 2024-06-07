<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    protected function logAction($action)
    {
        AuditLog::create([
            'action' => $action,
            'description' => $this->toJson(),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuditLogController extends Controller
{
    public function store(Request $request)
    {
        $log = AuditLog::create($request->all());
        return response()->json($log, 201);
    }
}

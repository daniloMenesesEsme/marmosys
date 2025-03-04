<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApprovalLog;

class ApprovalLogController extends Controller
{
    public function index()
    {
        $logs = ApprovalLog::with(['budget', 'user'])
            ->latest()
            ->paginate(15);
            
        return view('admin.approval-logs.index', compact('logs'));
    }
} 
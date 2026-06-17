<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = AuditLog::with('user')->recent();

        if ($request->user_id) {
            $query->byUser($request->user_id);
        }

        if ($request->action) {
            $query->byAction($request->action);
        }

        if ($request->model_type) {
            $query->byModel($request->model_type);
        }

        if ($request->start_date && $request->end_date) {
            $query->byDateRange($request->start_date, $request->end_date);
        }

        $logs = $query->paginate(25);

        return view('auditoria.index', compact('logs'));
    }

    public function show(AuditLog $auditLog)
    {
        return view('auditoria.show', compact('auditLog'));
    }
}

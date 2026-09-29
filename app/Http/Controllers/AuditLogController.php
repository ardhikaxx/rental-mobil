<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('view-audit-log');

        $modules = AuditLog::query()->select('module')->distinct()->orderBy('module')->pluck('module');
        $activities = AuditLog::query()->select('activity')->distinct()->orderBy('activity')->pluck('activity');

        $logs = AuditLog::query()
            ->with('user:id,name,username,role')
            ->when($request->filled('module'), fn ($query) => $query->where('module', $request->query('module')))
            ->when($request->filled('activity'), fn ($query) => $query->where('activity', $request->query('activity')))
            ->when($request->filled('user_id'), fn ($query) => $query->where('user_id', $request->query('user_id')))
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = trim((string) $request->query('search'));
                $query->where('description', 'like', "%{$term}%");
            })
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('audit-logs.index', [
            'logs' => $logs,
            'modules' => $modules,
            'activities' => $activities,
            'users' => User::orderBy('name')->get(['id', 'name']),
            'filterModule' => $request->query('module'),
            'filterActivity' => $request->query('activity'),
            'filterUser' => $request->query('user_id'),
            'search' => $request->query('search'),
        ]);
    }
}

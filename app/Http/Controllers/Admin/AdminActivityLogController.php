<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AdminActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user']);

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by log name
        if ($request->has('log_name') && $request->log_name) {
            $query->where('log_name', $request->log_name);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        $logs = $query->latest()->paginate(50);

        // Get unique log names for filter
        $logNames = ActivityLog::distinct()->pluck('log_name')->filter();

        return view('admin.activity-logs.index', compact('logs', 'logNames'));
    }

    public function show(ActivityLog $activityLog)
    {
        $activityLog->load(['user', 'subject', 'causer']);
        return view('admin.activity-logs.show', compact('activityLog'));
    }

    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();
        return redirect()->route('admin.activity-logs.index')
            ->with('success', 'Đã xóa log thành công.');
    }

    public function clear(Request $request)
    {
        $query = ActivityLog::query();

        // Only clear logs older than specified date
        if ($request->has('before_date')) {
            $query->whereDate('created_at', '<', $request->before_date);
        } else {
            // Default: clear logs older than 30 days
            $query->whereDate('created_at', '<', now()->subDays(30));
        }

        $count = $query->count();
        $query->delete();

        return back()->with('success', "Đã xóa {$count} log entries.");
    }
}

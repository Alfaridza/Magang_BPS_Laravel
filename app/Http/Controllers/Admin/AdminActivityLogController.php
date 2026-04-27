<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;

class AdminActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $action = $request->input('action');
        $adminId = $request->input('admin_id');

        $logs = AdminActivityLog::with('admin')
            ->whereNotIn('action', ['Login', 'Logout'])
            ->orderBy('created_at', 'desc');

        // filter action
        if ($action) {
            $logs->where('action', $action);
        }

        // filter admin
        if ($adminId) {
            $logs->where('admin_id', $adminId);
        }

        if ($search) {
            $logs->where(function ($query) use ($search) {
                $query->where('action', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%")
                      ->orWhere('subject_type', 'LIKE', "%{$search}%")
                      ->orWhereHas('admin', function ($query) use ($search) {
                          $query->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%");
                      });
            });
        }

        $logs = $logs->paginate(10)->withQueryString();
        $actions = AdminActivityLog::select('action')
                ->whereNotIn('action', ['Login', 'Logout'])
                ->distinct()
                ->pluck('action');
        $admins = \App\Models\Admin::select('id', 'name')->get();

        return view('admin.log_activity.index', compact(
            'logs', 'search', 'actions', 'admins', 'action', 'adminId'
        ));
    }
}

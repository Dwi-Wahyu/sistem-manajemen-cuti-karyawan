<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // Ambil list user untuk filter dropdown
        $users = User::orderBy('name')->pluck('name', 'id');

        // Query Logs
        $logs = ActivityLog::with('user')
            ->latest() // Urutkan dari yang terbaru

            // Filter berdasarkan User Pelaku
            ->when($request->user_id, function ($q, $uid) {
                $q->where('user_id', $uid);
            })

            // Filter berdasarkan Aksi (create/update/delete/approve/reject)
            ->when($request->action, function ($q, $act) {
                $q->where('action', $act);
            })

            // Pagination
            ->cursorPaginate(15)
            ->withQueryString();

        return view('admin.activity-logs.index', compact('logs', 'users'));
    }
}

<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $totalAssignments = Assignment::where('assigned_to', $userId)->count();
        $totalReports = Report::where('user_id', $userId)->count();

        // Filter: hanya tampilkan assignment yang belum selesai (status != 'done')
        $recentAssignments = Assignment::where('assigned_to', $userId)
            ->where('status', '!=', 'done')
            ->latest()
            ->take(5)
            ->get();

        $recentReports = Report::where('user_id', $userId)
            ->with('assignment')
            ->latest()
            ->take(5)
            ->get();

        return view('karyawan.dashboard', compact(
            'totalAssignments',
            'totalReports',
            'recentAssignments',
            'recentReports'
        ));
    }
}

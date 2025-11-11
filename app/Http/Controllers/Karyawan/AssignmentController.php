<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Assignment::where('assigned_to', Auth::id())
            ->with('assignedUser');

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assignments = $query->latest()->paginate(10);

        return view('karyawan.assignments.index', compact('assignments'));
    }

    public function show(Assignment $assignment)
    {
        // Pastikan assignment milik user yang login
        if ($assignment->assigned_to !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $assignment->load(['assignedUser', 'reports']);

        return view('karyawan.assignments.show', compact('assignment'));
    }
}

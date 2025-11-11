<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::where('user_id', Auth::id())
            ->with(['assignment', 'user']);

        // Search
        if ($request->filled('search')) {
            $query->whereHas('assignment', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('waktu_laporan', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('waktu_laporan', '<=', $request->end_date);
        }

        $reports = $query->latest('waktu_laporan')->paginate(10);

        return view('karyawan.reports.index', compact('reports'));
    }

    public function create()
    {
        // Ambil assignments yang ditugaskan ke user login
        $assignments = Assignment::where('assigned_to', Auth::id())
            ->orderBy('title')
            ->get();

        return view('karyawan.reports.create', compact('assignments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'jumlah_barang_dikirim' => 'required|integer|min:1',
            'lokasi' => 'required|string|max:1000',
            'catatan' => 'nullable|string|max:2000',
            'foto_bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_bukti_2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'waktu_laporan' => 'required|date',
        ]);

        // Pastikan assignment milik user yang login
        $assignment = Assignment::findOrFail($request->assignment_id);
        if ($assignment->assigned_to !== Auth::id()) {
            abort(403, 'Assignment tidak valid.');
        }

        $validated['user_id'] = Auth::id();

        // Handle file upload
        if ($request->hasFile('foto_bukti')) {
            $validated['foto_bukti'] = $request->file('foto_bukti')->store('reports', 'public');
        }

        if ($request->hasFile('foto_bukti_2')) {
            $validated['foto_bukti_2'] = $request->file('foto_bukti_2')->store('reports', 'public');
        }

        Report::create($validated);

        return redirect()->route('karyawan.reports.index')
            ->with('success', 'Laporan berhasil dibuat!');
    }

    public function show(Report $report)
    {
        // Pastikan report milik user yang login
        if ($report->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $report->load(['assignment', 'user']);

        return view('karyawan.reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        // Pastikan report milik user yang login
        if ($report->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $assignments = Assignment::where('assigned_to', Auth::id())
            ->orderBy('title')
            ->get();

        return view('karyawan.reports.edit', compact('report', 'assignments'));
    }

    public function update(Request $request, Report $report)
    {
        // Pastikan report milik user yang login
        if ($report->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $validated = $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'jumlah_barang_dikirim' => 'required|integer|min:1',
            'lokasi' => 'required|string|max:1000',
            'catatan' => 'nullable|string|max:2000',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_bukti_2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'waktu_laporan' => 'required|date',
        ]);

        // Pastikan assignment milik user yang login
        $assignment = Assignment::findOrFail($request->assignment_id);
        if ($assignment->assigned_to !== Auth::id()) {
            abort(403, 'Assignment tidak valid.');
        }

        // Handle file upload
        if ($request->hasFile('foto_bukti')) {
            // Delete old file
            if ($report->foto_bukti) {
                Storage::disk('public')->delete($report->foto_bukti);
            }
            $validated['foto_bukti'] = $request->file('foto_bukti')->store('reports', 'public');
        }

        if ($request->hasFile('foto_bukti_2')) {
            // Delete old file
            if ($report->foto_bukti_2) {
                Storage::disk('public')->delete($report->foto_bukti_2);
            }
            $validated['foto_bukti_2'] = $request->file('foto_bukti_2')->store('reports', 'public');
        }

        $report->update($validated);

        return redirect()->route('karyawan.reports.index')
            ->with('success', 'Laporan berhasil diperbarui!');
    }

    public function destroy(Report $report)
    {
        // Pastikan report milik user yang login
        if ($report->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        // Delete files
        if ($report->foto_bukti) {
            Storage::disk('public')->delete($report->foto_bukti);
        }
        if ($report->foto_bukti_2) {
            Storage::disk('public')->delete($report->foto_bukti_2);
        }

        $report->delete();

        return redirect()->route('karyawan.reports.index')
            ->with('success', 'Laporan berhasil dihapus!');
    }
}

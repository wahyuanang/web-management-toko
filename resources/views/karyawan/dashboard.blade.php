@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Notifications -->
        @php
            // Filter: hanya tampilkan notifikasi untuk assignment yang belum selesai
            $activeNotifications = auth()
                ->user()
                ->unreadNotifications->filter(function ($notification) {
                    if (isset($notification->data['assignment_id'])) {
                        $assignment = \App\Models\Assignment::find($notification->data['assignment_id']);
                        return $assignment && $assignment->status !== 'done';
                    }
                    return true;
                });
        @endphp

        @if ($activeNotifications->count() > 0)
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-bell text-blue-400"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-blue-800">
                            Anda memiliki {{ $activeNotifications->count() }} notifikasi baru
                        </h3>
                        <div class="mt-2 space-y-2">
                            @foreach ($activeNotifications->take(5) as $notification)
                                <div class="text-sm text-blue-700 bg-white p-3 rounded">
                                    <p class="font-medium">{{ $notification->data['message'] ?? 'Notifikasi baru' }}</p>
                                    @if (isset($notification->data['assignment_id']))
                                        <a href="{{ route('karyawan.assignments.show', $notification->data['assignment_id']) }}"
                                            class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center mt-1">
                                            Lihat Detail <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                        </a>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            <!-- Total Tugas -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Tugas</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalAssignments }}</p>
                    </div>
                    <div>
                        <i class="fas fa-clipboard-list text-4xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Laporan -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Laporan</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalReports }}</p>
                    </div>
                    <div>
                        <i class="fas fa-file-alt text-4xl text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Assignments -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Tugas Terbaru</h2>
                    <a href="{{ route('karyawan.assignments.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Lihat Semua →
                    </a>
                </div>
            </div>
            <div class="p-6">
                @forelse($recentAssignments as $assignment)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $assignment->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Target: {{ $assignment->qty_target }} unit
                            </p>
                        </div>
                        <div class="text-right ml-4">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            {{ $assignment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $assignment->status === 'completed' ? 'Selesai' : 'Berlangsung' }}
                            </span>
                            @if ($assignment->deadline)
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $assignment->deadline->format('d M Y') }}
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-8">Belum ada tugas</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Laporan Terbaru</h2>
                    <a href="{{ route('karyawan.reports.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Lihat Semua →
                    </a>
                </div>
            </div>
            <div class="p-6">
                @forelse($recentReports as $report)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $report->assignment->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $report->jumlah_barang_dikirim }} unit dikirim
                            </p>
                        </div>
                        <div class="text-right ml-4">
                            <p class="text-sm text-gray-900">{{ $report->waktu_laporan->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $report->waktu_laporan->format('H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-8">Belum ada laporan</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

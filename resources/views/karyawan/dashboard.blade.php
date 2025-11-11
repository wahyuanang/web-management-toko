@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            <!-- Total Tugas -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Tugas</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalAssignments }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-4">
                        <i class="fas fa-clipboard-list text-2xl text-blue-600"></i>
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
                    <div class="bg-green-100 rounded-full p-4">
                        <i class="fas fa-file-alt text-2xl text-green-600"></i>
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

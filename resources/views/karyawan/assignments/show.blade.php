@extends('layouts.app')

@section('title', 'Detail Tugas')
@section('page-title', 'Detail Tugas')

@section('content')
    <div class="space-y-6">
        <!-- Back Button -->
        <div>
            <a href="{{ route('karyawan.assignments.index') }}"
                class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Tugas
            </a>
        </div>

        <!-- Assignment Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Informasi Tugas</h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Judul Tugas</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $assignment->title }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Deskripsi</label>
                    <p class="text-gray-900">{{ $assignment->description }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Target Pengiriman</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $assignment->qty_target }} unit</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Batas Waktu</label>
                        @if ($assignment->deadline)
                            <p class="text-lg font-semibold text-gray-900">{{ $assignment->deadline->format('d M Y') }}</p>
                        @else
                            <p class="text-lg font-semibold text-gray-500">-</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $assignment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $assignment->status === 'completed' ? 'Selesai' : 'Berlangsung' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Report Button -->
        <div class="flex justify-end">
            <a href="{{ route('karyawan.reports.create', ['assignment_id' => $assignment->id]) }}"
                class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>Buat Laporan Untuk Tugas Ini
            </a>
        </div>

        <!-- Related Reports -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Laporan Terkait</h2>
            </div>
            <div class="p-6">
                @forelse($assignment->reports->where('user_id', auth()->id()) as $report)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $report->jumlah_barang_dikirim }} unit dikirim</p>
                            <p class="text-sm text-gray-500 mt-1">{{ $report->lokasi }}</p>
                        </div>
                        <div class="text-right ml-4">
                            <p class="text-sm text-gray-900">{{ $report->waktu_laporan->format('d M Y H:i') }}</p>
                            <a href="{{ route('karyawan.reports.show', $report) }}"
                                class="text-sm text-blue-600 hover:text-blue-800 mt-1 inline-block">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-8">Belum ada laporan untuk tugas ini</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

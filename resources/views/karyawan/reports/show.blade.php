@extends('layouts.app')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan')

@section('content')
<div class="space-y-6">
    <!-- Back Button & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
        <a href="{{ route('karyawan.reports.index') }}"
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Laporan
        </a>
        <div class="flex space-x-2">
            <a href="{{ route('karyawan.reports.edit', $report) }}"
               class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-medium transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Laporan
            </a>
            <button onclick="confirmDelete({{ $report->id }})"
                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                <i class="fas fa-trash mr-2"></i>Hapus
            </button>
        </div>
    </div>

    <!-- Report Details -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Informasi Laporan</h2>
        </div>
        <div class="p-6 space-y-6">
            <!-- Assignment Info -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clipboard-list text-blue-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800">Tugas Terkait</p>
                        <p class="text-lg font-semibold text-blue-900 mt-1">{{ $report->assignment->title }}</p>
                        <p class="text-sm text-blue-700 mt-1">{{ $report->assignment->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Jumlah Barang Dikirim</label>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-lg font-semibold bg-blue-100 text-blue-800">
                            <i class="fas fa-box mr-2"></i>{{ $report->jumlah_barang_dikirim }} unit
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Waktu Laporan</label>
                    <div class="flex items-center text-gray-900">
                        <i class="fas fa-calendar-alt text-gray-500 mr-2"></i>
                        <span class="font-semibold">{{ $report->waktu_laporan->format('d F Y, H:i') }} WIB</span>
                    </div>
                </div>
            </div>

            <!-- Lokasi -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-2">Lokasi Pengiriman</label>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-map-marker-alt text-red-500 mt-1 mr-3"></i>
                        <p class="text-gray-900 flex-1">{{ $report->lokasi }}</p>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            @if($report->catatan)
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Catatan</label>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-sticky-note text-yellow-600 mt-1 mr-3"></i>
                            <p class="text-gray-900 flex-1">{{ $report->catatan }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Foto Bukti -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-3">Foto Bukti</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($report->foto_bukti)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $report->foto_bukti) }}"
                                 alt="Foto Bukti 1"
                                 class="w-full h-64 object-cover rounded-lg shadow-md cursor-pointer transition-transform group-hover:scale-105"
                                 onclick="openModal('{{ asset('storage/' . $report->foto_bukti) }}')">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-opacity rounded-lg flex items-center justify-center">
                                <i class="fas fa-search-plus text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 text-center">Foto Bukti 1</p>
                        </div>
                    @endif

                    @if($report->foto_bukti_2)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $report->foto_bukti_2) }}"
                                 alt="Foto Bukti 2"
                                 class="w-full h-64 object-cover rounded-lg shadow-md cursor-pointer transition-transform group-hover:scale-105"
                                 onclick="openModal('{{ asset('storage/' . $report->foto_bukti_2) }}')">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-opacity rounded-lg flex items-center justify-center">
                                <i class="fas fa-search-plus text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 text-center">Foto Bukti 2</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Metadata -->
            <div class="pt-4 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">Dibuat:</span> {{ $report->created_at->format('d F Y, H:i') }} WIB
                    </div>
                    <div>
                        <span class="font-medium">Terakhir diubah:</span> {{ $report->updated_at->format('d F Y, H:i') }} WIB
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4" onclick="closeModal()">
    <div class="relative max-w-7xl max-h-full">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 text-3xl z-10">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImage" src="" alt="Preview" class="max-w-full max-h-screen object-contain">
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Hapus Laporan</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="flex space-x-4 px-4 py-3">
                <button onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                    Batal
                </button>
                <form id="deleteForm" method="POST" action="{{ route('karyawan.reports.destroy', $report) }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc;
        modal.classList.remove('hidden');
    }

    function closeModal(event) {
        if (event) event.stopPropagation();
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
    }

    function confirmDelete(reportId) {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>
@endpush
@endsection

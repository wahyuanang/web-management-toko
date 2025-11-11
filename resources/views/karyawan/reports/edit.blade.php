@extends('layouts.app')

@section('title', 'Edit Laporan')
@section('page-title', 'Edit Laporan')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('karyawan.reports.index') }}"
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Laporan
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Edit Informasi Laporan</h2>
        </div>

        <form method="POST" action="{{ route('karyawan.reports.update', $report) }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Assignment Selection -->
            <div>
                <label for="assignment_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Tugas <span class="text-red-500">*</span>
                </label>
                <select name="assignment_id"
                        id="assignment_id"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('assignment_id') border-red-500 @enderror">
                    <option value="">-- Pilih Tugas --</option>
                    @foreach($assignments as $assignment)
                        <option value="{{ $assignment->id }}"
                                data-qty="{{ $assignment->qty_target }}"
                                {{ old('assignment_id', $report->assignment_id) == $assignment->id ? 'selected' : '' }}>
                            {{ $assignment->title }} (Target: {{ $assignment->qty_target }} unit)
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">
                    <i class="fas fa-info-circle"></i> Hanya menampilkan tugas yang belum selesai diantar
                </p>
                @error('assignment_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jumlah Barang Dikirim -->
            <div>
                <label for="jumlah_barang_dikirim" class="block text-sm font-medium text-gray-700 mb-2">
                    Jumlah Barang Dikirim <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       name="jumlah_barang_dikirim"
                       id="jumlah_barang_dikirim"
                       value="{{ old('jumlah_barang_dikirim', $report->jumlah_barang_dikirim) }}"
                       min="1"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jumlah_barang_dikirim') border-red-500 @enderror"
                       placeholder="Masukkan jumlah barang">
                @error('jumlah_barang_dikirim')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Lokasi -->
            <div>
                <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                    Lokasi Pengiriman <span class="text-red-500">*</span>
                </label>
                <textarea name="lokasi"
                          id="lokasi"
                          rows="3"
                          required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('lokasi') border-red-500 @enderror"
                          placeholder="Masukkan alamat lengkap lokasi pengiriman">{{ old('lokasi', $report->lokasi) }}</textarea>
                @error('lokasi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan -->
            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan (Opsional)
                </label>
                <textarea name="catatan"
                          id="catatan"
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('catatan') border-red-500 @enderror"
                          placeholder="Tambahkan catatan jika diperlukan">{{ old('catatan', $report->catatan) }}</textarea>
                @error('catatan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto Bukti 1 -->
            <div>
                <label for="foto_bukti" class="block text-sm font-medium text-gray-700 mb-2">
                    Foto Bukti (Biarkan kosong jika tidak ingin mengubah)
                </label>

                @if($report->foto_bukti)
                    <div class="mb-3">
                        <p class="text-sm text-gray-600 mb-2">Foto saat ini:</p>
                        <img src="{{ asset('storage/' . $report->foto_bukti) }}"
                             alt="Current Photo"
                             class="max-w-xs rounded-lg shadow-md">
                    </div>
                @endif

                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                        <div class="flex text-sm text-gray-600">
                            <label for="foto_bukti" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                <span>Upload foto baru</span>
                                <input id="foto_bukti"
                                       name="foto_bukti"
                                       type="file"
                                       accept="image/jpeg,image/png,image/jpg"
                                       onchange="previewImage(this, 'preview1')"
                                       class="sr-only">
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, JPEG maksimal 2MB</p>
                    </div>
                </div>
                <div id="preview1" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Preview foto baru:</p>
                    <img src="" alt="Preview" class="max-w-xs rounded-lg shadow-md">
                </div>
                @error('foto_bukti')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto Bukti 2 -->
            <div>
                <label for="foto_bukti_2" class="block text-sm font-medium text-gray-700 mb-2">
                    Foto Bukti 2 (Opsional - Biarkan kosong jika tidak ingin mengubah)
                </label>

                @if($report->foto_bukti_2)
                    <div class="mb-3">
                        <p class="text-sm text-gray-600 mb-2">Foto saat ini:</p>
                        <img src="{{ asset('storage/' . $report->foto_bukti_2) }}"
                             alt="Current Photo 2"
                             class="max-w-xs rounded-lg shadow-md">
                    </div>
                @endif

                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                        <div class="flex text-sm text-gray-600">
                            <label for="foto_bukti_2" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                <span>Upload foto baru</span>
                                <input id="foto_bukti_2"
                                       name="foto_bukti_2"
                                       type="file"
                                       accept="image/jpeg,image/png,image/jpg"
                                       onchange="previewImage(this, 'preview2')"
                                       class="sr-only">
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, JPEG maksimal 2MB</p>
                    </div>
                </div>
                <div id="preview2" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Preview foto baru:</p>
                    <img src="" alt="Preview" class="max-w-xs rounded-lg shadow-md">
                </div>
                @error('foto_bukti_2')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Waktu Laporan -->
            <div>
                <label for="waktu_laporan" class="block text-sm font-medium text-gray-700 mb-2">
                    Waktu Laporan <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local"
                       name="waktu_laporan"
                       id="waktu_laporan"
                       value="{{ old('waktu_laporan', $report->waktu_laporan->format('Y-m-d\TH:i')) }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('waktu_laporan') border-red-500 @enderror">
                @error('waktu_laporan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 pt-4">
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    <i class="fas fa-save mr-2"></i>Update Laporan
                </button>
                <a href="{{ route('karyawan.reports.index') }}"
                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-medium text-center transition-colors">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Preview image
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const img = preview.querySelector('img');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.remove('hidden');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection

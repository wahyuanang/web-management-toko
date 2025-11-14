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

            <form method="POST" action="{{ route('karyawan.reports.update', $report) }}" enctype="multipart/form-data"
                class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Assignment Selection -->
                <div>
                    <label for="assignment_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Tugas <span class="text-red-500">*</span>
                    </label>
                    <select name="assignment_id" id="assignment_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('assignment_id') border-red-500 @enderror">
                        <option value="">-- Pilih Tugas --</option>
                        @foreach ($assignments as $assignment)
                            <option value="{{ $assignment->id }}" data-qty="{{ $assignment->qty_target }}"
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
                    <input type="number" name="jumlah_barang_dikirim" id="jumlah_barang_dikirim"
                        value="{{ old('jumlah_barang_dikirim', $report->jumlah_barang_dikirim) }}" min="1" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jumlah_barang_dikirim') border-red-500 @enderror"
                        placeholder="Masukkan jumlah barang">
                    @error('jumlah_barang_dikirim')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Perhitungan Harga (Display) -->
                <div id="price-section" class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                    <h3 class="text-sm font-semibold text-green-900 mb-3 flex items-center">
                        <i class="fas fa-money-bill-wave mr-2"></i> Perhitungan Harga
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-green-700">Harga per <span
                                    id="satuan-display">{{ $report->assignment->product->satuan ?? 'unit' }}</span>:</span>
                            <span class="font-semibold text-green-900"
                                id="harga-display">{{ 'Rp ' . number_format($report->harga_per_pcs, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-green-700">Jumlah barang:</span>
                            <span class="font-semibold text-green-900"
                                id="qty-display">{{ $report->jumlah_barang_dikirim }}
                                {{ $report->assignment->product->satuan ?? 'unit' }}</span>
                        </div>
                        <div class="border-t-2 border-green-200 pt-2 mt-2">
                            <div class="flex justify-between items-center">
                                <span class="text-green-900 font-semibold text-base">Total Uang Diterima:</span>
                                <span class="font-bold text-green-900 text-lg"
                                    id="total-display">{{ 'Rp ' . number_format($report->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <p class="text-xs text-green-600 mt-2">
                            <i class="fas fa-info-circle"></i> Ini adalah total uang yang harus diterima
                        </p>
                    </div>
                </div>

                <!-- Hidden fields untuk harga -->
                <input type="hidden" name="harga_per_pcs" id="harga_per_pcs"
                    value="{{ old('harga_per_pcs', $report->harga_per_pcs) }}">
                <input type="hidden" name="total_harga" id="total_harga"
                    value="{{ old('total_harga', $report->total_harga) }}">

                <!-- Lokasi -->
                <div>
                    <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                        Lokasi Pengiriman <span class="text-red-500">*</span>
                    </label>
                    <textarea name="lokasi" id="lokasi" rows="3" required
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
                    <textarea name="catatan" id="catatan" rows="3"
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

                    @if ($report->foto_bukti)
                        <div class="mb-3">
                            <p class="text-sm text-gray-600 mb-2">Foto saat ini:</p>
                            <div class="relative inline-block group cursor-pointer image-preview-trigger"
                                data-image-src="{{ asset('storage/' . $report->foto_bukti) }}"
                                data-image-title="Foto Bukti 1">
                                <img src="{{ asset('storage/' . $report->foto_bukti) }}" alt="Current Photo"
                                    class="max-w-xs rounded-lg shadow-md hover:shadow-xl transition-shadow">
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-lg flex items-center justify-center pointer-events-none">
                                    <i
                                        class="fas fa-search-plus text-white text-3xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-blue-600">
                                <i class="fas fa-info-circle"></i> Klik gambar untuk melihat ukuran penuh
                            </p>
                        </div>
                    @endif

                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="foto_bukti"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span>Upload foto baru</span>
                                    <input id="foto_bukti" name="foto_bukti" type="file"
                                        accept="image/jpeg,image/png,image/jpg" onchange="previewImage(this, 'preview1')"
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

                    @if ($report->foto_bukti_2)
                        <div class="mb-3">
                            <p class="text-sm text-gray-600 mb-2">Foto saat ini:</p>
                            <div class="relative inline-block group cursor-pointer image-preview-trigger"
                                data-image-src="{{ asset('storage/' . $report->foto_bukti_2) }}"
                                data-image-title="Foto Bukti 2">
                                <img src="{{ asset('storage/' . $report->foto_bukti_2) }}" alt="Current Photo 2"
                                    class="max-w-xs rounded-lg shadow-md hover:shadow-xl transition-shadow">
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-lg flex items-center justify-center pointer-events-none">
                                    <i
                                        class="fas fa-search-plus text-white text-3xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-blue-600">
                                <i class="fas fa-info-circle"></i> Klik gambar untuk melihat ukuran penuh
                            </p>
                        </div>
                    @endif

                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="foto_bukti_2"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span>Upload foto baru</span>
                                    <input id="foto_bukti_2" name="foto_bukti_2" type="file"
                                        accept="image/jpeg,image/png,image/jpg" onchange="previewImage(this, 'preview2')"
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
                    <input type="datetime-local" name="waktu_laporan" id="waktu_laporan"
                        value="{{ old('waktu_laporan', $report->waktu_laporan->format('Y-m-d\TH:i')) }}" required
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

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center p-4">
        <div class="relative max-w-7xl w-full h-full flex flex-col">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 id="modalTitle" class="text-white text-xl font-semibold"></h3>
                <button onclick="closeImageModal()" class="text-white hover:text-gray-300 transition-colors">
                    <i class="fas fa-times text-3xl"></i>
                </button>
            </div>

            <!-- Modal Image -->
            <div class="flex-1 flex items-center justify-center overflow-auto">
                <img id="modalImage" src="" alt="Full Size Preview"
                    class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
            </div>

            <!-- Modal Footer -->
            <div class="mt-4 text-center">
                <p class="text-white text-sm">
                    <i class="fas fa-info-circle"></i> Klik di luar gambar atau tombol X untuk menutup
                </p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-calculate price
            const jumlahBarangInput = document.getElementById('jumlah_barang_dikirim');
            const assignmentSelect = document.getElementById('assignment_id');
            const hargaPerPcsInput = document.getElementById('harga_per_pcs');
            const totalHargaInput = document.getElementById('total_harga');

            let currentHargaPerPcs = parseFloat(hargaPerPcsInput.value) || 0;
            let currentSatuan = '{{ $report->assignment->product->satuan ?? 'unit' }}';

            // Function to format currency
            function formatRupiah(amount) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(amount);
            }

            // Function to calculate total
            function calculateTotal() {
                const qty = parseInt(jumlahBarangInput.value) || 0;
                const harga = currentHargaPerPcs;
                const total = qty * harga;

                // Update display
                document.getElementById('harga-display').textContent = formatRupiah(harga);
                document.getElementById('qty-display').textContent = qty + ' ' + currentSatuan;
                document.getElementById('total-display').textContent = formatRupiah(total);

                // Update hidden fields
                hargaPerPcsInput.value = harga;
                totalHargaInput.value = total;
            }

            // Recalculate when jumlah barang changes
            if (jumlahBarangInput) {
                jumlahBarangInput.addEventListener('input', function() {
                    calculateTotal();
                });
            }

            // Recalculate when assignment changes
            if (assignmentSelect) {
                assignmentSelect.addEventListener('change', function() {
                    const assignmentId = this.value;

                    if (!assignmentId) {
                        return;
                    }

                    // Fetch assignment details via API
                    fetch(`/karyawan/api/assignments/${assignmentId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.data.product) {
                                currentHargaPerPcs = parseFloat(data.data.product.harga_per_pcs) || 0;
                                currentSatuan = data.data.product.satuan || 'unit';
                                document.getElementById('satuan-display').textContent = currentSatuan;

                                // Recalculate
                                calculateTotal();
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching assignment details:', error);
                        });
                });
            }

            // Preview image for file upload
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

            // Open image modal
            function openImageModal(imageSrc, title) {
                const modal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');
                const modalTitle = document.getElementById('modalTitle');

                if (modal && modalImage && modalTitle) {
                    modalImage.src = imageSrc;
                    modalTitle.textContent = title;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }
            }

            // Close image modal
            function closeImageModal() {
                const modal = document.getElementById('imageModal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.style.overflow = 'auto';
                }
            }

            // Initialize when DOM is ready
            document.addEventListener('DOMContentLoaded', function() {
                // Add click event to all image preview triggers
                const imagePreviewTriggers = document.querySelectorAll('.image-preview-trigger');
                imagePreviewTriggers.forEach(function(trigger) {
                    trigger.addEventListener('click', function() {
                        const imageSrc = this.getAttribute('data-image-src');
                        const imageTitle = this.getAttribute('data-image-title');
                        openImageModal(imageSrc, imageTitle);
                    });
                });

                // Close modal when clicking outside the image
                const imageModal = document.getElementById('imageModal');
                if (imageModal) {
                    imageModal.addEventListener('click', function(e) {
                        if (e.target === this) {
                            closeImageModal();
                        }
                    });
                }

                // Close modal with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeImageModal();
                    }
                });
            });
        </script>
    @endpush
@endsection

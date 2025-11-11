@extends('layouts.app')

@section('title', 'Tugas Saya')
@section('page-title', 'Tugas Saya')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border border-gray-200">
            <form method="GET" action="{{ route('karyawan.assignments.index') }}" class="space-y-3 sm:space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Tugas</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari judul atau deskripsi..."
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status"
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="flex items-end sm:col-span-2 lg:col-span-1">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2 rounded-lg font-medium transition-colors text-sm sm:text-base">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

      <!-- Assignments List -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <!-- Mobile Card View (Hidden on md and up) -->
    <div class="md:hidden divide-y divide-gray-200">
        @forelse($assignments as $assignment)
            <div class="p-4 hover:bg-gray-50 transition-colors">
                <div class="space-y-3">
                    <!-- Title & Description -->
                    <div>
                        <h3 class="font-semibold text-gray-900 text-base">{{ $assignment->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $assignment->description }}</p>
                    </div>
                    
                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-500 block">Target</span>
                            <span class="text-gray-900 font-medium">{{ $assignment->qty_target }} unit</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block">Batas Waktu</span>
                            @if ($assignment->deadline)
                                <span class="text-gray-900 font-medium block">{{ $assignment->deadline->format('d M Y') }}</span>
                                <span class="text-xs text-gray-500">{{ $assignment->deadline->format('H:i') }}</span>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Status & Action -->
                    <div class="flex items-center justify-between pt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                            @if($assignment->status === 'done') bg-green-100 text-green-800
                            @elseif($assignment->status === 'in_progress') bg-yellow-100 text-yellow-800
                            @elseif($assignment->status === 'pending') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800
                            @endif">
                            @if($assignment->status === 'done') Selesai
                            @elseif($assignment->status === 'in_progress') Berlangsung
                            @elseif($assignment->status === 'pending') Pending
                            @else Dibatalkan
                            @endif
                        </span>
                        <a href="{{ route('karyawan.assignments.show', $assignment) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-medium">
                            <i class="fas fa-eye mr-2"></i>Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-inbox text-4xl mb-3 text-gray-400"></i>
                <p class="text-base">Belum ada tugas yang ditugaskan</p>
            </div>
        @endforelse
    </div>

    <!-- Desktop Table View (Hidden on mobile) -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tugas
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Target
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Batas Waktu
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($assignments as $assignment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-900">{{ $assignment->title }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($assignment->description, 50) }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $assignment->qty_target }} unit</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($assignment->deadline)
                                <div>
                                    <span class="text-sm text-gray-900 block">{{ $assignment->deadline->format('d M Y') }}</span>
                                    <span class="text-xs text-gray-500">{{ $assignment->deadline->format('H:i') }}</span>
                                </div>
                            @else
                                <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                @if($assignment->status === 'done') bg-green-100 text-green-800
                                @elseif($assignment->status === 'in_progress') bg-yellow-100 text-yellow-800
                                @elseif($assignment->status === 'pending') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800
                                @endif">
                                @if($assignment->status === 'done') Selesai
                                @elseif($assignment->status === 'in_progress') Berlangsung
                                @elseif($assignment->status === 'pending') Pending
                                @else Dibatalkan
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <a href="{{ route('karyawan.assignments.show', $assignment) }}"
                                class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                <i class="fas fa-eye mr-2"></i>Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3"></i>
                            <p>Belum ada tugas yang ditugaskan</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($assignments->hasPages())
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
            {{ $assignments->links() }}
        </div>
    @endif
</div>
    </div>
@endsection

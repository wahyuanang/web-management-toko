<!-- Overlay untuk mobile -->
<div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black opacity-50 lg:hidden">
</div>

<!-- Sidebar -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">

    <!-- Logo -->
    <div class="flex items-center justify-center h-16 bg-blue-900 border-b border-blue-700">
        <span class="text-xl font-bold">
            <i class="fas fa-box-open mr-2"></i>
            Toko Management
        </span>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-4">
        <a href="{{ route('karyawan.dashboard') }}"
            class="flex items-center px-4 py-3 mb-2 rounded-lg transition-colors {{ request()->routeIs('karyawan.dashboard') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
            <i class="fas fa-home w-5"></i>
            <span class="ml-3">Dashboard</span>
        </a>

        <a href="{{ route('karyawan.assignments.index') }}"
            class="flex items-center px-4 py-3 mb-2 rounded-lg transition-colors {{ request()->routeIs('karyawan.assignments.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
            <i class="fas fa-clipboard-list w-5"></i>
            <span class="ml-3">Tugas Saya</span>
        </a>

        <a href="{{ route('karyawan.reports.index') }}"
            class="flex items-center px-4 py-3 mb-2 rounded-lg transition-colors {{ request()->routeIs('karyawan.reports.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
            <i class="fas fa-file-alt w-5"></i>
            <span class="ml-3">Laporan Saya</span>
        </a>

        <div class="border-t border-blue-700 my-4"></div>

        <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center px-4 py-3 mb-2 rounded-lg text-blue-100 hover:bg-red-600 transition-colors">
                <i class="fas fa-sign-out-alt w-5"></i>
                <span class="ml-3">Keluar</span>
            </button>
        </form>
    </nav>
</aside>

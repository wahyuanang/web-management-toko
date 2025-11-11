<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-store text-white text-sm"></i>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Toko Management</span>
                </div>
                <div class="flex items-center space-x-3">
                    @auth
                        @if(auth()->user()->hasRole('admin'))
                            <a href="/admin" class="text-gray-700 hover:text-blue-600 font-medium transition-colors px-3 py-2 rounded-lg hover:bg-blue-50">
                                <i class="fas fa-chart-line mr-2"></i>Dashboard Admin
                            </a>
                        @elseif(auth()->user()->hasRole('karyawan'))
                            <a href="{{ route('karyawan.dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors px-3 py-2 rounded-lg hover:bg-blue-50">
                                <i class="fas fa-chart-line mr-2"></i>Dashboard Karyawan
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-700 font-medium transition-colors px-3 py-2 rounded-lg hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors px-4 py-2 rounded-lg hover:bg-blue-50">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-5 py-2 rounded-lg font-medium transition-all shadow-md hover:shadow-lg">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center pt-20 pb-16">
            <!-- Main Heading -->
            <div class="mb-6 space-y-3">
                <div class="inline-block px-4 py-2 bg-blue-100 rounded-full mb-4">
                    <span class="text-blue-700 font-semibold text-sm">✨ Sistem Management Modern</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
                    Kelola Toko Anda <br>
                    <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Dengan Mudah</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Platform terintegrasi untuk manajemen tugas, laporan, dan operasional toko yang efisien
                </p>
            </div>

            @guest
                <div class="flex flex-col sm:flex-row justify-center gap-4 mb-12">
                    <a href="{{ route('register') }}"
                       class="group bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                        Mulai Sekarang
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="{{ route('login') }}"
                       class="bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 px-8 py-4 rounded-xl font-semibold text-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login
                    </a>
                </div>
            @endguest

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-20">
                <!-- Feature 1 -->
                <div class="group bg-white rounded-2xl shadow-md hover:shadow-xl p-8 transition-all duration-300 border border-gray-100 hover:border-blue-200 hover:-translate-y-1">
                    <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl mb-5 group-hover:scale-110 transition-transform shadow-lg">
                        <i class="fas fa-tasks text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Manajemen Tugas</h3>
                    <p class="text-gray-600 leading-relaxed">Kelola dan pantau tugas karyawan dengan sistem yang terorganisir dan mudah digunakan</p>
                </div>

                <!-- Feature 2 -->
                <div class="group bg-white rounded-2xl shadow-md hover:shadow-xl p-8 transition-all duration-300 border border-gray-100 hover:border-green-200 hover:-translate-y-1">
                    <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl mb-5 group-hover:scale-110 transition-transform shadow-lg">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Laporan Real-time</h3>
                    <p class="text-gray-600 leading-relaxed">Buat dan pantau laporan secara langsung dengan visualisasi data yang informatif</p>
                </div>

                <!-- Feature 3 -->
                <div class="group bg-white rounded-2xl shadow-md hover:shadow-xl p-8 transition-all duration-300 border border-gray-100 hover:border-purple-200 hover:-translate-y-1">
                    <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl mb-5 group-hover:scale-110 transition-transform shadow-lg">
                        <i class="fas fa-shield-alt text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Aman & Terpercaya</h3>
                    <p class="text-gray-600 leading-relaxed">Data Anda dilindungi dengan enkripsi dan sistem keamanan tingkat enterprise</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-20 py-8 border-t border-gray-200 bg-white/50 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-600">
                <p>&copy; 2024 Toko Management. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>

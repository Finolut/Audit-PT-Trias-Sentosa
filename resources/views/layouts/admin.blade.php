<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Audit Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .active-link { 
            background-color: #eff6ff; 
            color: #1d4ed8; 
            font-weight: bold; 
            border-right: 3px solid #2563eb; 
        }
        .dept-item { 
            transition: all 0.2s ease; 
        }
        .sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            z-index: 50;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar-hidden {
            transform: translateX(-100%);
        }
        .overlay {
            transition: opacity 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 40;
            display: none;
        }
        .overlay-visible {
            display: block;
        }
        @media (min-width: 1024px) {
            .sidebar {
                transform: translateX(0);
            }
            .sidebar-hidden {
                transform: translateX(0);
            }
            .overlay {
                display: none !important;
            }
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- Mobile Overlay -->
    <div id="overlay" class="overlay"></div>

    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR -->
        <div id="sidebar" class="sidebar w-64 bg-white border-r border-gray-200 flex flex-col h-full">
            <!-- Header Sidebar -->
            <div class="p-6 border-b border-gray-100">
                <h1 class="text-xl font-extrabold text-blue-800 uppercase leading-none tracking-tight">PT Trias Sentosa</h1>
                <p class="text-[10px] font-semibold text-gray-400 mt-1.5 uppercase tracking-wider">Audit System Admin</p>
            </div>

            <!-- Navigation List -->
            <nav class="p-3 space-y-1 overflow-y-auto flex-1">
                <div class="px-3 mb-2 mt-2">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Menu Utama</span>
                </div>

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.dashboard') ? 'active-link' : '' }}">
                    <span class="mr-3 text-lg">📊</span> Dashboard Overview
                </a>

                <a href="{{ route('admin.dept_status') }}"
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.dept_status') ? 'active-link' : '' }}">
                    <span class="mr-3 text-lg">📋</span> Status Audit Dept.
                </a>

                <!-- Manajemen Data -->
                <div class="px-3 mb-2 mt-6">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Manajemen Data</span>
                </div>

                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.users.*', 'admin.auditors.*') ? 'active-link' : '' }}">
                    <span class="mr-3 text-lg">👥</span> Manajemen User & Auditor
                </a>

                <a href="{{ route('admin.items.index') }}"
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.items.*') ? 'active-link' : '' }}">
                    <span class="mr-3 text-lg">📝</span> Kelola Soal Audit
                </a>

                <!-- Cari Laporan moved to top section -->
                <a href="{{ route('admin.search.report') }}"
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.search.report') ? 'active-link' : '' }}">
                    <span class="mr-3 text-lg">🔍</span> Cari Laporan
                </a>
            </nav>

            <!-- User Profile Footer -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center justify-between group">
                    <div class="flex items-center">
                        <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                        </div>
                        <div class="ml-3 overflow-hidden">
                            <p class="text-sm font-bold text-gray-700 truncate capitalize">
                                {{ auth()->user()->name ?? 'Administrator' }}
                            </p>
                            <p class="text-[10px] font-medium text-blue-600 uppercase tracking-tight">
                                {{ auth()->user()->role ?? 'Admin' }}
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                title="Logout"
                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Bar for Mobile -->
            <header class="lg:hidden p-4 bg-white border-b border-gray-200 flex items-center">
                <button id="menu-toggle" class="p-2 mr-3 text-gray-600 hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h2 class="text-lg font-semibold">Dashboard</h2>
            </header>

            <main class="flex-1 overflow-y-auto p-4 lg:p-8 pb-20 lg:pb-20">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const menuToggle = document.getElementById('menu-toggle');
            
            // Toggle sidebar on mobile
            if (menuToggle) {
                menuToggle.addEventListener('click', () => {
                    sidebar.classList.remove('sidebar-hidden');
                    overlay.classList.add('overlay-visible');
                });
            }
            
            // Close sidebar when clicking overlay
            if (overlay) {
                overlay.addEventListener('click', () => {
                    sidebar.classList.add('sidebar-hidden');
                    overlay.classList.remove('overlay-visible');
                });
            }
            
            // Close sidebar when clicking navigation links on mobile
            const navLinks = document.querySelectorAll('#sidebar a');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        sidebar.classList.add('sidebar-hidden');
                        overlay.classList.remove('overlay-visible');
                    }
                });
            });
            
            // Handle window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('sidebar-hidden');
                    overlay.classList.remove('overlay-visible');
                } else {
                    sidebar.classList.add('sidebar-hidden');
                    overlay.classList.remove('overlay-visible');
                }
            });
        });
    </script>
</body>
</html>


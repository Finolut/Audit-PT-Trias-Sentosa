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

        /* Sidebar Transitions */
        .sidebar {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Mobile Overlay Transition */
        .overlay {
            transition: opacity 0.3s ease;
        }

        /* Hide Scrollbar for clean look */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Utility untuk menyembunyikan elemen saat mini */
        .sidebar-mini .hide-on-mini {
            display: none !important;
        }
        .sidebar-mini .justify-center-on-mini {
            justify-content: center !important;
        }
        .sidebar-mini .no-margin-on-mini {
            margin-right: 0 !important;
        }

        /* Logo logic */
        .logo-expanded { display: block; }
        .logo-collapsed { display: none; }
        .sidebar-mini .logo-expanded { display: none; }
        .sidebar-mini .logo-collapsed { display: block; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- Mobile Overlay -->
    <div id="overlay" class="overlay fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR -->
        <div id="sidebar" class="sidebar fixed inset-y-0 left-0 z-50 flex flex-col bg-white border-r border-gray-200 h-full transform -translate-x-full lg:translate-x-0 lg:static w-64">
            
            <!-- Header Sidebar -->
            <div class="h-16 flex items-center justify-center border-b border-gray-100 px-4">
                <div class="logo-expanded text-left w-full">
                    <a href="https://trias-sentosa.com" class="block">
                        <img src="https://trias-sentosa.com/images/logo.webp" alt="PT Trias Sentosa" class="h-8 object-contain">
                    </a>
                </div>
                <div class="logo-collapsed text-center hidden">
                    <a href="https://trias-sentosa.com" class="block">
                        <img src="https://trias-sentosa.com/images/logo.webp" alt="PT Trias Sentosa" class="h-8 object-contain">
                    </a>
                </div>
            </div>

            <!-- Navigation List -->
            <nav class="flex-1 overflow-y-auto py-4 no-scrollbar flex flex-col gap-1 px-3">
                
                <div class="px-3 mb-2 mt-2 hide-on-mini transition-opacity duration-200">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Menu Utama</span>
                </div>

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.dashboard') ? 'active-link' : '' }}">
                    <span class="text-xl min-w-[24px] text-center no-margin-on-mini mr-3">📊</span> 
                    <span class="hide-on-mini whitespace-nowrap">Dashboard Overview</span>
                </a>

                <a href="{{ route('admin.dept_status') }}"
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.dept_status') ? 'active-link' : '' }}">
                    <span class="text-xl min-w-[24px] text-center no-margin-on-mini mr-3">📋</span> 
                    <span class="hide-on-mini whitespace-nowrap">Status Audit Dept.</span>
                </a>

                <div class="px-3 mb-2 mt-6 hide-on-mini transition-opacity duration-200">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Manajemen Data</span>
                </div>

                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.users.*', 'admin.auditors.*') ? 'active-link' : '' }}">
                    <span class="text-xl min-w-[24px] text-center no-margin-on-mini mr-3">👥</span> 
                    <span class="hide-on-mini whitespace-nowrap">Manajemen User & Auditor</span>
                </a>

                <a href="{{ route('admin.items.index') }}"
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.items.*') ? 'active-link' : '' }}">
                    <span class="text-xl min-w-[24px] text-center no-margin-on-mini mr-3">📝</span> 
                    <span class="hide-on-mini whitespace-nowrap">Kelola Soal Audit</span>
                </a>

                <a href="{{ route('admin.search.report') }}"
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.search.report') ? 'active-link' : '' }}">
                    <span class="text-xl min-w-[24px] text-center no-margin-on-mini mr-3">🔍</span> 
                    <span class="hide-on-mini whitespace-nowrap">Cari Laporan</span>
                </a>

                <!-- Minimize Button (Desktop Only) -->
                <div class="mt-auto pt-4 border-t border-gray-100 hidden lg:block">
                    <button id="btn-minimize" class="w-full flex items-center px-3 py-2.5 text-sm font-medium text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors focus:outline-none">
                        <div id="icon-collapse" class="min-w-[24px] mr-3 no-margin-on-mini flex justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                            </svg>
                        </div>
                        <div id="icon-expand" class="min-w-[24px] mr-3 no-margin-on-mini hidden justify-center">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                            </svg>
                        </div>
                        <span class="hide-on-mini whitespace-nowrap">Minimize Menu</span>
                    </button>
                </div>

                <!-- User Profile Footer -->
                <div class="mt-auto pt-4 border-t border-gray-100">
                    <div class="p-4">
                        <div class="flex items-center group justify-between sidebar-mini:justify-center">
                            <div class="flex items-center justify-center-on-mini w-full">
                                <div class="w-9 h-9 min-w-[2.25rem] rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                                </div>
                                <div class="ml-3 overflow-hidden hide-on-mini">
                                    <p class="text-sm font-bold text-gray-700 truncate capitalize">
                                        {{ auth()->user()->name ?? 'Administrator' }}
                                    </p>
                                    <p class="text-[10px] font-medium text-blue-600 uppercase tracking-tight">
                                        {{ auth()->user()->role ?? 'Admin' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Dropdown Menu for Logout -->
                            <div class="relative">
                                <button id="user-menu-btn" type="button"
                                        class="p-2 text-gray-400 hover:text-gray-600 rounded-lg transition-all duration-200 sidebar-mini:hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 6h-6" />
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden z-50">
                                    <form method="POST" action="{{ route('logout') }}" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                                        @csrf
                                        <button type="submit" class="w-full text-left">Logout</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0 bg-gray-50 transition-all duration-300">
            <!-- Top Bar for Mobile -->
            <header class="lg:hidden p-4 bg-white border-b border-gray-200 flex items-center shadow-sm">
                <button id="menu-toggle" class="p-2 mr-3 text-gray-600 hover:text-gray-900 rounded-md hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h2 class="text-lg font-bold text-gray-800">Dashboard</h2>
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
            const btnMinimize = document.getElementById('btn-minimize');
            const iconCollapse = document.getElementById('icon-collapse');
            const iconExpand = document.getElementById('icon-expand');

            // --- MOBILE LOGIC ---
            if (menuToggle) {
                menuToggle.addEventListener('click', () => {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
            }

            // Close sidebar on mobile when clicking nav link
            const navLinks = document.querySelectorAll('#sidebar a');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        sidebar.classList.add('-translate-x-full');
                        overlay.classList.add('hidden');
                    }
                });
            });

            // --- DESKTOP MINIMIZE LOGIC ---
            function updateSidebarState(isMini) {
                if (isMini) {
                    sidebar.classList.add('sidebar-mini', 'w-20');
                    sidebar.classList.remove('w-64');
                    iconCollapse.classList.add('hidden');
                    iconExpand.classList.remove('hidden');
                } else {
                    sidebar.classList.remove('sidebar-mini', 'w-20');
                    sidebar.classList.add('w-64');
                    iconCollapse.classList.remove('hidden');
                    iconExpand.classList.add('hidden');
                }
            }

            if (btnMinimize) {
                btnMinimize.addEventListener('click', () => {
                    const isCurrentlyExpanded = sidebar.classList.contains('w-64');
                    updateSidebarState(isCurrentlyExpanded);
                    localStorage.setItem('sidebar-state', isCurrentlyExpanded ? 'mini' : 'expanded');
                });
            }

            // Load saved state
            const savedState = localStorage.getItem('sidebar-state');
            if (savedState === 'mini') {
                updateSidebarState(true);
            }

            // Dropdown logout toggle
            const userMenuBtn = document.getElementById('user-menu-btn');
            const userMenu = document.getElementById('user-menu');

            if (userMenuBtn) {
                userMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userMenu.classList.toggle('hidden');
                });
            }

            document.addEventListener('click', function(e) {
                if (!e.target.closest('#user-menu-btn') && !e.target.closest('#user-menu')) {
                    userMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
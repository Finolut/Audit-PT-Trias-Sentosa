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
        
        /* Perbaikan untuk logo agar tidak terlalu kecil di sidebar mini */
        .sidebar-mini .logo-container {
            padding: 0.5rem 0;
        }
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
                        <img src="https://trias-sentosa.com/images/ts.jpg" alt="PT Trias Sentosa" class="h-8 object-contain">
                    </a>
                </div>
            </div>

 <!-- Navigation List -->
<nav class="flex-1 overflow-y-auto py-4 no-scrollbar flex flex-col gap-1 px-3">

    <!-- Dashboard -->
    <a href="{{ route('admin.dashboard') }}"
       class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.dashboard') ? 'active-link' : '' }}">
        <span class="text-xl min-w-[24px] text-center no-margin-on-mini mr-3">📊</span> 
        <span class="hide-on-mini whitespace-nowrap">Landing Page Dashboard</span>
    </a>

    <!-- Status Audit Dept. -->
    <a href="{{ route('admin.dept_status') }}"
       class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.dept_status') ? 'active-link' : '' }}">
        <span class="text-xl min-w-[24px] text-center no-margin-on-mini mr-3">📋</span> 
        <span class="hide-on-mini whitespace-nowrap">Status Departemem</span>
    </a>

    <!-- Manajemen User & Auditor -->
    <a href="{{ route('admin.users.index') }}"
       class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.users.*', 'admin.auditors.*') ? 'active-link' : '' }}">
        <span class="text-xl min-w-[24px] text-center no-margin-on-mini mr-3">👥</span> 
        <span class="hide-on-mini whitespace-nowrap">Manajemen User</span>
    </a>

    <!-- Kelola Soal Audit -->
    <a href="{{ route('admin.items.index') }}"
       class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.items.*') ? 'active-link' : '' }}">
        <span class="text-xl min-w-[24px] text-center no-margin-on-mini mr-3">📝</span> 
        <span class="hide-on-mini whitespace-nowrap">Kelola Pertanyaan Audit</span>
    </a>

    <!-- Cari Laporan -->
    <a href="{{ route('admin.search.report') }}"
       class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.search.report') ? 'active-link' : '' }}">
        <span class="text-xl min-w-[24px] text-center no-margin-on-mini mr-3">🔍</span> 
        <span class="hide-on-mini whitespace-nowrap">Cari Laporan Audit</span>
    </a>

<div class="mt-auto border-t border-gray-100 bg-gray-50/50">
    <div class="p-4">
        <div class="flex items-center group/profile justify-between relative">
            
            <div class="flex items-center min-w-0">
                <div class="w-10 h-10 min-w-[2.5rem] rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-blue-100 ring-2 ring-white">
                    {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                </div>
                
                <div class="ml-3 overflow-hidden transition-all duration-300 max-w-[150px] group-data-[collapsed=true]:max-w-0 group-data-[collapsed=true]:ml-0 group-data-[collapsed=true]:opacity-0">
                    <p class="text-sm font-bold text-gray-800 truncate capitalize leading-tight">
                        {{ auth()->user()->name ?? 'Administrator' }}
                    </p>
                    <p class="text-[11px] font-medium text-blue-500 uppercase tracking-wider">
                        {{ auth()->user()->role ?? 'Admin' }}
                    </p>
                </div>
            </div>

            <button id="logout-btn" 
                    type="button"
                    title="Logout"
                    class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200 
                           group-data-[collapsed=true]:hidden"> <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
            
        </div>
    </div>
</div>

<!-- Minimize Button (Desktop Only) -->
<div class="pt-4 border-t border-gray-100 hidden lg:block">
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
        <span class="lg:hidden whitespace-nowrap">Minimize Menu</span>
    </button>
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

    <!-- Logout Confirmation Modal -->
    <div id="logout-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg p-6 w-96 max-w-full mx-4 transform transition-all duration-300 scale-95 opacity-0 modal-content">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 p-3 rounded-full mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Konfirmasi Logout</h3>
            </div>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar dari sistem? Semua sesi Anda akan diakhiri.</p>
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancel-logout" class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium rounded-lg transition-colors">
                    Batal
                </button>
                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
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
            
            // Logout functionality with confirmation modal
            const logoutBtn = document.getElementById('logout-btn');
            const logoutBtnMini = document.getElementById('logout-btn-mini');
            const logoutModal = document.getElementById('logout-modal');
            const cancelLogout = document.getElementById('cancel-logout');
            const modalContent = document.querySelector('.modal-content');
            
            function showLogoutModal() {
                logoutModal.classList.remove('hidden');
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            }
            
            function hideLogoutModal() {
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    logoutModal.classList.add('hidden');
                }, 300);
            }
            
            if (logoutBtn) {
                logoutBtn.addEventListener('click', showLogoutModal);
            }
            
            if (logoutBtnMini) {
                logoutBtnMini.addEventListener('click', showLogoutModal);
            }
            
            if (cancelLogout) {
                cancelLogout.addEventListener('click', hideLogoutModal);
            }
            
            if (logoutModal) {
                logoutModal.addEventListener('click', function(e) {
                    if (e.target === logoutModal) {
                        hideLogoutModal();
                    }
                });
                
                // Close modal with ESC key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !logoutModal.classList.contains('hidden')) {
                        hideLogoutModal();
                    }
                });
            }
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Audit Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .active-link { 
            background-color: #eff6ff; 
            color: #1d4ed8; 
            font-weight: bold; 
            border-right: 3px solid #2563eb; 
        }

        /* Sidebar Transitions */
        .sidebar {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
        
        /* Responsive adjustments */
        @media (max-width: 1023px) {
            .sidebar-mobile {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar-mobile.open {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- Mobile Overlay -->
    <div id="overlay" class="overlay fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR -->
        <div id="sidebar" class="sidebar sidebar-mobile fixed lg:static inset-y-0 left-0 z-50 flex flex-col bg-white border-r border-gray-200 h-full w-64 lg:translate-x-0">
            
            <!-- Header Sidebar -->
            <div class="h-16 flex items-center justify-center border-b border-gray-100 px-4">
                <div class="logo-container w-full">
                    <div class="logo-expanded text-left">
                        <a href="https://trias-sentosa.com" class="block">
                            <img src="https://trias-sentosa.com/images/logo.webp" alt="PT Trias Sentosa" class="h-8 object-contain">
                        </a>
                    </div>
                    <div class="logo-collapsed text-center hidden">
                        <a href="https://trias-sentosa.com" class="block">
                            <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center text-white font-bold text-sm">
                                TS
                            </div>
                        </a>
                    </div>
                </div>
                <button id="sidebar-close" class="lg:hidden absolute right-4 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Navigation List -->
            <nav class="flex-1 overflow-y-auto py-4 no-scrollbar flex flex-col gap-1 px-3">
                <!-- Dashboard -->
                <a href="#" class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors active-link">
                    <span class="text-xl min-w-[24px] text-center no-margin-on-mini mr-3">📊</span> 
                    <span class="hide-on-mini whitespace-nowrap">Landing Page Dashboard</span>
                </a>

                <!-- Status Audit Dept. -->
                <a href="#" class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors">
                    <span class="text-xl min-w-[24px] text-center no-margin-on-mini mr-3">📋</span> 
                    <span class="hide-on-mini whitespace-nowrap">Status Departemen</span>
                </a>

                <!-- Manajemen User & Auditor -->
                <a href="#" class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors">
                    <span class="text-xl min-w-[24px] text-center no-margin-on-mini mr-3">👥</span> 
                    <span class="hide-on-mini whitespace-nowrap">Manajemen User</span>
                </a>

                <!-- Kelola Soal Audit -->
                <a href="#" class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors">
                    <span class="text-xl min-w-[24px] text-center no-margin-on-mini mr-3">📝</span> 
                    <span class="hide-on-mini whitespace-nowrap">Kelola Pertanyaan Audit</span>
                </a>

                <!-- Cari Laporan -->
                <a href="#" class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors">
                    <span class="text-xl min-w-[24px] text-center no-margin-on-mini mr-3">🔍</span> 
                    <span class="hide-on-mini whitespace-nowrap">Cari Laporan Audit</span>
                </a>
            </nav>

            <!-- User Profile Footer -->
            <div class="border-t border-gray-100 pt-3 pb-4 px-3">
                <div class="block group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center w-full">
                            <div class="w-9 h-9 min-w-[2.25rem] rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                AD
                            </div>
                            <div class="ml-3 overflow-hidden">
                                <p class="text-sm font-bold text-gray-700 truncate capitalize">
                                    Administrator
                                </p>
                                <p class="text-[10px] font-medium text-blue-600 uppercase tracking-tight">
                                    Admin
                                </p>
                            </div>
                        </div>
                        
                        <button id="dropdown-menu" class="p-1.5 text-gray-400 hover:text-blue-600 rounded-full hover:bg-gray-100 transition-colors">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                    </div>
                    
                    <!-- Dropdown menu -->
                    <div id="profile-dropdown" class="hidden mt-2 py-1 bg-white rounded-lg shadow-md border border-gray-100 absolute z-50 w-48 right-4">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900">
                            <i class="fas fa-user mr-2 text-blue-500"></i> Profil Saya
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900">
                            <i class="fas fa-cog mr-2 text-blue-500"></i> Pengaturan
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <button id="logout-btn" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600">
                            <i class="fas fa-sign-out-alt mr-2 text-red-500"></i> Logout
                        </button>
                    </div>
                </div>
            </div>

            <!-- Minimize Button (Desktop Only) -->
            <div class="hidden lg:block border-t border-gray-100 pt-3 pb-4 px-3">
                <button id="btn-minimize" class="w-full flex items-center px-3 py-2 text-sm font-medium text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors focus:outline-none">
                    <div id="icon-collapse" class="min-w-[24px] mr-3 no-margin-on-mini flex justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </div>
                    <div id="icon-expand" class="min-w-[24px] mr-3 no-margin-on-mini hidden justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    </div>
                    <span class="hide-on-mini whitespace-nowrap">Minimize Menu</span>
                </button>
            </div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300">
            <!-- Top Bar for Mobile -->
            <header class="lg:hidden p-4 bg-white border-b border-gray-200 flex items-center shadow-sm">
                <button id="menu-toggle" class="p-2 mr-3 text-gray-600 hover:text-gray-900 rounded-md hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h2 class="text-lg font-bold text-gray-800">Dashboard Admin</h2>
            </header>

            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h1 class="text-2xl font-bold text-gray-800 mb-4">Selamat Datang di Admin Dashboard</h1>
                    <p class="text-gray-600 mb-6">Sistem Audit Internal PT Trias Sentosa</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                            <h3 class="text-gray-500 text-sm font-medium mb-1">Total Audit</h3>
                            <p class="text-2xl font-bold text-blue-700">24</p>
                        </div>
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                            <h3 class="text-gray-500 text-sm font-medium mb-1">Selesai</h3>
                            <p class="text-2xl font-bold text-green-700">18</p>
                        </div>
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                            <h3 class="text-gray-500 text-sm font-medium mb-1">Dalam Proses</h3>
                            <p class="text-2xl font-bold text-yellow-700">4</p>
                        </div>
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                            <h3 class="text-gray-500 text-sm font-medium mb-1">Tertunda</h3>
                            <p class="text-2xl font-bold text-red-700">2</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logout-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg p-6 w-96 max-w-full mx-4 transform transition-all duration-300">
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
                <button type="button" id="cancel-logout" class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium rounded-lg transition-colors border border-gray-300 hover:border-gray-400">
                    Batal
                </button>
                <button type="button" id="confirm-logout" class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                    Logout
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const menuToggle = document.getElementById('menu-toggle');
            const sidebarClose = document.getElementById('sidebar-close');
            const btnMinimize = document.getElementById('btn-minimize');
            const iconCollapse = document.getElementById('icon-collapse');
            const iconExpand = document.getElementById('icon-expand');
            const dropdownMenu = document.getElementById('dropdown-menu');
            const profileDropdown = document.getElementById('profile-dropdown');
            const logoutBtn = document.getElementById('logout-btn');
            const logoutModal = document.getElementById('logout-modal');
            const cancelLogout = document.getElementById('cancel-logout');
            const confirmLogout = document.getElementById('confirm-logout');

            // --- MOBILE SIDEBAR LOGIC ---
            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            if (menuToggle) {
                menuToggle.addEventListener('click', openSidebar);
            }

            if (sidebarClose) {
                sidebarClose.addEventListener('click', closeSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            // Close sidebar on mobile when clicking nav link
            const navLinks = document.querySelectorAll('#sidebar a');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        closeSidebar();
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
                localStorage.setItem('sidebar-state', isMini ? 'mini' : 'expanded');
            }

            if (btnMinimize) {
                btnMinimize.addEventListener('click', () => {
                    const isCurrentlyExpanded = sidebar.classList.contains('w-64');
                    updateSidebarState(isCurrentlyExpanded);
                });
            }

            // Load saved state
            const savedState = localStorage.getItem('sidebar-state');
            if (savedState === 'mini') {
                updateSidebarState(true);
            }

            // --- PROFILE DROPDOWN LOGIC ---
            if (dropdownMenu && profileDropdown) {
                dropdownMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isVisible = !profileDropdown.classList.contains('hidden');
                    document.querySelectorAll('.profile-dropdown').forEach(el => {
                        el.classList.add('hidden');
                    });
                    if (!isVisible) {
                        profileDropdown.classList.remove('hidden');
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!dropdownMenu.contains(e.target) && !profileDropdown.contains(e.target)) {
                        profileDropdown.classList.add('hidden');
                    }
                });
            }

            // --- LOGOUT MODAL LOGIC ---
            function showLogoutModal() {
                logoutModal.classList.remove('hidden');
                profileDropdown.classList.add('hidden');
            }
            
            function hideLogoutModal() {
                logoutModal.classList.add('hidden');
            }
            
            if (logoutBtn) {
                logoutBtn.addEventListener('click', showLogoutModal);
            }
            
            if (cancelLogout) {
                cancelLogout.addEventListener('click', hideLogoutModal);
            }
            
            if (confirmLogout) {
                confirmLogout.addEventListener('click', function() {
                    // In real application, this would submit the logout form
                    console.log('Logging out...');
                    window.location.href = '/'; // Redirect to home page after logout
                });
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

            // --- CLOSE SIDEBAR ON SCREEN RESIZE ---
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth >= 1024) {
                        closeSidebar();
                    }
                }, 250);
            });
        });
    </script>
</body>
</html>
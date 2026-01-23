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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 16rem;
        }
        .sidebar-collapsed {
            width: 5rem !important;
        }
        .sidebar-collapsed .sidebar-header,
        .sidebar-collapsed .nav-text,
        .sidebar-collapsed .dept-label,
        .sidebar-collapsed .user-info,
        .sidebar-collapsed .minimize-btn-text {
            display: none;
        }
        .sidebar-collapsed .minimize-btn-icon {
            display: block !important;
        }
        .sidebar-collapsed .nav-icon {
            margin-right: 0 !important;
        }
        .overlay {
            transition: opacity 0.3s ease;
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- Mobile Overlay -->
    <div id="overlay" class="overlay fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR -->
        <div id="sidebar" class="sidebar fixed lg:static bg-white border-r border-gray-200 flex flex-col z-50 h-full lg:h-auto sidebar-expanded">
            <!-- Header Sidebar -->
            <div class="sidebar-header p-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-extrabold text-blue-800 uppercase leading-none tracking-tight">PT Trias Sentosa</h1>
                    <p class="text-[10px] font-semibold text-gray-400 mt-1.5 uppercase tracking-wider">Audit System Admin</p>
                </div>
                <button id="minimize-btn" class="p-2 text-gray-400 hover:text-blue-600 rounded-lg transition-all duration-200 lg:block hidden">
                    <span class="minimize-btn-icon hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </span>
                    <span class="minimize-btn-text">≡</span>
                </button>
            </div>

            <!-- Navigation List -->
            <nav class="p-3 space-y-1 overflow-y-auto flex-1">
                <div class="px-3 mb-2 mt-2 dept-label">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Menu Utama</span>
                </div>

                <a href="#"
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors active-link">
                    <span class="nav-icon mr-3 text-lg">📊</span>
                    <span class="nav-text">Dashboard Overview</span>
                </a>

                <a href="#"
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors">
                    <span class="nav-icon mr-3 text-lg">📋</span>
                    <span class="nav-text">Status Audit Dept.</span>
                </a>

                <!-- Manajemen Data -->
                <div class="px-3 mb-2 mt-6 dept-label">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Manajemen Data</span>
                </div>

                <a href="#"
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors">
                    <span class="nav-icon mr-3 text-lg">👥</span>
                    <span class="nav-text">Manajemen User</span>
                </a>

                <a href="#"
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors">
                    <span class="nav-icon mr-3 text-lg">📝</span>
                    <span class="nav-text">Kelola Soal Audit</span>
                </a>

                <!-- Cari Laporan -->
                <a href="#"
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors">
                    <span class="nav-icon mr-3 text-lg">🔍</span>
                    <span class="nav-text">Cari Laporan</span>
                </a>
            </nav>

            <!-- User Profile Footer -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center justify-between group">
                    <div class="flex items-center">
                        <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                            TS
                        </div>
                        <div class="ml-3 overflow-hidden user-info">
                            <p class="text-sm font-bold text-gray-700 truncate capitalize">
                                Admin Trias
                            </p>
                            <p class="text-[10px] font-medium text-blue-600 uppercase tracking-tight">
                                Administrator
                            </p>
                        </div>
                    </div>

                    <button id="logout-btn"
                            title="Logout"
                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
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
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Dashboard Overview</h2>
                    <p class="text-gray-600">Welcome to the Admin Audit Dashboard. Use the sidebar to navigate through different sections.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 uppercase text-sm font-semibold">Total Auditors</p>
                                <p class="text-3xl font-bold mt-1">28</p>
                            </div>
                            <div class="bg-blue-700 w-12 h-12 rounded-lg flex items-center justify-center">
                                👥
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 uppercase text-sm font-semibold">Active Audits</p>
                                <p class="text-3xl font-bold mt-1">42</p>
                            </div>
                            <div class="bg-green-700 w-12 h-12 rounded-lg flex items-center justify-center">
                                ✅
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 uppercase text-sm font-semibold">Pending Reports</p>
                                <p class="text-3xl font-bold mt-1">17</p>
                            </div>
                            <div class="bg-purple-700 w-12 h-12 rounded-lg flex items-center justify-center">
                                📋
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const minimizeBtn = document.getElementById('minimize-btn');
            const overlay = document.getElementById('overlay');
            const menuToggle = document.getElementById('menu-toggle');
            const logoutBtn = document.getElementById('logout-btn');
            
            // Check for saved sidebar state
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (isCollapsed && window.innerWidth >= 1024) {
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                if (minimizeBtn) {
                    minimizeBtn.querySelector('.minimize-btn-text').textContent = '≡';
                }
            }
            
            // Toggle sidebar collapse state
            if (minimizeBtn) {
                minimizeBtn.addEventListener('click', () => {
                    if (sidebar.classList.contains('sidebar-collapsed')) {
                        sidebar.classList.remove('sidebar-collapsed');
                        sidebar.classList.add('sidebar-expanded');
                        localStorage.setItem('sidebar-collapsed', 'false');
                        minimizeBtn.querySelector('.minimize-btn-text').textContent = '≡';
                    } else {
                        sidebar.classList.remove('sidebar-expanded');
                        sidebar.classList.add('sidebar-collapsed');
                        localStorage.setItem('sidebar-collapsed', 'true');
                        minimizeBtn.querySelector('.minimize-btn-text').textContent = '☰';
                    }
                });
            }
            
            // Toggle sidebar on mobile
            if (menuToggle) {
                menuToggle.addEventListener('click', () => {
                    sidebar.classList.remove('sidebar-collapsed');
                    overlay.classList.remove('hidden');
                });
            }
            
            // Logout simulation
            if (logoutBtn) {
                logoutBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    if(confirm('Are you sure you want to logout?')) {
                        alert('Logout successful!');
                    }
                });
            }
            
            // Close sidebar when clicking overlay
            if (overlay) {
                overlay.addEventListener('click', () => {
                    sidebar.classList.add('sidebar-collapsed');
                    overlay.classList.add('hidden');
                });
            }
            
            // Close sidebar when clicking navigation links on mobile
            const navLinks = document.querySelectorAll('#sidebar a');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        sidebar.classList.add('sidebar-collapsed');
                        overlay.classList.add('hidden');
                    }
                });
            });
            
            // Handle window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    overlay.classList.add('hidden');
                    sidebar.classList.remove('sidebar-collapsed');
                }
            });
        });
    </script>
</body>
</html>
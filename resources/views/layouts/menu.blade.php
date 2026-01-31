<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/ts.jpg') }}" type="image/jpeg">
    <title>{{ $deptName ?? 'Auditor Dashboard' }} - Sesi Audit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .active-link { 
            background-color: #eff6ff; 
            color: #1d4ed8; 
            font-weight: bold; 
            border-right: 3px solid #2563eb; 
        }
        .sidebar { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .overlay { transition: opacity 0.3s ease; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .sidebar-mini .hide-on-mini { display: none !important; }
        .sidebar-mini .justify-center-on-mini { justify-content: center !important; }
        .sidebar-mini .no-margin-on-mini { margin-right: 0 !important; }
        .logo-expanded { display: block; }
        .logo-collapsed { display: none; }
        .sidebar-mini .logo-expanded { display: none; }
        .sidebar-mini .logo-collapsed { display: block; }
        .sidebar-mini .logo-container { padding: 0.5rem 0; }
        
        /* Department-specific styling */
        .department-item { 
            position: relative; 
            transition: all 0.3s ease;
            margin-bottom: 0.25rem;
        }
        .department-item.expanded { 
            background-color: #f8fafc;
            border-radius: 0.75rem;
        }
        .department-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            width: 100%;
            padding: 0.75rem 0.75rem;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
        }
        .department-toggle:hover {
            background-color: #f1f5f9;
        }
        .department-toggle.active {
            background-color: #e0f2fe;
            color: #0c4a6e;
            font-weight: 600;
        }
        .department-icon {
            min-width: 24px;
            text-align: center;
            margin-right: 0.75rem;
            color: #38bdf8;
        }
        .department-name {
            flex: 1;
            text-align: left;
            font-weight: 600;
            font-size: 0.87rem;
        }
        .department-status {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
        }
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.75rem;
        }
        .status-active {
            background: #dbeafe;
            color: #1d4ed8;
        }
        .status-pending {
            background: #f0f9ff;
            color: #0ea5e9;
        }
        .status-completed {
            background: #dcfce7;
            color: #15803d;
        }
        
        /* Clause-specific styling */
        .clause-link { 
            position: relative; 
            overflow: hidden;
            padding-left: 3.5rem !important;
            margin-left: 1.5rem;
            border-left: 2px solid #e5e7eb;
            transition: all 0.2s ease;
        }
        .clause-link:hover {
            background-color: #f8fafc;
            border-left-color: #3b82f6;
        }
        .clause-link::after {
            content: '';
            position: absolute;
            right: 0; top: 0; bottom: 0;
            width: 4px;
            background: linear-gradient(to bottom, #1e40af, #1d4ed8, #1e40af);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .clause-link.active-link::after { 
            opacity: 1; 
        }
        .clause-badge {
            min-width: 20px;
            height: 20px;
            border-radius: 50%;
            font-size: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
            margin-right: 0.5rem;
        }
        .clause-badge.in-progress { background: #dbeafe; color: #1d4ed8; }
        .clause-badge.completed { background: #dcfce7; color: #15803d; }
        
        /* Nested clause visibility */
        .clauses-container {
            overflow: hidden;
            transition: max-height 0.3s ease, opacity 0.3s ease;
            padding-left: 1rem;
        }
        .clauses-container.hidden {
            max-height: 0;
            opacity: 0;
        }
        .clauses-container.visible {
            max-height: 800px;
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <div id="overlay" class="overlay fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR - AUDITOR VERSION -->
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

            <!-- Navigation: Auditor Menu + Departments with Clauses -->
            <nav class="flex-1 overflow-y-auto py-4 no-scrollbar flex flex-col gap-1 px-3">
                <!-- Dashboard Link -->
                <a href="{{ route('audit.menu', ['id' => $auditId ?? 1]) }}"
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('audit.menu') ? 'active-link' : '' }}">
                    <span class="text-xl min-w-[24px] text-center no-margin-on-mini mr-3">📊</span> 
                    <span class="hide-on-mini whitespace-nowrap">Dashboard Audit</span>
                </a>

                <!-- Department Links with Expandable Clauses -->
                @php
                    // Gunakan data relatedAudits dari controller
                    $departments = $relatedAudits ?? [];
                    $currentDeptId = $activeDepartmentId ?? null;
                    $currentAuditId = $auditId ?? null;
                    $currentClause = request()->route('clause');
                    
                    // Hitung progress per departemen
                    function getDeptProgress($auditId, $deptId) {
                        $mainClauses = [4,5,6,7,8,9,10];
                        $completed = 0;
                        foreach ($mainClauses as $mainCode) {
                            $subCodes = [];
                            switch($mainCode) {
                                case 4: $subCodes = ['4.1', '4.2', '4.3', '4.4']; break;
                                case 5: $subCodes = ['5.1', '5.2', '5.3']; break;
                                case 6: $subCodes = ['6.1.1', '6.1.2', '6.1.3', '6.1.4', '6.2.1', '6.2.2']; break;
                                case 7: $subCodes = ['7.1', '7.2', '7.3', '7.4', '7.5.1', '7.5.2', '7.5.3']; break;
                                case 8: $subCodes = ['8.1', '8.2']; break;
                                case 9: $subCodes = ['9.1.1', '9.1.2', '9.2.1 & 9.2.2', '9.3']; break;
                                case 10: $subCodes = ['10.1', '10.2', '10.3']; break;
                            }
                            
                            $totalItems = \DB::table('items')
                                ->join('clauses', 'items.clause_id', '=', 'clauses.id')
                                ->whereIn('clauses.clause_code', $subCodes)
                                ->count();
                            
                            $answeredItems = \DB::table('answers')
                                ->join('items', 'answers.item_id', '=', 'items.id')
                                ->join('clauses', 'items.clause_id', '=', 'clauses.id')
                                ->where('answers.audit_id', $auditId)
                                ->where('answers.department_id', $deptId)
                                ->whereIn('clauses.clause_code', $subCodes)
                                ->count();
                            
                            if ($answeredItems >= $totalItems && $totalItems > 0) {
                                $completed++;
                            }
                        }
                        return [
                            'percentage' => count($mainClauses) > 0 ? round(($completed / count($mainClauses)) * 100) : 0,
                            'completed' => $completed,
                            'total' => count($mainClauses)
                        ];
                    }
                    
                    function getClauseProgress($auditId, $deptId, $mainCode) {
                        $subCodes = [];
                        switch($mainCode) {
                            case 4: $subCodes = ['4.1', '4.2', '4.3', '4.4']; break;
                            case 5: $subCodes = ['5.1', '5.2', '5.3']; break;
                            case 6: $subCodes = ['6.1.1', '6.1.2', '6.1.3', '6.1.4', '6.2.1', '6.2.2']; break;
                            case 7: $subCodes = ['7.1', '7.2', '7.3', '7.4', '7.5.1', '7.5.2', '7.5.3']; break;
                            case 8: $subCodes = ['8.1', '8.2']; break;
                            case 9: $subCodes = ['9.1.1', '9.1.2', '9.2.1 & 9.2.2', '9.3']; break;
                            case 10: $subCodes = ['10.1', '10.2', '10.3']; break;
                        }
                        
                        $totalItems = \DB::table('items')
                            ->join('clauses', 'items.clause_id', '=', 'clauses.id')
                            ->whereIn('clauses.clause_code', $subCodes)
                            ->count();
                        
                        $answeredItems = \DB::table('answers')
                            ->join('items', 'answers.item_id', '=', 'items.id')
                            ->join('clauses', 'items.clause_id', '=', 'clauses.id')
                            ->where('answers.audit_id', $auditId)
                            ->where('answers.department_id', $deptId)
                            ->whereIn('clauses.clause_code', $subCodes)
                            ->count();
                        
                        $percentage = ($totalItems > 0) ? round(($answeredItems / $totalItems) * 100) : 0;
                        
                        return [
                            'percentage' => $percentage,
                            'count' => $answeredItems,
                            'total' => $totalItems,
                            'completed' => $percentage >= 100
                        ];
                    }
                @endphp

                @foreach($departments as $dept)
                    @php
                        $deptProgress = getDeptProgress($dept['id'], $dept['dept_id']);
                        $isDeptActive = $dept['dept_id'] == $currentDeptId && $dept['id'] == $currentAuditId;
                        $status = $deptProgress['percentage'] == 100 ? 'completed' : ($deptProgress['completed'] > 0 ? 'active' : 'pending');
                        $statusClass = $status === 'completed' ? 'status-completed' : ($status === 'active' ? 'status-active' : 'status-pending');
                    @endphp
                    
                    <div class="department-item {{ $isDeptActive ? 'expanded' : '' }}" data-dept-id="{{ $dept['dept_id'] }}">
                        <div class="department-toggle {{ $isDeptActive ? 'active' : '' }}">
                            <div class="flex items-center w-full">
                                <span class="department-icon">
<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1" />
</svg>
                                </span>
                                <div class="department-content flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="department-name hide-on-mini">{{ $dept['dept_name'] }}</span>
                                    </div>
                                    <div class="department-status mt-1">
                                        <span class="status-badge {{ $statusClass }} hide-on-mini">{{ $statusText }}</span>
                                        <span class="text-xs text-gray-500 hide-on-mini">{{ $statusDesc }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="clauses-container {{ $isDeptActive ? 'visible' : 'hidden' }}" id="clauses-{{ $dept['dept_id'] }}">
                            @php
                                $clauses = [4,5,6,7,8,9,10];
                            @endphp
                            
                            @foreach($clauses as $clauseNum)
                                @php
                                    $clauseProgressData = getClauseProgress($dept['id'], $dept['dept_id'], $clauseNum);
                                    $isCurrent = $currentClause == $clauseNum && $isDeptActive;
                                    $isCompleted = $clauseProgressData['completed'];
                                    $badgeClass = $isCompleted ? 'completed' : ($clauseProgressData['count'] > 0 ? 'in-progress' : '');
                                @endphp
                                <a href="{{ route('audit.show', ['id' => $dept['id'], 'clause' => $clauseNum]) }}"
                                   class="clause-link flex items-center px-3 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ $isCurrent ? 'active-link' : '' }}">
                                    <span class="text-lg min-w-[20px] text-center mr-2">
                                        @if($isCompleted) ✅ @else 📋 @endif
                                    </span> 
                                    <span class="hide-on-mini whitespace-nowrap mr-2">Klausul {{ $clauseNum }}</span>
                                    @if($clauseProgressData['total'] > 0)
                                        <span class="clause-badge {{ $badgeClass }} hide-on-mini">
                                            {{ $clauseProgressData['count'] }}/{{ $clauseProgressData['total'] }}
                                        </span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- User Profile Section (Bottom) -->
                <div class="mt-auto pt-4 border-t border-gray-100">
                    <div class="px-4 py-2"> 
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0">
                                <div class="w-9 h-9 min-w-[2.25rem] rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'AU', 0, 2)) }}
                                </div>
                                <div class="ml-3 overflow-hidden hide-on-mini">
                                    <p class="text-sm font-bold text-gray-700 truncate capitalize leading-tight">
                                        {{ auth()->user()->name ?? 'Auditor' }}
                                    </p>
                                    <p class="text-[10px] font-medium text-blue-600 uppercase tracking-tight">
                                        {{ auth()->user()->role ?? 'Auditor' }}
                                    </p>
                                </div>
                            </div>
                            <button id="logout-btn" type="button"
                                    title="Logout"
                                    class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200 hide-on-mini">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Minimize Button (Desktop Only) -->
                <div class="pt-4 border-t border-gray-100 hidden lg:block">
                    <button id="btn-minimize" class="w-full flex items-center px-3 py-2.5 text-sm font-medium text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all focus:outline-none">
                        <div id="icon-collapse" class="min-w-[24px] mr-3 flex justify-center no-margin-on-mini">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                            </svg>
                        </div>
                        <div id="icon-expand" class="min-w-[24px] mr-3 hidden justify-center no-margin-on-mini">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                            </svg>
                        </div>
                        <span class="whitespace-nowrap transition-opacity duration-200 hide-on-mini">
                            Kecilkan Side Bar
                        </span>
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
                <div>
                    <h2 class="text-lg font-bold text-gray-800">{{ $deptName ?? 'Sesi Audit' }}</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Auditor: {{ auth()->user()->name ?? 'Nama Auditor' }}</p>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 lg:p-6 pb-24 lg:pb-20">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Logout Confirmation Modal (Same as Admin) -->
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Department toggle functionality
            const departmentItems = document.querySelectorAll('.department-item');
            departmentItems.forEach(item => {
                const toggleBtn = item.querySelector('.department-toggle');
                const clausesContainer = item.querySelector('.clauses-container');
                
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Toggle current department
                    const isExpanded = item.classList.contains('expanded');
                    
                    // Close all other departments
                    departmentItems.forEach(otherItem => {
                        if (otherItem !== item) {
                            otherItem.classList.remove('expanded');
                            otherItem.querySelector('.clauses-container').classList.remove('visible');
                            otherItem.querySelector('.clauses-container').classList.add('hidden');
                            otherItem.querySelector('.department-toggle').classList.remove('active');
                        }
                    });
                    
                    // Toggle current department
                    if (isExpanded) {
                        item.classList.remove('expanded');
                        clausesContainer.classList.remove('visible');
                        clausesContainer.classList.add('hidden');
                        toggleBtn.classList.remove('active');
                    } else {
                        item.classList.add('expanded');
                        clausesContainer.classList.remove('hidden');
                        clausesContainer.classList.add('visible');
                        toggleBtn.classList.add('active');
                    }
                });
            });

            // Sidebar & Mobile Menu Logic
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const menuToggle = document.getElementById('menu-toggle');
            const btnMinimize = document.getElementById('btn-minimize');
            const iconCollapse = document.getElementById('icon-collapse');
            const iconExpand = document.getElementById('icon-expand');
            const logoutBtn = document.getElementById('logout-btn');
            const logoutModal = document.getElementById('logout-modal');
            const cancelLogout = document.getElementById('cancel-logout');
            const modalContent = document.querySelector('.modal-content');

            // Mobile menu toggle
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

            // Close sidebar on mobile link click
            document.querySelectorAll('#sidebar a').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        sidebar.classList.add('-translate-x-full');
                        overlay.classList.add('hidden');
                    }
                });
            });

            // Sidebar minimize/expand
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
                    const isExpanded = sidebar.classList.contains('w-64');
                    updateSidebarState(isExpanded);
                    localStorage.setItem('sidebar-state', isExpanded ? 'mini' : 'expanded');
                });
            }

            // Restore saved sidebar state
            if (localStorage.getItem('sidebar-state') === 'mini') {
                updateSidebarState(true);
            }

            // Logout modal handlers
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
                setTimeout(() => logoutModal.classList.add('hidden'), 300);
            }

            if (logoutBtn) logoutBtn.addEventListener('click', showLogoutModal);
            if (cancelLogout) cancelLogout.addEventListener('click', hideLogoutModal);
            
            if (logoutModal) {
                logoutModal.addEventListener('click', e => {
                    if (e.target === logoutModal) hideLogoutModal();
                });
                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape' && !logoutModal.classList.contains('hidden')) {
                        hideLogoutModal();
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
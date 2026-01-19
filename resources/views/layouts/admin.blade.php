<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Audit Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .active-link { background-color: #eff6ff; color: #1d4ed8; font-weight: bold; border-right: 3px solid #2563eb; }
        .dept-item { transition: all 0.2s ease; }
        
        /* Custom Scrollbar for sidebar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <div class="flex h-screen overflow-hidden">
        {{-- SIDEBAR --}}
        <div class="w-64 bg-white border-r border-gray-200 shrink-0 flex flex-col z-20">
            {{-- Header Sidebar --}}
            <div class="p-6 border-b border-gray-100">
                <h1 class="text-xl font-extrabold text-blue-800 uppercase leading-none tracking-tight">PT Trias Sentosa</h1>
                <p class="text-[10px] font-semibold text-gray-400 mt-1.5 uppercase tracking-wider">Audit System Admin</p>
            </div>

            {{-- Search Bar (Unified) --}}
            <div class="p-4 border-b border-gray-100 bg-gray-50/30">
                <div class="relative">
                    <input type="text" 
                           id="sidebarSearch" 
                           placeholder="Cari departemen..." 
                           class="w-full pl-9 pr-3 py-2 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400 transition-all shadow-sm">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            
            {{-- Navigation List --}}
            <nav class="p-3 space-y-1 overflow-y-auto flex-1" id="navContainer">
                <div class="px-3 mb-2 mt-2">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Menu Utama</span>
                </div>

                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.dashboard') ? 'active-link' : '' }}">
                   <span class="mr-3 text-lg">📊</span> Dashboard Overview
                </a>

                <a href="{{ route('admin.dept.status_index') }}" 
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.dept.status_index') ? 'active-link' : '' }}">
                   <span class="mr-3 text-lg">📋</span> Status Audit Dept.
                </a>


                <!-- Manajemen Data -->
<div class="px-3 mb-2 mt-6">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Manajemen Data</span>
                </div>

                {{-- MENU BARU: KELOLA USER --}}
                <a href="{{ route('admin.users.create') }}" 
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.users.*') ? 'active-link' : '' }}">
                    <span class="mr-3 text-lg">👥</span> Kelola User (Auditor)
                </a>
                {{-- END MENU BARU --}}

                {{-- MENU BARU: MONITORING AUDITOR --}}
<a href="{{ route('admin.auditors.index') }}" 
   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.auditors.*') ? 'active-link' : '' }}">
    <span class="mr-3 text-lg">🕵️‍♂️</span> Monitoring Auditor
</a>
{{-- END MENU BARU --}}

                <a href="{{ route('admin.items.index') }}" 
                   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.items.*') ? 'active-link' : '' }}">
                    <span class="mr-3 text-lg">📝</span> Kelola Soal Audit
                </a>
            </nav>
            
            {{-- User Profile Footer --}}
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                        AD
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-bold text-gray-700">Administrator</p>
                        <p class="text-xs text-gray-500">PT Trias Sentosa</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT AREA --}}
        <div class="flex-1 overflow-y-auto bg-gray-50 relative">
             <main class="p-8 pb-20">
                @yield('content')
             </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('sidebarSearch');
            const deptDropdown = document.getElementById('deptDropdown');
            const deptItems = document.querySelectorAll('.dept-item');
            const deptToggle = document.getElementById('deptToggle');
            const deptChevron = document.getElementById('deptChevron');
            const noResult = document.getElementById('noSidebarResult');
            
            deptToggle.addEventListener('click', function() {
                const isHidden = deptDropdown.classList.contains('hidden');
                if (isHidden) {
                    deptDropdown.classList.remove('hidden');
                    deptChevron.classList.add('rotate-180');
                } else {
                    deptDropdown.classList.add('hidden');
                    deptChevron.classList.remove('rotate-180');
                }
            });

            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase().trim();
                let foundCount = 0;

                if (term.length > 0) {
                    deptDropdown.classList.remove('hidden');
                    deptChevron.classList.add('rotate-180');
                } else {
                    deptDropdown.classList.add('hidden');
                    deptChevron.classList.remove('rotate-180');
                    deptItems.forEach(item => item.style.display = 'block');
                    noResult.classList.add('hidden');
                    return; 
                }

                deptItems.forEach(item => {
                    const deptName = item.getAttribute('data-name');
                    if (deptName.includes(term)) {
                        item.style.display = 'block';
                        foundCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                if (foundCount === 0) {
                    noResult.classList.remove('hidden');
                } else {
                    noResult.classList.add('hidden');
                }
            });
            
            if (document.querySelector('.dept-item.active-link')) {
                deptDropdown.classList.remove('hidden');
                deptChevron.classList.add('rotate-180');
            }
        });
    </script>

</body>
</html>
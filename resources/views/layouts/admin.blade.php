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
        <div class="w-64 bg-white border-r border-gray-200 flex flex-col z-20"> <!-- HAPUS shrink-0 -->
            {{-- Header Sidebar --}}
            <div class="p-6 border-b border-gray-100">
                <h1 class="text-xl font-extrabold text-blue-800 uppercase leading-none tracking-tight">PT Trias Sentosa</h1>
                <p class="text-[10px] font-semibold text-gray-400 mt-1.5 uppercase tracking-wider">Audit System Admin</p>
            </div>

            <div class="p-4 bg-blue-50 border-b border-blue-100">
                <label class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-2 block">
                    Cari Laporan Cepat
                </label>
                <form action="{{ route('admin.audit.search') }}" method="GET" class="relative">
                    @if(session('search_error'))
                        <div class="mb-3 px-3 py-2 rounded-md bg-red-50 border border-red-200 text-red-700 text-xs font-semibold flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                            </svg>
                            {{ session('search_error') }}
                        </div>
                    @endif
                    {{-- Ganti bagian input di sidebar --}}
                    <input type="text"
                           name="audit_id"
                           placeholder="Tempel ID Laporan (UUID)..."
                           class="w-full pl-3 pr-10 py-2 text-sm bg-white border border-blue-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm placeholder-gray-400"
                           required>
                    <button type="submit" class="absolute right-1 top-1 bottom-1 bg-blue-600 hover:bg-blue-700 text-white rounded px-2 flex items-center justify-center transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>
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

<a href="{{ route('admin.dept_status') }}"
   class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50 group transition-colors {{ request()->routeIs('admin.dept_status') ? 'active-link' : '' }}">
    <span class="mr-3 text-lg">📋</span> Status Audit Dept.
</a>


                <!-- Manajemen Data -->
                <div class="px-3 mb-2 mt-6">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Manajemen Data</span>
                </div>

                {{-- MENU BARU: KELOLA USER --}}
                <a href="{{ route('admin.users.index') }}"
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
                <div class="flex items-center justify-between group">
                    <div class="flex items-center">
                        {{-- Avatar Inisial Dinamis --}}
                        <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                        </div>
                        <div class="ml-3 overflow-hidden">
                            {{-- Nama User dari Database --}}
                            <p class="text-sm font-bold text-gray-700 truncate capitalize">
                                {{ auth()->user()->name ?? 'Administrator' }}
                            </p>
                            {{-- Role User dari Database --}}
                            <p class="text-[10px] font-medium text-blue-600 uppercase tracking-tight">
                                {{ auth()->user()->role ?? 'Admin' }}
                            </p>
                        </div>
                    </div>

                    {{-- Tombol Logout --}}
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

        </div> {{-- END SIDEBAR --}}

        {{-- MAIN CONTENT AREA --}}
        <div class="flex-1 overflow-y-auto bg-gray-50 min-w-0"> <!-- TAMBAHKAN min-w-0 -->
            <main class="p-8 pb-20">
                @yield('content')
            </main>
        </div>

    </div> {{-- END FLEX CONTAINER --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('sidebarSearch');
            const deptDropdown = document.getElementById('deptDropdown');
            const deptItems = document.querySelectorAll('.dept-item');
            const deptToggle = document.getElementById('deptToggle');
            const deptChevron = document.getElementById('deptChevron');
            const noResult = document.getElementById('noSidebarResult');

            // Pastikan elemen-elemen ini ada sebelum menambahkan event listener
            if (deptToggle && deptChevron) {
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
            }

            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const term = e.target.value.toLowerCase().trim();
                    let foundCount = 0;

                    if (term.length > 0) {
                        if (deptDropdown) deptDropdown.classList.remove('hidden');
                        if (deptChevron) deptChevron.classList.add('rotate-180');
                    } else {
                        if (deptDropdown) deptDropdown.classList.add('hidden');
                        if (deptChevron) deptChevron.classList.remove('rotate-180');
                        if (deptItems) {
                            deptItems.forEach(item => item.style.display = 'block');
                        }
                        if (noResult) noResult.classList.add('hidden');
                        return;
                    }

                    if (deptItems) {
                        deptItems.forEach(item => {
                            const deptName = item.getAttribute('data-name');
                            if (deptName && deptName.includes(term)) {
                                item.style.display = 'block';
                                foundCount++;
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    }

                    if (noResult) {
                        if (foundCount === 0) {
                            noResult.classList.remove('hidden');
                        } else {
                            noResult.classList.add('hidden');
                        }
                    }
                });
            }

            // Periksa apakah ada item aktif
            if (document.querySelector('.dept-item.active-link') && deptDropdown) {
                deptDropdown.classList.remove('hidden');
                if (deptChevron) deptChevron.classList.add('rotate-180');
            }
        });
    </script>

</body>
</html>
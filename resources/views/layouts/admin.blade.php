<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Audit Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .active-link { background-color: #e5e7eb; font-weight: bold; border-right: 4px solid #2563eb; }
        /* Transisi halus saat menyembunyikan elemen */
        .dept-item { transition: all 0.2s ease; }
    </style>
</head>
<body class="bg-gray-50">

    <div class="flex h-screen">
        <div class="w-64 bg-white border-r border-gray-200 shrink-0 flex flex-col">
            <div class="p-6 border-b">
                <h1 class="text-xl font-bold text-blue-800 uppercase leading-tight">PT Trias Sentosa</h1>
                <p class="text-xs text-gray-500 mt-1">Audit Dashboard System</p>
            </div>

            <div class="p-4 border-b bg-gray-50">
                <div class="relative">
                    <input type="text" 
                           id="deptSearch" 
                           placeholder="Cari departemen..." 
                           class="w-full pl-8 pr-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            
            <nav class="p-4 space-y-1 overflow-y-auto flex-1" id="deptList">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-4">Daftar Departemen</div>
                
                @foreach($departments as $dept)
                    <a href="{{ route('dept.show', $dept->id) }}" 
                       data-name="{{ strtolower($dept->name) }}"
                       class="dept-item block px-4 py-2 text-sm text-gray-700 rounded hover:bg-gray-100 {{ request()->is('dept/'.$dept->id) ? 'active-link' : '' }}">
                       {{ $dept->name }}
                    </a>
                @endforeach

                <div id="noResult" class="hidden px-4 py-2 text-sm text-gray-400 italic">
                    Departemen tidak ditemukan...
                </div>
            </nav>
        </div>

        <div class="flex-1 overflow-y-auto p-8">
            @yield('content')
            
            {{-- Default Dashboard View (Hanya muncul jika di root dashboard) --}}
            @if(request()->routeIs('dashboard'))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                    <div class="text-gray-500 text-sm uppercase font-semibold">Total Audit</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalAudits }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                    <div class="text-gray-500 text-sm uppercase font-semibold">Total User Auditor</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalAuditors }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500">
                    <div class="text-gray-500 text-sm uppercase font-semibold">Total Departemen</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalDepartments }}</div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 text-blue-800 px-6 py-4 rounded-lg flex items-center shadow-sm">
                <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Silakan pilih <strong>Departemen</strong> di menu sebelah kiri untuk melihat hasil audit secara detail.</span>
            </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('deptSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const deptItems = document.querySelectorAll('.dept-item');
            const noResult = document.getElementById('noResult');
            let foundCount = 0;

            deptItems.forEach(item => {
                const deptName = item.getAttribute('data-name');
                if (deptName.includes(searchTerm)) {
                    item.style.display = 'block';
                    foundCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Tampilkan pesan jika tidak ada yang cocok
            if (foundCount === 0) {
                noResult.classList.remove('hidden');
            } else {
                noResult.classList.add('hidden');
            }
        });
    </script>

</body>
</html>
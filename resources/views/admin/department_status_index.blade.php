@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Status Audit Departemen</h2>
            <p class="text-gray-500 mt-1">Rekapitulasi progres audit seluruh departemen.</p>
        </div>
        <div>
            {{-- Tombol Kembali ke Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-gray-500 hover:text-blue-600 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    {{-- Tabel Full Width --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-800">Daftar Semua Departemen</h3>
            
            {{-- Search Lokal untuk Tabel --}}
            <div class="relative w-64">
                <input type="text" id="tableSearch" placeholder="Cari nama departemen..." class="w-full pl-8 pr-3 py-1.5 text-xs border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="w-3 h-3 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Departemen</th>
                        <th class="px-6 py-4 text-center">Total Audit</th>
                        <th class="px-6 py-4 text-center">Selesai</th>
                        <th class="px-6 py-4 text-center">Berjalan (Pending)</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="tableBody">
                    @foreach($deptSummary as $dept)
                    <tr class="hover:bg-gray-50 transition-colors dept-row">
                        <td class="px-6 py-4 font-bold text-gray-800 dept-name">{{ $dept->name }}</td>
                        <td class="px-6 py-4 text-center font-semibold">{{ $dept->total_audit }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($dept->completed_count > 0)
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-bold">{{ $dept->completed_count }}</span>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($dept->pending_count > 0)
                                <span class="bg-amber-100 text-amber-700 px-2 py-1 rounded-full text-xs font-bold">{{ $dept->pending_count }}</span>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('dept.show', $dept->id) }}" class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded text-xs font-bold transition-colors shadow-sm">
                                DETAIL
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- Pesan jika tidak ketemu --}}
            <div id="tableNoResult" class="hidden p-8 text-center text-gray-400 text-sm italic">
                Departemen tidak ditemukan.
            </div>
        </div>
    </div>

    <script>
        // Script sederhana untuk search tabel
        document.getElementById('tableSearch').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.dept-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.querySelector('.dept-name').textContent.toLowerCase();
                if(name.includes(term)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const noResult = document.getElementById('tableNoResult');
            if(visibleCount === 0) noResult.classList.remove('hidden');
            else noResult.classList.add('hidden');
        });
    </script>
@endsection
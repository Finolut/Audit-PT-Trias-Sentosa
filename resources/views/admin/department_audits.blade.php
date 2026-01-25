@extends('layouts.admin')

@section('content')
    {{-- Header Section --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800">Status Audit Departemen</h2>
            <p class="text-gray-500 mt-1">Rekapitulasi progres audit seluruh departemen.</p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Form Filter Tahun --}}
            <form action="{{ url()->current() }}" method="GET" class="flex items-center bg-white px-3 py-1.5 rounded-xl border border-gray-200 shadow-sm transition-focus focus-within:ring-2 focus-within:ring-blue-500">
                <label for="year" class="text-xs font-bold text-gray-400 uppercase mr-2">Tahun:</label>
                <select name="year" id="year" onchange="this.form.submit()" class="text-sm border-none focus:ring-0 rounded-lg bg-transparent font-bold text-gray-700 cursor-pointer">
                    <option value="">Semua</option>
                    @for ($y = 2026; $y <= 2030; $y++)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </form>
        </div>
    </div>

    {{-- Tabel Full Width dengan Gaya Dashboard --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-800">Daftar Semua Departemen</h3>

            {{-- Search Lokal untuk Tabel --}}
            <div class="relative w-64">
                <input type="text" id="tableSearch" placeholder="Cari nama departemen..." class="w-full pl-8 pr-3 py-1.5 text-xs border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="w-3 h-3 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        {{-- Loading Skeleton --}} 
        <div id="loadingSkeleton" class="hidden p-8">
            <div class="animate-pulse">
                <div class="h-4 bg-gray-200 rounded mb-4 w-3/4"></div>
                <div class="space-y-3">
                    @for ($i = 0; $i < 5; $i++)
                        <div class="h-10 bg-gray-200 rounded"></div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="overflow-x-auto" id="tableContainer">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 text-center">Departemen</th>
                        <th class="px-6 py-4 text-center">Total Audit</th>
                        <th class="px-6 py-4 text-center">Selesai</th>
                        <th class="px-6 py-4 text-center">Berjalan (Pending)</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="tableBody">
                    @foreach($deptSummary as $dept)
                    <tr class="hover:bg-gray-50 transition-colors dept-row">
                        <td class="px-6 py-4 text-center font-bold text-gray-800 dept-name">{{ $dept->name }}</td>
                        <td class="px-6 py-4 text-center font-semibold">{{ $dept->total_audit }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($dept->completed_count > 0)
                                <span class="inline-flex items-center px-3 py-1 text-[10px] font-black rounded-full bg-green-100 text-green-700 border border-green-200 uppercase">
                                    ● SELESAI
                                </span>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($dept->pending_count > 0)
                                <span class="inline-flex items-center px-3 py-1 text-[10px] font-black rounded-full bg-amber-100 text-amber-700 border border-amber-200 uppercase">
                                    ● BERJALAN
                                </span>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.dept.show', $dept->id) }}" class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded text-xs font-bold transition-colors shadow-sm inline-block">
                                DETAIL
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div id="tableNoResult" class="hidden p-8 text-center text-gray-400 text-sm italic">
                Departemen tidak ditemukan.
            </div>
        </div>
    </div>

    {{-- Script Pencarian + Loading --}}
    <script>
        // Simulasi loading (opsional: bisa diganti dengan AJAX jika butuh loading real)
        document.addEventListener('DOMContentLoaded', function() {
            // Jika ada data, sembunyikan loading
            if (document.querySelectorAll('.dept-row').length > 0) {
                document.getElementById('loadingSkeleton').classList.add('hidden');
                document.getElementById('tableContainer').classList.remove('hidden');
            } else {
                document.getElementById('loadingSkeleton').classList.remove('hidden');
                document.getElementById('tableContainer').classList.add('hidden');
            }

            // Script pencarian
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
        });
    </script>
@endsection
@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">📋 Daftar Auditor Internal</h2>
            <p class="text-sm text-gray-500">Pantau kinerja dan riwayat pemeriksaan tim auditor.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow-sm">
            + Tambah Auditor Baru
        </a>
    </div>

    {{-- Search Lokal untuk Tabel --}}
    <div class="relative w-64 mb-4">
        <input type="text" id="tableSearch" placeholder="Cari nama auditor..." class="w-full pl-8 pr-3 py-1.5 text-xs border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <svg class="w-3 h-3 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider border-b border-gray-100">
                    <th class="p-4 font-semibold">Nama Auditor</th>
                    <th class="p-4 font-semibold">NIK</th>
                    <th class="p-4 font-semibold">Departemen Asal</th>
                    <th class="p-4 font-semibold text-center">Total Audit</th>
                    <th class="p-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="auditorTableBody">
                @forelse($auditors as $aud)
                <tr class="hover:bg-blue-50/50 transition-colors group">
                    <td class="p-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs mr-3">
                                {{ substr($aud->name, 0, 2) }}
                            </div>
                            <span class="font-medium text-gray-800">{{ $aud->name }}</span>
                        </div>
                    </td>
                    <td class="p-4 text-gray-600 text-sm">{{ $aud->nik }}</td>
                    <td class="p-4 text-gray-600 text-sm">{{ $aud->department }}</td>
<td class="p-4 text-center">
    @if(($aud->total_audits ?? 0) > 0)
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200 shadow-sm">
            {{ $aud->total_audits }} Selesai
        </span>
    @else
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-400 border border-gray-200">
            0 Selesai
        </span>
    @endif
</td>
                    <td class="p-4 text-right space-x-2">
                        <a href="{{ route('admin.auditors.show', $aud->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Lihat
                        </a>
                        <form action="{{ route('admin.auditors.destroy', $aud->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus auditor ini? Tindakan ini tidak bisa dikembalikan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-400 italic">
                        Belum ada data auditor. Silakan tambahkan user baru.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Script Pencarian Lokal --}}
<script>
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#auditorTableBody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection
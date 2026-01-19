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
            <tbody class="divide-y divide-gray-100">
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
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $aud->total_audits ?? 0 }} Selesai
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <a href="{{ route('admin.auditors.show', $aud->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center justify-end gap-1">
                            Lihat History <span>→</span>
                        </a>
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
@endsection
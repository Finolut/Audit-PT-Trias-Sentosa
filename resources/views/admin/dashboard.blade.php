@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Dashboard Overview</h2>
            <p class="text-gray-500 mt-1">Ringkasan aktivitas audit PT Trias Sentosa Tbk.</p>
        </div>
        <div class="text-right hidden md:block">
            <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Tanggal Hari Ini</span>
            <span class="text-lg font-bold text-gray-700">{{ date('d F Y') }}</span>
        </div>
    </div>

    {{-- 1. Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center hover:shadow-md transition-shadow">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Audit</p>
                <p class="text-3xl font-black text-gray-800">{{ $stats['total_audits'] }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center hover:shadow-md transition-shadow">
    <div class="p-3 bg-green-50 text-green-600 rounded-xl mr-4">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Selesai</p>
        <p class="text-3xl font-black text-gray-800">{{ $stats['completed'] }}</p>
    </div>
</div>

<div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center hover:shadow-md transition-shadow">
    <div class="p-3 bg-amber-50 text-amber-600 rounded-xl mr-4">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Berjalan</p>
        <p class="text-3xl font-black text-gray-800">{{ $stats['pending'] }}</p>
    </div>
</div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center hover:shadow-md transition-shadow">
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Departemen</p>
                <p class="text-3xl font-black text-gray-800">{{ $stats['departments'] }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- 2. Kolom Kiri: Tabel Performa Departemen --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-gray-800">Status Audit per Departemen</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Departemen</th>
                            <th class="px-6 py-4 text-center">Total</th>
                            <th class="px-6 py-4 text-center">Selesai</th>
                            <th class="px-6 py-4 text-center">Pending</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($deptSummary as $dept)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $dept->name }}</td>
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
                                <a href="{{ route('dept.show', $dept->id) }}" class="text-blue-600 hover:text-blue-800 font-bold text-xs">
                                    LIHAT DETAIL &rarr;
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 3. Kolom Kanan: Aktivitas Terbaru --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden h-fit">
    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
        <h3 class="font-bold text-gray-800">Audit Aktif & Terbaru</h3>
    </div>
    <div class="divide-y divide-gray-100">
        @forelse($recentAudits as $audit)
        <div class="p-5 hover:bg-gray-50 transition-colors">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">
                    {{ $audit->department->name }}
                </span>
                <span class="text-[10px] text-gray-400">
                    {{ $audit->created_at->format('d M Y') }}
                </span>
            </div>

            <!-- Tambahkan Scope & PIC -->
            <p class="text-sm text-gray-700 mt-1">
                <strong>Area:</strong> {{ Str::limit($audit->session?->audit_scope ?? 'N/A', 40) }}
            </p>
            <p class="text-sm text-gray-700">
                <strong>PIC:</strong> {{ $audit->session?->pic_name ?? 'Belum ditentukan' }}
            </p>

            <div class="mt-2 flex items-center justify-between">
                <span class="text-xs text-gray-500">
                    {{ $audit->session?->auditor_name ?? 'N/A' }}
                </span>
                @if($audit->status == 'COMPLETED')
                    <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-1 rounded-full">SELESAI</span>
                @else
                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">BERJALAN</span>
                @endif
            </div>

            <!-- Tombol Lihat Detail Audit (bukan hanya dept) -->
            <a href="{{ route('audit.overview', $audit->id) }}" 
               class="block mt-3 text-center w-full py-1.5 text-xs font-bold text-white bg-blue-600 rounded hover:bg-blue-700 transition-colors">
                LIHAT DETAIL AUDIT
            </a>
        </div>
        @empty
        <div class="p-8 text-center text-gray-400 text-sm">
            Belum ada audit yang dimulai.
        </div>
        @endforelse
    </div>
</div>
    </div>
@endsection
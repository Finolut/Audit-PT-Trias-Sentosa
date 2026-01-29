@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    {{-- Breadcrumb & Header --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex text-gray-500 text-xs mb-2 tracking-widest uppercase font-bold">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-gray-800">Log Evidence Audit</span>
            </nav>
            <h1 class="text-3xl font-black text-gray-800 tracking-tight">Riwayat Bukti Audit</h1>
            <p class="text-gray-500">Daftar lengkap bukti foto yang diunggah auditor selama proses audit lapangan.</p>
        </div>
        
        <a href="{{ route('admin.dashboard') }}" 
           class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl shadow-sm text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dashboard
        </a>
    </div>

    {{-- Pencarian & Filter (UI Only) --}}
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-6 flex flex-wrap gap-4 items-center">
        <div class="relative flex-1 min-w-[300px]">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" placeholder="Cari berdasarkan klausul, item, atau auditor..." 
                   class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-blue-500 sm:text-sm transition-all">
        </div>
        <select class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5">
            <option selected>Semua Departemen</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Table / List --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Waktu & Klausul</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Departemen & Auditor</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Item & Bukti</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($evidences as $row)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-black text-blue-600">
                                Clause {{ $row->clause_code }}
                            </div>
                            <div class="text-[10px] text-gray-400 font-bold uppercase">
                                {{ \Carbon\Carbon::parse($row->evidence_time)->format('d M Y | H:i') }}
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">
                                {{ $row->department_name }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $row->auditor_name }}
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-sm mb-2">
                                <strong>Item:</strong> {{ $row->item_text }}
                            </div>

                            <div class="flex flex-wrap gap-3 items-start">
                                <img
                                    src="{{ route('admin.evidence.view', $row->evidence_id) }}"
                                    alt="Evidence"
                                    class="w-24 h-24 object-cover rounded-xl border shadow-sm"
                                    onerror="this.src='{{ asset('images/placeholder-image.png') }}'"
                                />

                                <div class="text-xs space-y-1">
                                    @if($row->finding_level)
                                        <div class="font-bold uppercase 
                                            @if($row->finding_level === 'major') text-red-600
                                            @elseif($row->finding_level === 'minor') text-orange-600
                                            @else text-green-600 @endif">
                                            {{ 
                                                $row->finding_level === 'observed' ? 'OFI' : 
                                                ($row->finding_level === 'minor' ? 'Minor NC' : 'Major NC')
                                            }}
                                        </div>
                                    @endif
                                    <div class="text-gray-500">
                                        Jawaban: <span class="font-medium">{{ $row->answer }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.audit.overview', $row->audit_id) }}"
                               class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-600 hover:text-white transition-all inline-flex items-center">
                                Lihat Audit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-gray-400 text-sm font-medium">Belum ada bukti audit yang diunggah.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($evidences->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $evidences->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    .pagination svg { width: 1.5rem; display: inline; }
    .pagination nav p { margin-bottom: 0; }

    /* Optional: enhance image loading */
    img[alt="Evidence"] {
        background-color: #f9fafb;
    }
</style>
@endsection
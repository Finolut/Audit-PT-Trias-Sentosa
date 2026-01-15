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

    {{-- Layout Utama: 2 Kolom --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- KOLOM KIRI (2/3): DAFTAR AUDIT --}}
{{-- KOLOM KIRI (2/3): DAFTAR AUDIT --}}
<div class="lg:col-span-2">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 text-lg">Daftar Audit Terbaru</h3>
            <span class="text-xs text-gray-400 font-medium">Menampilkan 5 aktivitas terakhir</span>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentAudits as $audit)
            <div class="p-5 hover:bg-gray-50 transition-colors">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md uppercase">
                                {{ $audit->department->name }}
                            </span>
                            <span class="text-[10px] text-gray-400 italic">
                                {{ $audit->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        {{-- PERBAIKAN: Menampilkan 'Bagian yang Diperiksa' (Scope) --}}
                        <h4 class="text-sm font-semibold text-gray-800 mb-1">
                            Bagian yang Diperiksa: <span class="text-blue-600">{{ $audit->scope ?? 'N/A' }}</span>
                        </h4>

                        <div class="grid grid-cols-2 mt-2 gap-4 text-[11px] text-gray-500 border-t border-gray-50 pt-2">
                            {{-- PERBAIKAN: Menampilkan PIC Auditee (Penanggung Jawab) --}}
                            <div>
                                <p class="font-bold text-gray-400 uppercase text-[9px]">Penanggung Jawab (PIC)</p>
                                <p class="text-gray-700 font-medium">{{ $audit->pic_auditee_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="font-bold text-gray-400 uppercase text-[9px]">Auditor</p>
                                <p class="text-gray-700 font-medium">{{ $audit->session->auditor_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        @if($audit->status == 'COMPLETED')
                            <span class="text-[10px] font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full border border-green-100 shadow-sm">SELESAI</span>
                        @else
                            <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-full border border-amber-100 shadow-sm">BERJALAN</span>
                        @endif
                        <a href="{{ route('audit.overview', $audit->id) }}" 
                           class="px-4 py-2 text-xs font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all shadow-md shadow-blue-100">
                            DETAIL
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center text-gray-400">
                <p class="text-sm">Belum ada aktivitas audit.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

        {{-- KOLOM KANAN (1/3): PERTANYAAN TERKINI --}}
        <div class="lg:col-span-1">
            <div class="bg-blue-600 rounded-2xl shadow-lg shadow-blue-100 overflow-hidden border border-blue-700">
                <div class="px-6 py-5 border-b border-blue-500/30 flex items-center justify-between">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                        Log Pertanyaan
                    </h3>
                    <span class="bg-blue-400/30 text-blue-100 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">Live</span>
                </div>

                <div class="p-4 space-y-4 max-h-[600px] overflow-y-auto custom-scrollbar">
                    @forelse($liveQuestions as $q)
                    <div class="bg-blue-700/40 p-4 rounded-xl border border-blue-400/20 backdrop-blur-sm group hover:bg-blue-700/60 transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-blue-200 uppercase tracking-tighter">{{ $q->dept_name }}</span>
                                <span class="text-[11px] font-bold text-white">Clause {{ $q->clause_code }}</span>
                            </div>
                            <span class="text-[9px] text-blue-300 font-medium">{{ \Carbon\Carbon::parse($q->created_at)->diffForHumans() }}</span>
                        </div>
                        
                        <p class="text-xs text-blue-50 leading-relaxed mb-3 italic">
                            "{{ Str::limit($q->question_text, 100) }}"
                        </p>
                        
                        <div class="flex items-center gap-2 pt-2 border-t border-blue-400/20">
                            <div class="w-5 h-5 rounded-full bg-blue-400 flex items-center justify-center text-[8px] font-bold text-white">
                                {{ strtoupper(substr($q->auditor_name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="text-[10px] font-medium text-blue-200">
                                Auditor: <span class="text-white">{{ $q->auditor_name ?? 'N/A' }}</span>
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="py-8 text-center">
                        <p class="text-sm text-blue-200">Belum ada catatan pertanyaan.</p>
                    </div>
                    @endforelse
                </div>

                <div class="p-4 bg-blue-800/50">
                   <a href="{{ route('admin.question_log') }}" class="inline-block mt-4 text-blue-600 text-xs font-bold hover:underline">
    LIHAT SEMUA LOG PERTANYAAN →
</a>
                </div>
            </div>
        </div>
    </div>
@endsection
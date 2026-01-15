@extends('layouts.admin')

@section('content')
    {{-- Header & Stats Cards (Tetap seperti kode Anda) --}}
    ... 

    {{-- Layout Utama: 2 Kolom --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
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
                                <h4 class="text-sm font-semibold text-gray-800">
                                    Area: {{ Str::limit($audit->scope ?? 'N/A', 60) }}
                                </h4>
                                <div class="grid grid-cols-2 mt-2 gap-2 text-[11px] text-gray-500">
                                    <p><strong>PIC:</strong> {{ $audit->pic_auditee_name ?? 'N/A' }}</p>
                                    <p><strong>Auditor:</strong> {{ $audit->auditor_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($audit->status == 'COMPLETED')
                                    <span class="text-[10px] font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full border border-green-100">SELESAI</span>
                                @else
                                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-full border border-amber-100">BERJALAN</span>
                                @endif
                                <a href="{{ route('audit.overview', $audit->id) }}" 
                                   class="px-4 py-1.5 text-xs font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-shadow shadow-sm">
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
                                {{ strtoupper(substr($q->auditor_name, 0, 1)) }}
                            </div>
                            <span class="text-[10px] font-medium text-blue-200">
                                Auditor: <span class="text-white">{{ $q->auditor_name }}</span>
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
                    <a href="#" class="flex items-center justify-center gap-2 w-full text-[11px] font-bold text-blue-200 hover:text-white transition-colors">
                        LIHAT SEMUA LOG 
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
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

{{-- 2. CONTRIBUTION GRAPH SECTION --}}
    <div class="mb-8">
        <h3 class="font-bold text-gray-800 text-xl mb-4">Aktivitas Audit {{ $selectedYear }}</h3>
        
        <div class="flex flex-col lg:flex-row gap-6 items-start">
            
            {{-- A. Kiri: Grafik (White Box) --}}
            <div class="flex-1 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm w-full overflow-hidden">
                <div class="w-full overflow-x-auto custom-scrollbar pb-2">
                    <div class="flex flex-col min-w-max">
                        {{-- Baris Label Bulan --}}
                        <div class="flex gap-1 mb-1 pl-8"> 
                            @foreach($contributionData as $week)
                                <div class="w-3 text-[10px] text-gray-400 text-left overflow-visible whitespace-nowrap">
                                    {{-- Tampilkan label bulan --}}
                                    @if(!empty($week['month_label']))
                                        {{ $week['month_label'] }}
                                    @endif
                                </div>
                            @endforeach
                        </div>
            
                        {{-- Grid Utama --}}
                        <div class="flex gap-1">
                            {{-- Label Hari --}}
                            <div class="flex flex-col gap-1 mr-2 pt-[1px]">
                                <span class="text-[9px] text-gray-300 h-3 leading-3">Mon</span>
                                <span class="text-[9px] text-transparent h-3 leading-3">Tue</span>
                                <span class="text-[9px] text-gray-300 h-3 leading-3">Wed</span>
                                <span class="text-[9px] text-transparent h-3 leading-3">Thu</span>
                                <span class="text-[9px] text-gray-300 h-3 leading-3">Fri</span>
                                <span class="text-[9px] text-transparent h-3 leading-3">Sat</span>
                                <span class="text-[9px] text-transparent h-3 leading-3">Sun</span>
                            </div>
            
                            {{-- Kolom Kotak --}}
                            @foreach($contributionData as $week)
                                <div class="flex flex-col gap-1">
                                    @foreach($week['days'] as $day)
                                        @php
                                            // Tentukan Warna (Hanya 3 Level + Kosong)
                                            if(!$day['in_year']) {
                                                $colorClass = 'bg-transparent border border-gray-100/50'; // Luar tahun
                                            } else {
                                                $colorClass = match($day['level']) {
                                                    0 => 'bg-gray-100',      // Kosong
                                                    1 => 'bg-green-300',     // Sedikit
                                                    2 => 'bg-green-500',     // Sedang
                                                    3 => 'bg-green-800',     // Banyak
                                                    default => 'bg-gray-100',
                                                };
                                            }
                                        @endphp
                                        
                                        <div class="{{ $colorClass }} w-3 h-3 rounded-[2px] relative group cursor-pointer transition-colors duration-200">
                                            @if($day['in_year'])
                                                {{-- Tooltip --}}
                                                <div class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 hidden group-hover:block z-20 w-max pointer-events-none">
                                                    <div class="bg-gray-800 text-white text-[10px] py-1 px-2 rounded shadow-lg">
                                                        <span class="font-bold">{{ $day['count'] }} Audit</span>
                                                        <div class="text-gray-400 text-[9px]">{{ $day['date'] }}</div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Legend di bawah kotak grafik --}}
                <div class="mt-4 flex justify-between items-center border-t border-gray-50 pt-3">
                    <span class="text-xs text-gray-400">Learn how we count contributions</span>
                    <div class="flex items-center gap-1 text-[10px] text-gray-400">
                        <span>Less</span>
                        <div class="w-2.5 h-2.5 bg-gray-100 rounded-[1px]"></div>
                        <div class="w-2.5 h-2.5 bg-green-300 rounded-[1px]"></div>
                        <div class="w-2.5 h-2.5 bg-green-500 rounded-[1px]"></div>
                        <div class="w-2.5 h-2.5 bg-green-800 rounded-[1px]"></div>
                        <span>More</span>
                    </div>
                </div>
            </div>

            {{-- B. Kanan: Year Selector (Pill Style) --}}
            <div class="flex flex-row lg:flex-col gap-2 overflow-x-auto w-full lg:w-auto pb-2 lg:pb-0">
                @foreach($availableYears as $year)
                    <a href="{{ request()->fullUrlWithQuery(['year' => $year]) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all whitespace-nowrap
                       {{ $selectedYear == $year 
                          ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' 
                          : 'bg-white text-gray-500 hover:bg-gray-50 border border-transparent hover:border-gray-200' }}">
                        {{ $year }}
                    </a>
                @endforeach
            </div>

        </div>
    </div>

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
                                
                                <h4 class="text-sm font-semibold text-gray-800 mb-1">
                                    Bagian yang Diperiksa: <span class="text-blue-600">{{ $audit->scope ?? 'N/A' }}</span>
                                </h4>

                                <div class="grid grid-cols-2 mt-2 gap-4 text-[11px] text-gray-500 border-t border-gray-50 pt-2">
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

                            {{-- STATUS BADGE (Variabel $audit aman di sini karena dalam loop) --}}
                            <div class="flex items-center gap-3">
                                @php
                                    $statusNorm = strtoupper($audit->status);
                                @endphp

                                @if($statusNorm === 'COMPLETE' || $statusNorm === 'COMPLETED')
                                    <span class="text-[10px] font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full border border-green-100 shadow-sm uppercase">SELESAI</span>
                                @else
                                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-full border border-amber-100 shadow-sm uppercase">BERJALAN</span>
                                @endif

                                <a href="{{ route('admin.audit.overview', $audit->id) }}" 
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

        {{-- KOLOM KANAN (1/3): LOG PERTANYAAN --}}
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
                    <div class="py-8 text-center text-blue-200">
                        <p class="text-sm">Belum ada catatan pertanyaan.</p>
                    </div>
                    @endforelse
                </div>

                <div class="p-4 bg-blue-800/50 text-center">
                    <a href="{{ route('admin.question_log') }}" class="text-white text-xs font-bold hover:underline uppercase tracking-wider">
                        Lihat Semua Log →
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
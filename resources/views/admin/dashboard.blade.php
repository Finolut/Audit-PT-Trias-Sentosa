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
        <div class="flex-1 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm w-full overflow-hidden max-w-[800px] lg:max-w-none">
            <div class="w-full overflow-x-auto custom-scrollbar pb-2">
                <div class="flex flex-col min-w-max">
                    {{-- Baris Label Bulan --}}
                    <div class="flex gap-1 mb-1 pl-8"> 
                        @foreach($contributionData as $week)
                            <div class="w-3 text-[10px] text-gray-400 text-left overflow-visible whitespace-nowrap">
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
        if(!$day['in_year']) {
            $colorClass = 'bg-transparent border border-gray-100/50';
        } else {
            $colorClass = match($day['level']) {
                0 => 'bg-gray-100',
                1 => 'bg-green-300',
                2 => 'bg-green-500',
                3 => 'bg-green-800',
                default => 'bg-gray-100',
            };
        }
    @endphp
    
    {{-- Wrapper dengan group dan relative --}}
    <div class="group relative w-3 h-3">
        <div class="{{ $colorClass }} w-full h-full rounded-[2px] cursor-pointer transition-colors duration-200 hover:opacity-80"
             onclick="showAuditDetails('{{ $day['date'] }}', {{ $day['count'] }})">
        </div>

        {{-- Tooltip: dipindah ke luar kotak kecil --}}
        @if($day['in_year'] && $day['count'] > 0)
            <div class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 hidden group-hover:block z-20 w-max pointer-events-none">
                <div class="bg-gray-800 text-white text-[10px] py-1 px-2 rounded shadow-lg whitespace-nowrap">
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

            {{-- Legend --}}
            <div class="mt-4 flex justify-between items-center border-t border-gray-50 pt-3">
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

{{-- B. Kanan: Year Selector + Detail Panel --}}
<div class="w-full lg:w-auto flex flex-col gap-4">

    {{-- Dropdown Tahun --}}
    <select onchange="window.location.href = this.value;" 
            class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
        @foreach($availableYears as $year)
            <option value="{{ request()->fullUrlWithQuery(['year' => $year]) }}" 
                    {{ $selectedYear == $year ? 'selected' : '' }}>
                {{ $year }}
            </option>
        @endforeach
    </select>

    {{-- Panel Detail Audit (Default Hidden) --}}
    <div id="auditDetailPanel" class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm hidden lg:block min-w-[280px] max-w-[320px]">
        <h4 class="font-bold text-gray-800 text-sm mb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 14h.01M18 14h.01M15 11h3M12 11h.01M9 11h.01M7 21h10v-2a3 3 0 005.356-2.678M7 11H5v8h2V11z"/></svg>
            Detail Aktivitas Audit
        </h4>

        <div id="detailContent" class="text-xs text-gray-600 space-y-2">
            <p class="italic text-gray-400">Klik kotak di kalender untuk melihat detail.</p>
        </div>
    </div>

</div>

    </div>
</div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      {{-- KOLOM KIRI (2/3): DAFTAR AUDIT --}}
<div class="lg:col-span-2">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-white-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 text-lg">Daftar Audit Terbaru</h3>
            <span class="text-xs text-gray-500 font-medium">Menampilkan 5 aktivitas terakhir</span>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentAudits as $audit)
            <div class="p-5 hover:bg-gray-50 transition-colors">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-md uppercase tracking-wide">
                                {{ $audit->department->name }}
                            </span>
                            <span class="text-[10px] text-gray-500 italic">
                                {{ $audit->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <h4 class="text-sm font-semibold text-gray-800 mb-1">
                            Bagian yang Diperiksa: <span class="text-blue-700">{{ $audit->scope ?? 'N/A' }}</span>
                        </h4>

                        <div class="grid grid-cols-2 mt-3 gap-4 text-[11px] text-gray-600 pt-2 border-t border-gray-100">
                            <div>
                                <p class="font-bold text-gray-500 uppercase text-[9px] tracking-wider">Penanggung Jawab (PIC)</p>
                                <p class="text-gray-800 font-medium">{{ $audit->pic_auditee_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="font-bold text-gray-500 uppercase text-[9px] tracking-wider">Auditor</p>
                                <p class="text-gray-800 font-medium">{{ $audit->session->auditor_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- STATUS BADGE --}}
                    <div class="flex items-center gap-3">
                        @php
                            $statusNorm = strtoupper($audit->status);
                        @endphp

                        @if($statusNorm === 'COMPLETE' || $statusNorm === 'COMPLETED')
                            <span class="text-[10px] font-bold text-green-700 bg-green-100 px-3 py-1 rounded-full uppercase tracking-wide">SELESAI</span>
                        @else
                            <span class="text-[10px] font-bold text-amber-700 bg-amber-100 px-3 py-1 rounded-full uppercase tracking-wide">BERJALAN</span>
                        @endif

                        <a href="{{ route('admin.audit.overview', $audit->id) }}" 
                           class="px-4 py-2 text-xs font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all shadow-sm">
                            DETAIL
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center text-gray-500">
                <p class="text-sm">Belum ada aktivitas audit.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

       {{-- KOLOM KANAN (1/3): LOG PERTANYAAN --}}
<div class="lg:col-span-1">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                </svg>
                Pertanyaan
            </h3>
            <span class="bg-blue-100 text-blue-700 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">Live</span>
        </div>

        <div class="p-4 space-y-4 max-h-[600px] overflow-y-auto custom-scrollbar">
            @forelse($liveQuestions as $q)
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 group hover:bg-gray-100 transition-all">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-gray-500 uppercase tracking-tighter">{{ $q->dept_name }}</span>
                        <span class="text-[11px] font-bold text-gray-800">Clause {{ $q->clause_code }}</span>
                    </div>
                    <span class="text-[9px] text-gray-400 font-medium">{{ \Carbon\Carbon::parse($q->created_at)->diffForHumans() }}</span>
                </div>
                <p class="text-xs text-gray-700 leading-relaxed mb-3 italic">
                    "{{ Str::limit($q->question_text, 100) }}"
                </p>
                <div class="flex items-center gap-2 pt-2 border-t border-gray-200">
                    <div class="w-5 h-5 rounded-full bg-blue-500 flex items-center justify-center text-[8px] font-bold text-white">
                        {{ strtoupper(substr($q->auditor_name ?? 'A', 0, 1)) }}
                    </div>
                    <span class="text-[10px] font-medium text-gray-600">
                        Auditor: <span class="text-gray-800 font-semibold">{{ $q->auditor_name ?? 'N/A' }}</span>
                    </span>
                </div>
            </div>
            @empty
            <div class="py-8 text-center text-gray-400">
                <p class="text-sm">Belum ada catatan pertanyaan.</p>
            </div>
            @endforelse
        </div>

        <div class="p-4 bg-gray-50/60 text-center border-t border-gray-100">
            <a href="{{ route('admin.question_log') }}" class="text-blue-600 text-xs font-bold hover:underline uppercase tracking-wider">
                Lihat Semua Pertanyaan →
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showAuditDetails(dateString, count) {
    // 1. Definisikan elemen panel dan content agar JS tahu ke mana data harus dimasukkan
    const panel = document.getElementById('auditDetailPanel');
    const content = document.getElementById('detailContent');

    if (!panel || !content) return; // Guard clause jika elemen tidak ditemukan

    // Tampilkan panel (karena defaultnya hidden di mobile/tertentu)
    panel.classList.remove('hidden');

    // Tampilkan loading state
    content.innerHTML = `<div class="text-xs text-gray-500 italic animate-pulse">Memuat data...</div>`;

    // 2. Gunakan URL dari Route Laravel
    const url = `{{ route('admin.audit.day-details') }}?date=${encodeURIComponent(dateString)}`;

    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.audits.length > 0) {
            let html = `
                <div class="mb-3 border-b border-gray-100 pb-2">
                    <span class="font-bold text-gray-800">${dateString}</span> 
                    <span class="text-blue-600 text-[10px] font-bold">(${data.count} AUDIT)</span>
                </div>
                <div class="space-y-3">
            `;
            
            data.audits.forEach(audit => {
                // Link detail disesuaikan dengan route overview kamu
                const detailUrl = `{{ url('/admin/audit/overview') }}/${audit.id}`;
                
                html += `
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 hover:border-blue-300 transition-colors shadow-sm">
                        <div class="text-[9px] font-black text-blue-700 bg-blue-100 inline-block px-2 py-0.5 rounded mb-2 uppercase tracking-tighter">
                            ${audit.department_name}
                        </div>
                        <div class="space-y-1">
                            <p class="text-[11px] text-gray-700"><strong>PIC:</strong> ${audit.pic_auditee_name}</p>
                            <p class="text-[11px] text-gray-700"><strong>Auditor:</strong> ${audit.auditor_name}</p>
                            <p class="text-[11px] text-gray-600 line-clamp-2 italic">"${audit.scope}"</p>
                        </div>
                        <a href="${detailUrl}" class="text-[11px] text-blue-600 font-bold hover:underline mt-3 block text-right">
                            DETAIL AKTIVITAS →
                        </a>
                    </div>
                `;
            });
            html += '</div>';
            content.innerHTML = html;
        } else {
            content.innerHTML = `
                <div class="text-center py-4 text-gray-400">
                    <p class="text-xs italic">Tidak ada aktivitas audit pada tanggal ini.</p>
                </div>
            `;
        }
    })
    .catch(err => {
        console.error('Full Error:', err);
        content.innerHTML = `
            <div class="text-[10px] text-red-600 bg-red-50 p-3 rounded-lg border border-red-200">
                <strong>Gagal Memuat:</strong><br>
                ${err.message}. Pastikan koneksi aman dan controller tidak error 500.
            </div>
        `;
    });
}
</script>
@endpush

@endsection
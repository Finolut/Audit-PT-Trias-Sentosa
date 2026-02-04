@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">

    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Informasi Audit</h1>
                <p class="text-gray-500">
                    Department: {{ $audit->department->name ?? '-' }} | 
                    Date: {{ $audit->created_at->format('d M Y') }}
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.audit.export.pdf', $audit->id) }}" 
                   class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-white font-bold flex items-center shadow-sm transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-3 text-sm">
                {{-- KIRI --}}
                <div class="space-y-2">
                    <div class="flex">
                        <span class="w-48 font-semibold text-gray-600">Kode Audit</span>:
                        <span class="ml-2 font-mono text-gray-800">{{ $auditSummary['audit_code'] }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-48 font-semibold text-gray-600">Jenis Pemeriksaan</span>:
                        <span class="ml-2">{{ $auditSummary['type'] }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-48 font-semibold text-gray-600">Referensi Standar</span>:
                        <span class="ml-2">{{ $auditSummary['standards'] }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-48 font-semibold text-gray-600">Audit Objective</span>:
                        <span class="ml-2">{{ $auditSummary['objective'] }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-48 font-semibold text-gray-600">Audit Scope</span>:
                        <span class="ml-2">{{ $auditSummary['scope'] }}</span>
                    </div>
                </div>

                {{-- KANAN --}}
                <div class="space-y-2">
                    <div class="flex">
                        <span class="w-48 font-semibold text-gray-600">Lead Auditor</span>:
                        <span class="ml-2">{{ $leadAuditor['name'] }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-48 font-semibold text-gray-600">Email Lead Auditor</span>:
                        <span class="ml-2 text-blue-700 underline">{{ $leadAuditor['email'] }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-48 font-semibold text-gray-600">Anggota Tim</span>:
                        <span class="ml-2">
                            @forelse($teamMembers as $member)
                                {{ $member->name }}@if(!$loop->last), @endif
                            @empty
                                -
                            @endforelse
                        </span>
                    </div>
                    <div class="flex">
                        <span class="w-48 font-semibold text-gray-600">Periode Audit</span>:
                        <span class="ml-2">{{ $auditSummary['start_date'] }} – {{ $auditSummary['end_date'] }}</span>
                    </div>
                    <div class="flex items-center pt-1">
                        <span class="w-48 font-semibold text-gray-600">Status Audit</span>:
                        @if($auditSummary['status'] === 'COMPLETE')
                            <span class="ml-2 px-2 py-0.5 text-xs font-bold bg-green-100 text-green-700 rounded border border-green-200">SELESAI</span>
                        @else
                            <span class="ml-2 px-2 py-0.5 text-xs font-bold bg-yellow-100 text-yellow-700 rounded border border-yellow-200">BERJALAN</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div> 

    <!-- Grafik Utama -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Gambaran Per Klausul Utama</h3>
            <div class="h-64"><canvas id="mainClauseChart"></canvas></div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Rincian Lengkap (Semua Klausus)</h3>
            <div class="h-64"><canvas id="detailedClauseChart"></canvas></div>
        </div>
    </div>

    <!-- Grafik Finding Level - DIPERBAIKI -->
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-8">
        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">
            <svg class="w-5 h-5 inline-block mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Distribusi Finding Level
        </h3>
        <div class="h-64"><canvas id="findingLevelChart"></canvas></div>
        <div class="mt-4 grid grid-cols-3 gap-4 text-center">
            <div class="flex flex-col items-center">
                <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                <span class="text-xs font-medium">Observed: {{ $findingChartData['observed'] ?? 0 }}</span>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                <span class="text-xs font-medium">Minor: {{ $findingChartData['minor'] ?? 0 }}</span>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                <span class="text-xs font-medium">Major: {{ $findingChartData['major'] ?? 0 }}</span>
            </div>
        </div>
    </div>

    <!-- Detail per Klausul -->
    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
        <span class="bg-blue-600 w-2 h-6 rounded-full mr-3"></span>
        Detail Audit per Klausul
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
        @foreach($mainClauses as $key => $subClauses)
            @php 
                $mStats = $mainStats[$key] ?? ['yes'=>0, 'no'=>0, 'na'=>0, 'unanswered'=>0];
                $sudahDikerjakan = $mStats['yes'] + $mStats['no'] + $mStats['na'];
                $totalSoal = array_sum($mStats);
                $persenProgres = $totalSoal > 0 ? round(($sudahDikerjakan / $totalSoal) * 100) : 0;
                $isComplete = ($mStats['unanswered'] == 0);
            @endphp

            <a href="{{ route('admin.audit.clause_detail', ['auditId' => $audit->id, 'mainClause' => $key]) }}" 
               class="btn-clause block transition-all duration-300 p-6 rounded-xl shadow-sm border group bg-white hover:shadow-lg {{ $isComplete ? 'border-gray-200 hover:border-green-400' : 'border-dashed border-gray-300 hover:border-blue-400' }}">
                
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-extrabold text-xl transition-transform group-hover:scale-110 {{ $isComplete ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                        {{ $key }}
                    </div>

                    @if($isComplete)
                        <span class="text-[10px] font-bold px-2 py-1 rounded bg-green-100 text-green-700 uppercase">Selesai</span>
                    @else
                        <span class="text-[10px] font-bold px-2 py-1 rounded bg-amber-100 text-amber-700 uppercase">{{ $persenProgres }}% Terisi</span>
                    @endif
                </div>
                
                <h4 class="text-lg font-bold text-gray-800 mb-1 group-hover:text-blue-700">
                    {{ $titles[$key] ?? 'Klausul '.$key }}
                </h4>
                <p class="text-xs text-gray-500 mb-4 h-8 overflow-hidden">
                    Mencakup: {{ implode(', ', $subClauses) }}
                </p>

                <div class="w-full bg-gray-200 rounded-full h-1.5 mb-2">
                    <div class="h-1.5 rounded-full {{ $isComplete ? 'bg-green-500' : 'bg-blue-500' }}" style="width: {{ $persenProgres }}%"></div>
                </div>
                
                <div class="flex justify-between items-center text-[10px] font-medium text-gray-400">
                    <span>{{ $sudahDikerjakan }} / {{ $totalSoal }} Soal</span>
                    @if($mStats['unanswered'] > 0)
                        <span class="text-amber-600 font-bold">{{ $mStats['unanswered'] }} Belum</span>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
</div>

{{-- SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const mainStats = {!! json_encode($mainStats) !!};
    const detailedStats = {!! json_encode($detailedStats) !!};
    
    // --- PERBAIKAN UTAMA DI SINI ---
    // Pastikan ketiga level selalu ada, bahkan jika nilainya 0
    const findingData = {!! json_encode([
        'observed' => $findingChartData['observed'] ?? 0,
        'minor' => $findingChartData['minor'] ?? 0,
        'major' => $findingChartData['major'] ?? 0,
    ]) !!};

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'top' } },
        scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } }
    };

    function createDatasets(statsObj, labels) {
        return [
            { label: 'Yes', data: labels.map(l => statsObj[l]?.yes ?? 0), backgroundColor: '#22c55e' },
            { label: 'No', data: labels.map(l => statsObj[l]?.no ?? 0), backgroundColor: '#ef4444' },
            { label: 'N/A', data: labels.map(l => statsObj[l]?.na ?? 0), backgroundColor: '#facc15' },
            { label: 'Belum', data: labels.map(l => statsObj[l]?.unanswered ?? 0), backgroundColor: '#e2e8f0' }
        ];
    }

    // Main Clause Chart
    const mainLabels = Object.keys(mainStats);
    new Chart(document.getElementById('mainClauseChart'), {
        type: 'bar',
        data: { labels: mainLabels.map(l => 'Clause ' + l), datasets: createDatasets(mainStats, mainLabels) },
        options: commonOptions
    });

    // Detailed Clause Chart
    const detailedLabels = Object.keys(detailedStats);
    new Chart(document.getElementById('detailedClauseChart'), {
        type: 'bar',
        data: { labels: detailedLabels, datasets: createDatasets(detailedStats, detailedLabels) },
        options: commonOptions
    });

    // Finding Level Chart - DIPERBAIKI
    new Chart(document.getElementById('findingLevelChart'), {
        type: 'doughnut',
        data: {
            labels: ['Observed', 'Minor', 'Major'],
            datasets: [{
                data: [
                    findingData.observed,
                    findingData.minor,
                    findingData.major
                ],
                backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
</script>
@endsection
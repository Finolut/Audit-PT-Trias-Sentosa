@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    {{-- HEADER --}}
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Audit Overview</h1>
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
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded text-gray-700 font-bold transition">
                    Back to Dashboard
                </a>
            </div>
        </div>

        {{-- 📋 RINGKASAN DATA (KOTAK BIRU) --}}
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200 shadow-sm">
            <h3 class="font-bold text-lg text-blue-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Informasi Audit
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 text-sm">
                <div class="space-y-3">
                    <div class="flex">
                        <span class="w-44 font-bold text-gray-600">Jenis Pemeriksaan</span>
                        <span class="mr-2">:</span>
                        <span class="text-gray-800">
                            @php
                                $typeLabels = [
                                    'Regular' => 'Pemeriksaan Rutin (Terjadwal)',
                                    'Special' => 'Pemeriksaan Khusus (Mendadak)',
                                    'FollowUp' => 'Pemeriksaan Lanjutan (Follow Up)'
                                ];
                            @endphp
                            {{ $typeLabels[$audit->type] ?? '-' }}
                        </span>
                    </div>
                    <div class="flex">
                        <span class="w-44 font-bold text-gray-600">Tanggal Pemeriksaan</span>
                        <span class="mr-2">:</span>
                        <span class="text-gray-800">{{ $audit->created_at->format('d F Y') }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-44 font-bold text-gray-600">Bagian yang Diperiksa</span>
                        <span class="mr-2">:</span>
                        <span class="text-gray-800 font-medium">{{ $audit->scope ?? '-' }}</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex">
                        <span class="w-44 font-bold text-gray-600">Auditor Utama</span>
                        <span class="mr-2">:</span>
                        <span class="text-gray-800 font-medium">{{ $leadAuditor['name'] ?? '-' }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-44 font-bold text-gray-600">Penanggung Jawab</span>
                        <span class="mr-2">:</span>
                        <span class="text-gray-800 font-medium">{{ $audit->pic_auditee_name ?? '-' }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-44 font-bold text-gray-600">Anggota Tim</span>
                        <span class="mr-2">:</span>
                        <div class="flex-1">
                            @forelse($teamMembers as $member)
                                <div class="text-gray-800 mb-1">
                                    <span class="font-medium">{{ $member->name }}</span>
                                    <span class="text-gray-500 text-xs">(NIK: {{ $member->nik }})</span>
                                </div>
                            @empty
                                <span class="text-gray-400 italic">Tidak ada anggota tim</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <p class="text-sm font-bold text-gray-600 mb-1">Pemeriksaan Terkait :</p>
                <p class="text-gray-700 bg-white/60 p-3 rounded-lg border border-blue-100 italic text-sm">
                    "{{ $audit->objective ?? 'Tidak diisi oleh auditor.' }}"
                </p>
            </div>

            <div class="mt-4 pt-4 border-t border-blue-200/50 flex items-center justify-between">
                <div class="flex items-center">
                    <span class="text-sm font-bold text-gray-600 mr-3">Status:</span>
                    @php $currentStatus = strtoupper($audit->status); @endphp
                    @if($currentStatus === 'COMPLETE' || $currentStatus === 'COMPLETED')
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg font-bold text-xs uppercase shadow-sm border border-green-200">
                             SELESAI
                        </span>
                    @else
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg font-bold text-xs uppercase shadow-sm border border-yellow-200">
                             SEDANG BERJALAN
                        </span>
                    @endif
                </div>
                <span class="text-[10px] text-blue-400 font-mono">ID: {{ $audit->id }}</span>
            </div>
        </div> {{-- AKHIR KOTAK BIRU --}}
    </div> {{-- AKHIR HEADER SECTION --}}

    {{-- GRAFIK SECTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Overview per Main Clause</h3>
            <div class="h-64">
                <canvas id="mainClauseChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Detailed Breakdown (All Clauses)</h3>
            <div class="h-64">
                <canvas id="detailedClauseChart"></canvas>
            </div>
        </div>
    </div>

    {{-- MENU GRID: Detail Audit per Klausul --}}
    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
        <span class="bg-blue-600 w-2 h-6 rounded-full mr-3"></span>
        Detail Audit per Klausul
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
        @foreach($mainClauses as $key => $subClauses)
            @php 
                $mStats = $mainStats[$key] ?? ['yes'=>0, 'no'=>0, 'partial'=>0, 'na'=>0, 'unanswered'=>0];
                $sudahDikerjakan = $mStats['yes'] + $mStats['no'] + $mStats['partial'] + $mStats['na'];
                $totalSoal = array_sum($mStats);
                $persenProgres = $totalSoal > 0 ? round(($sudahDikerjakan / $totalSoal) * 100) : 0;
                $isComplete = ($mStats['unanswered'] == 0);
            @endphp

            <a href="{{ route('admin.audit.clause_detail', ['auditId' => $audit->id, 'mainClause' => $key]) }}" 
               class="block transition-all duration-300 p-6 rounded-xl shadow-sm border group bg-white hover:shadow-lg {{ $isComplete ? 'border-gray-200 hover:border-green-400' : 'border-dashed border-gray-300 hover:border-blue-400' }}">
                
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-extrabold text-xl transition-transform group-hover:scale-110 {{ $isComplete ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                        {{ $key }}
                    </div>

                    @if($isComplete)
                        <span class="text-[10px] font-bold px-2 py-1 rounded bg-green-100 text-green-700 uppercase">
                            Selesai
                        </span>
                    @else
                        <span class="text-[10px] font-bold px-2 py-1 rounded bg-amber-100 text-amber-700 uppercase">
                            {{ $persenProgres }}% Terisi
                        </span>
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

{{-- SCRIPT TETAP SAMA --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: { stacked: true },
            y: { stacked: true, beginAtZero: true }
        },
        plugins: { legend: { position: 'top' } }
    };

// ... Cari script Chart di bagian bawah file blade ...

// --- CHART 1: MAIN CLAUSE ---
const mainStats = {!! json_encode($mainStats) !!}; 
const mainLabels = Object.keys(mainStats);

new Chart(document.getElementById('mainClauseChart'), {
    type: 'bar',
    data: {
        labels: mainLabels.map(l => 'Clause ' + l),
        datasets: [
            { label: 'Yes', data: mainLabels.map(l => mainStats[l].yes), backgroundColor: '#22c55e' }, // Green
            { label: 'Partial', data: mainLabels.map(l => mainStats[l].partial), backgroundColor: '#94a3b8' }, // Slate
            { label: 'No', data: mainLabels.map(l => mainStats[l].no), backgroundColor: '#ef4444' }, // Red
            { label: 'N/A', data: mainLabels.map(l => mainStats[l].na), backgroundColor: '#facc15' }, // Yellow
            // TAMBAHAN DATASET BARU UNTUK BELUM DIISI (WARNA ABU-ABU MUDA)
            { 
                label: 'Belum Diisi', 
                data: mainLabels.map(l => mainStats[l].unanswered), 
                backgroundColor: '#e2e8f0', // Gray-200 (Warna netral/kosong)
                borderColor: '#cbd5e1',     // Border sedikit lebih gelap
                borderWidth: 1
            },
        ]
    },
    options: commonOptions
});

// --- CHART 2: DETAILED CLAUSE ---
const detailStats = {!! json_encode($detailedStats) !!}; 
const detailLabels = Object.keys(detailStats);

new Chart(document.getElementById('detailedClauseChart'), {
    type: 'bar',
    data: {
        labels: detailLabels,
        datasets: [
            { label: 'Yes', data: detailLabels.map(l => detailStats[l].yes), backgroundColor: '#22c55e' },
            { label: 'Partial', data: detailLabels.map(l => detailStats[l].partial), backgroundColor: '#94a3b8' },
            { label: 'No', data: detailLabels.map(l => detailStats[l].no), backgroundColor: '#ef4444' },
            { label: 'N/A', data: detailLabels.map(l => detailStats[l].na), backgroundColor: '#facc15' },
            // TAMBAHAN DATASET BARU JUGA DI SINI
            { 
                label: 'Belum Diisi', 
                data: detailLabels.map(l => detailStats[l].unanswered), 
                backgroundColor: '#e2e8f0',
                borderColor: '#cbd5e1',
                borderWidth: 1
            },
        ]
    },
    options: {
        ...commonOptions,
        scales: {
            x: { stacked: true, ticks: { autoSkip: false, maxRotation: 90, minRotation: 90, font: {size: 10} } },
            y: { stacked: true }
        }
    }
});

// Dataset untuk Chart (Gunakan ini untuk Main Chart dan Detailed Chart)
datasets: [
    { 
        label: 'Yes', 
        data: labels.map(l => stats[l].yes), 
        backgroundColor: '#22c55e' // Hijau
    },
    { 
        label: 'Partial', 
        data: labels.map(l => stats[l].partial), 
        backgroundColor: '#94a3b8' // Slate/Abu Tua
    },
    { 
        label: 'No', 
        data: labels.map(l => stats[l].no), 
        backgroundColor: '#ef4444' // Merah
    },
    { 
        label: 'N/A', 
        data: labels.map(l => stats[l].na), 
        backgroundColor: '#facc15' // KUNING (Wajib diisi tapi N/A)
    },
    { 
        label: 'Belum Diisi', 
        data: labels.map(l => stats[l].unanswered), 
        backgroundColor: '#e2e8f0' // ABU-ABU MUDA (Belum disentuh)
    }
]
</script>
@endsection
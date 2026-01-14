@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    
{{-- HEADER + RINGKASAN INFORMASI AUDIT --}}
<div class="mb-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Audit Overview</h1>
            <p class="text-gray-500">
                Department: {{ $audit->department->name ?? '-' }} | 
                Date: {{ $audit->created_at->format('d M Y') }}
            </p>
        </div>
        <a href="{{ route('admin.dashboard') }}" 
           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded text-gray-700 font-bold">
            Back to Dashboard
        </a>
    </div>

    {{-- 📋 Ringkasan Data dari Form Awal --}}
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-5 rounded-xl border border-blue-200 shadow-sm">
        <h3 class="font-bold text-lg text-blue-800 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Informasi Audit
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <!-- Kolom Kiri -->
            <div>
                <p><strong>Jenis Audit:</strong> 
                    @php
                        $typeLabels = [
                            'Regular' => 'Rutin / Terjadwal',
                            'Special' => 'Khusus / Insidental',
                            'FollowUp' => 'Follow Up'
                        ];
                    @endphp
                    {{ $typeLabels[$audit->session?->audit_type] ?? '-' }}
                </p>
                <p><strong>Tanggal Audit:</strong> 
                    {{ $audit->session?->audit_date ? \Carbon\Carbon::parse($audit->session->audit_date)->format('d F Y') : '-' }}
                </p>
                <p><strong>Area yang Diperiksa:</strong> 
                    <span class="font-medium">{{ $audit->session?->audit_scope ?? '-' }}</span>
                </p>
            </div>

            <!-- Kolom Kanan -->
            <div>
                <p><strong>Auditor Utama:</strong> 
                    <span class="font-medium">{{ $audit->session?->auditor_name ?? '-' }}</span>
                </p>
                <p><strong>PIC (Penanggung Jawab):</strong> 
                    <span class="font-medium">{{ $audit->session?->pic_name ?? '-' }}</span>
                </p>
                <p><strong>NIK PIC:</strong> 
                    {{ $audit->session?->pic_nik ?: 'Tidak ada' }}
                </p>
            </div>
        </div>

        <!-- Tujuan Audit (full width) -->
        <div class="mt-4">
            <p><strong>Alasan Melakukan Pemeriksaan:</strong></p>
            <p class="text-gray-700 bg-white p-3 rounded mt-1 border border-gray-200">
                {{ $audit->session?->audit_objective ?? 'Tidak diisi oleh auditor.' }}
            </p>
        </div>

        <!-- Status Audit -->
        <div class="mt-3 flex items-center">
            <span class="text-sm font-bold mr-2">Status:</span>
            @if($audit->status === 'COMPLETED')
                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">SELESAI</span>
            @else
                <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-bold">SEDANG BERJALAN</span>
            @endif
        </div>
    </div>
</div>

    {{-- GRAFIK SECTION (2 VERSI) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        
        {{-- GRAFIK 1: MAIN CLAUSES ONLY (4, 5, 6...) --}}
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Overview per Main Clause (4-10)</h3>
            <div class="h-64">
                <canvas id="mainClauseChart"></canvas>
            </div>
        </div>

        {{-- GRAFIK 2: ALL DETAILED CLAUSES (4.1, 4.2...) --}}
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Detailed Breakdown (All Clauses)</h3>
            <div class="h-64">
                <canvas id="detailedClauseChart"></canvas>
            </div>
        </div>
    </div>

    {{-- MENU GRID (Navigasi yang sudah ada) --}}
    <h3 class="text-xl font-bold text-gray-800 mb-4">Detail Audit per Klausul</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($mainClauses as $key => $subClauses)
        <a href="{{ route('audit.clause_detail', ['auditId' => $audit->id, 'mainClause' => $key]) }}" 
           class="block bg-white hover:bg-blue-50 transition-all duration-300 p-6 rounded-xl shadow border border-gray-200 group">
            
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-extrabold text-xl group-hover:scale-110 transition-transform">
                    {{ $key }}
                </div>
                {{-- Mini Status Indicator (Contoh ambil dari data mainStats) --}}
                @php 
                    $mStats = $mainStats[$key] ?? ['yes'=>0, 'no'=>0];
                    $totalM = array_sum($mStats);
                    $perc = $totalM > 0 ? round(($mStats['yes']/$totalM)*100) : 0;
                @endphp
                <span class="text-xs font-bold px-2 py-1 rounded bg-gray-100 text-gray-600">
                    {{ $perc }}% Compliant
                </span>
            </div>
            
            <h4 class="text-lg font-bold text-gray-800 mb-1 group-hover:text-blue-700">
                {{ $titles[$key] ?? 'Clause '.$key }}
            </h4>
            <p class="text-sm text-gray-500 mb-4">
                Includes: {{ implode(', ', $subClauses) }}
            </p>
        </a>
        @endforeach
    </div>

</div>

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

    // --- CHART 1: MAIN CLAUSE ---
    const mainStats = {!! json_encode($mainStats) !!}; // Object { '4':{yes:2...}, '5':... }
    const mainLabels = Object.keys(mainStats);
    
    new Chart(document.getElementById('mainClauseChart'), {
        type: 'bar',
        data: {
            labels: mainLabels.map(l => 'Clause ' + l),
            datasets: [
                { label: 'Yes', data: mainLabels.map(l => mainStats[l].yes), backgroundColor: '#22c55e' },
                { label: 'Partial', data: mainLabels.map(l => mainStats[l].partial), backgroundColor: '#94a3b8' },
                { label: 'No', data: mainLabels.map(l => mainStats[l].no), backgroundColor: '#ef4444' },
                { label: 'N/A', data: mainLabels.map(l => mainStats[l].na), backgroundColor: '#facc15' },
            ]
        },
        options: commonOptions
    });

    // --- CHART 2: DETAILED CLAUSE ---
    const detailStats = {!! json_encode($detailedStats) !!}; // Object { '4.1':{...}, '4.2':... }
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
            ]
        },
        options: {
            ...commonOptions,
            scales: {
                x: { stacked: true, ticks: { autoSkip: false, maxRotation: 90, minRotation: 90, font: {size: 10} } }, // Rotate label biar muat
                y: { stacked: true }
            }
        }
    });
</script>
@endsection
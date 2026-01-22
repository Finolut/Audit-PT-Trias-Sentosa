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
<div class="flex space-x-3">
    <!-- HANYA TOMBOL PDF -->
    <a href="{{ route('admin.audit.export.pdf', $audit->id) }}" 
       class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-white font-bold flex items-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Export PDF
    </a>
    <a href="{{ route('admin.dashboard') }}" 
       class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded text-gray-700 font-bold">
        Back to Dashboard
    </a>
</div>
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
                <p><strong>Jenis Pemeriksaan:</strong> 
                    @php
                        $typeLabels = [
                            'Regular' => 'Pemeriksaan Rutin (Terjadwal)',
                            'Special' => 'Pemeriksaan Khusus (Mendadak)',
                            'FollowUp' => 'Pemeriksaan Lanjutan (Follow Up)'
                        ];
                    @endphp
                    {{ $typeLabels[$audit->type] ?? '-' }}
                </p>
          <p><strong>Tanggal Pemeriksaan:</strong> 
    {{ $audit->created_at ? \Carbon\Carbon::parse($audit->created_at)->format('d F Y') : '-' }}
</p>
                <p><strong>Bagian yang Diperiksa:</strong> 
                    <span class="font-medium">{{ $audit->scope ?? '-' }}</span>
                </p>
            </div>

          <div>
    <p><strong>Auditor Utama:</strong> 
        <span class="font-medium">{{ $leadAuditor['name'] ?? '-' }}</span>
    </p>
    <p><strong>Penanggung Jawab di Departemen:</strong> 
        <span class="font-medium">{{ $audit->pic_auditee_name ?? '-' }}</span>
    </p>
    <p><strong>NIK Penanggung Jawab:</strong> 
        {{ $audit->pic_auditee_nik ?: 'Tidak ada' }}
    </p>
</div>

        <!-- Tujuan Audit (full width) -->
        <div class="mt-4">
            <p><strong>Alasan Melakukan Pemeriksaan Ini:</strong></p>
            <p class="text-gray-700 bg-white p-3 rounded mt-1 border border-gray-200">
                {{ $audit->objective ?? 'Tidak diisi oleh auditor.' }}
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

   {{-- MENU GRID: Detail Audit per Klausul --}}
<h3 class="text-xl font-bold text-gray-800 mb-4">Detail Audit per Klausul</h3>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($mainClauses as $key => $subClauses)
    @php 
        // Mengambil statistik untuk klausul utama ini
        $mStats = $mainStats[$key] ?? ['yes'=>0, 'no'=>0, 'partial'=>0, 'na'=>0, 'unanswered'=>0];
        
        // Total soal yang sudah dikerjakan (Yes + No + Partial + N/A)
        $sudahDikerjakan = $mStats['yes'] + $mStats['no'] + $mStats['partial'] + $mStats['na'];
        
        // Total semua soal dalam klausul ini
        $totalSoal = array_sum($mStats);
        
        // Hitung persentase progres pengisian
        $persenProgres = $totalSoal > 0 ? round(($sudahDikerjakan / $totalSoal) * 100) : 0;
        
        // Cek apakah sudah selesai semua (tidak ada yang unanswered)
        $isComplete = ($mStats['unanswered'] == 0);
    @endphp

    <a href="{{ route('admin.audit.clause_detail', ['auditId' => $audit->id, 'mainClause' => $key]) }}" 
       class="block transition-all duration-300 p-6 rounded-xl shadow border group {{ $isComplete ? 'bg-white border-gray-200 hover:bg-blue-50' : 'bg-gray-50 border-dashed border-gray-300 hover:border-blue-400' }}">
        
        <div class="flex justify-between items-start mb-4">
            {{-- Lingkaran Nomor Klausul --}}
            <div class="w-12 h-12 rounded-full flex items-center justify-center font-extrabold text-xl transition-transform group-hover:scale-110 {{ $isComplete ? 'bg-blue-100 text-blue-600' : 'bg-gray-200 text-gray-500' }}">
                {{ $key }}
            </div>

            {{-- Badge Status --}}
            @if($isComplete)
                <span class="text-[10px] font-bold px-2 py-1 rounded bg-green-100 text-green-700 uppercase">
                    100% Selesai
                </span>
            @else
                <span class="text-[10px] font-bold px-2 py-1 rounded bg-amber-100 text-amber-700 uppercase">
                    {{ $persenProgres }}% Terisi
                </span>
            @endif
        </div>
        
        {{-- Judul & Deskripsi --}}
        <h4 class="text-lg font-bold text-gray-800 mb-1 group-hover:text-blue-700">
            {{ $titles[$key] ?? 'Klausul '.$key }}
        </h4>
        <p class="text-xs text-gray-500 mb-4">
            Mencakup: {{ implode(', ', $subClauses) }}
        </p>

        {{-- Progress Bar Kecil --}}
        <div class="w-full bg-gray-200 rounded-full h-1.5 mb-2">
            <div class="h-1.5 rounded-full {{ $isComplete ? 'bg-green-500' : 'bg-blue-500' }}" style="width: {{ $persenProgres }}%"></div>
        </div>
        
        {{-- Info Detail (Bahasa Indonesia) --}}
        <div class="flex justify-between items-center text-[10px] font-medium text-gray-400">
            <span>{{ $sudahDikerjakan }} dari {{ $totalSoal }} Soal</span>
            @if($mStats['unanswered'] > 0)
                <span class="text-amber-600 font-bold">{{ $mStats['unanswered'] }} Belum Diisi</span>
            @endif
        </div>
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
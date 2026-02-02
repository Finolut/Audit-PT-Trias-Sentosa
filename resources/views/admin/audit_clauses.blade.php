@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    {{-- HEADER --}}
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

{{-- 📋 RINGKASAN DATA (KOTAK BIRU) --}}
<div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200 shadow-sm">

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
            <div class="flex">
                <span class="w-44 font-bold text-gray-600">Kode Pemeriksaan</span>
                <span class="mr-2">:</span>
                <span class="text-gray-800">{{ $audit->audit_code ?? '-' }}</span>
            </div>
            <div class="flex">
                <span class="w-44 font-bold text-gray-600">Tanggal Jadwal</span>
                <span class="mr-2">:</span>
                <span class="text-gray-800">{{ $audit->scheduled_date ? $audit->scheduled_date->format('d F Y') : '-' }}</span>
            </div>
        </div>

        <div class="space-y-3">
            <div class="flex">
                <span class="w-44 font-bold text-gray-600">Auditor</span>
                <span class="mr-2">:</span>
                <span class="text-gray-800 font-medium">{{ $leadAuditor['name'] ?? '-' }}</span>
            </div>
            <div class="flex">
                <span class="w-44 font-bold text-gray-600">Anggota</span>
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
            <div class="flex">
                <span class="w-44 font-bold text-gray-600">Metodologi</span>
                <span class="mr-2">:</span>
                <span class="text-gray-800">
                    @if($audit->methodology)
                        {{ implode(', ', json_decode($audit->methodology, true)) }}
                    @else
                        -
                    @endif
                </span>
            </div>
            <div class="flex">
                <span class="w-44 font-bold text-gray-600">Standar</span>
                <span class="mr-2">:</span>
                <span class="text-gray-800">
                    @if($audit->standards)
                        {{ implode(', ', json_decode($audit->standards, true)) }}
                    @else
                        -
                    @endif
                </span>
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
            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Gambaran Per Klausul Utama</h3>
            <div class="h-64">
                <canvas id="mainClauseChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Rincian Lengkap (Semua Klausus)</h3>
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

{{-- SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. Inisialisasi Chart (Pastikan data PHP tersedia)
    const mainStats = {!! json_encode($mainStats) !!};
    const detailedStats = {!! json_encode($detailedStats) !!};

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'top' } },
        scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } }
    };

    // Fungsi pembantu untuk buat dataset
    function createDatasets(statsObj, labels) {
        return [
            { label: 'Yes', data: labels.map(l => statsObj[l].yes), backgroundColor: '#22c55e' },
            { label: 'No', data: labels.map(l => statsObj[l].no), backgroundColor: '#ef4444' },
            { label: 'N/A', data: labels.map(l => statsObj[l].na), backgroundColor: '#facc15' },
            { label: 'Belum', data: labels.map(l => statsObj[l].unanswered), backgroundColor: '#e2e8f0' }
        ];
    }

    // Render Chart 1
    const mainLabels = Object.keys(mainStats);
    new Chart(document.getElementById('mainClauseChart'), {
        type: 'bar',
        data: { labels: mainLabels.map(l => 'Clause ' + l), datasets: createDatasets(mainStats, mainLabels) },
        options: commonOptions
    });

    // Render Chart 2
    const detailedLabels = Object.keys(detailedStats);
    new Chart(document.getElementById('detailedClauseChart'), {
        type: 'bar',
        data: { labels: detailedLabels, datasets: createDatasets(detailedStats, detailedLabels) },
        options: commonOptions
    });

    // 2. LOGIC LOADING (SweetAlert)
    document.addEventListener('DOMContentLoaded', function() {
        const clauseButtons = document.querySelectorAll('.btn-clause');
        
        clauseButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Jangan jalankan jika user klik kanan atau ctrl+klik
                if (e.ctrlKey || e.metaKey || e.button !== 0) return;

                Swal.fire({
                    title: 'Memuat Klausul...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        });
    });
</script>
@endsection
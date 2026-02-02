@extends('layouts.admin')

@section('content')
{{-- HEADER & NAVIGATION --}}
<div class="sticky top-0 z-40 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm -mx-4 sm:-mx-6 lg:-mx-8 !-mt-18 mb-8 transition-all">
    <div class="w-full flex items-center justify-between px-8 lg:px-12 py-4">
        <div>
            <a href="{{ route('admin.audit.overview', $audit->id) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Menu Utama
            </a>
            <h2 class="text-2xl font-bold text-gray-800 mt-1">Main Clause {{ $mainClause }}</h2>
        </div>
        
{{-- Mini Stats Summary --}}
<div class="flex gap-3">
    <div class="px-3 py-1.5 bg-green-50 text-green-700 rounded-md text-xs font-semibold flex items-center gap-1 border border-green-100">
        <span class="w-3 h-3 bg-green-500 rounded-full"></span>
        <div class="leading-tight">SESUAI<br><span class="text-lg font-bold">{{ $totalYes }}</span></div>
    </div>

    <div class="px-3 py-1.5 bg-red-50 text-red-700 rounded-md text-xs font-semibold flex items-center gap-1 border border-red-100">
        <span class="w-3 h-3 bg-red-500 rounded-full"></span>
        <div class="leading-tight">TIDAK<br><span class="text-lg font-bold">{{ $totalNo }}</span></div>
    </div>
    <div class="px-3 py-1.5 bg-yellow-50 text-yellow-700 rounded-md text-xs font-semibold flex items-center gap-1 border border-yellow-100">
        <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
        <div class="leading-tight">N/A<br><span class="text-lg font-bold">{{ $totalNA }}</span></div>
    </div>
    <div class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-md text-xs font-semibold flex items-center gap-1 border border-blue-100">
        <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
        <div class="leading-tight">BELUM<br><span class="text-lg font-bold">{{ $totalUnanswered }}</span></div>
    </div>
</div>
    </div>
</div>

{{-- CHART SECTION --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Overall Performance</h3>
            <div class="relative h-60">
                <canvas id="clauseChart"></canvas>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Breakdown per Sub-Clause</h3>
            <div class="relative h-60">
                <canvas id="stackedBarChart"></canvas>
            </div>
        </div>
    </div>
</div>

@foreach($subCodes as $code)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Sub-Clause</span>
                        <h3 class="text-lg font-bold text-gray-800 mt-0.5">{{ $code }} - {{ $subClauseTitles[$code] ?? 'Detail' }}</h3>
                    </div>
                </div>
            </div>

            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-1/2">Pertanyaan / Item</th>
                                <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Maturity</th>
                                <th class="px-3 py-2 text-center text-xs font-semibold text-green-600 uppercase">Yes</th>
                                <th class="px-3 py-2 text-center text-xs font-semibold text-red-600 uppercase">No</th>
                                <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Result</th>
                            </tr>
                        </thead>
<tbody class="divide-y divide-gray-100 bg-white">
@php 
    $subItems = $itemsGrouped[$code] ?? collect(); 
    $currentMaturity = null;
@endphp

@forelse($subItems as $item)
    @if($currentMaturity !== $item->level_number)
        <tr class="bg-blue-50/20">
            <td colspan="5" class="px-4 py-1.5 text-[10px] font-medium text-blue-700 uppercase tracking-wide border-t border-blue-100">
                Maturity Level {{ $item->level_number }}
            </td>
        </tr>
        @php $currentMaturity = $item->level_number; @endphp
    @endif

    @php
        $final = $item->answerFinals->first();
        $yesCount = $final->yes_count ?? 0;
        $noCount  = $final->no_count ?? 0;
        $finalYes = $final->final_yes ?? 0;
        $finalNo  = $final->final_no ?? 0;
        $isUnanswered = !$final || ($yesCount === 0 && $noCount === 0);
    @endphp

    {{-- ROW SOAL --}}
    <tr class="hover:bg-gray-50 transition-colors {{ $isUnanswered ? 'bg-gray-50/50' : '' }}">
        <td class="px-4 py-3 text-gray-700 leading-snug">
            {{ $item->item_text }}
        </td>
        <td class="px-3 py-3 text-center">
            <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-blue-600 bg-blue-100 rounded-full">
                {{ $item->level_number }}
            </span>
        </td>
 <td class="px-3 py-3 text-center font-medium {{ $isUnanswered ? 'text-gray-300' : 'text-green-600' }}">
    {{ $isUnanswered ? '-' : $yesCount }}
</td>
<td class="px-3 py-3 text-center font-medium {{ $isUnanswered ? 'text-gray-300' : 'text-red-600' }}">
    {{ $isUnanswered ? '-' : $noCount }}
</td>

<td class="px-3 py-3 text-center">
    @if($isUnanswered)
        <span class="px-2 py-0.5 text-[10px] font-medium text-blue-700 bg-blue-100 rounded border border-blue-200">
            BELUM DIJAWAB
        </span>
    @elseif($finalYes > $finalNo)
        <span class="px-2 py-0.5 text-[10px] font-medium text-green-700 bg-green-100 rounded border border-green-200">
            SESUAI
        </span>
    @elseif($finalNo > $finalYes)
        <span class="px-2 py-0.5 text-[10px] font-medium text-red-700 bg-red-100 rounded border border-red-200">
            TIDAK
        </span>
    @endif
</td>

    </tr>

    {{-- ✅ ROW TEMUAN (Jika Ada) --}}
    @if(!empty(trim($item->finding_note)))
        <tr class="bg-red-50/20 border-t border-red-100">
            <td colspan="5" class="px-4 py-2">
                <div class="flex items-start gap-2">
                    <p class="text-sm text-red-800 bg-red-50 p-2 rounded-md border border-red-100 whitespace-pre-wrap">
                        <span class="font-semibold">Temuan:</span> {{ $item->finding_note }}
                    </p>
                </div>
            </td>
        </tr>
    @endif
@empty
    <tr><td colspan="5" class="px-4 py-4 text-center italic text-gray-400">Data kosong</td></tr>
@endforelse
</tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxDonut = document.getElementById('clauseChart').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
          labels: ['Sesuai', 'Tidak Sesuai', 'N/A', 'Belum Dijawab'],
            datasets: [{
                data: [{{ $totalYes }}, {{ $totalNo }}, {{ $totalNA }}, {{ $totalUnanswered }}],
                backgroundColor: ['#22c55e', '#ef4444', '#facc15', '#e2e8f0'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: { legend: { position: 'bottom' } }
        }
    });

    const rawData = {!! json_encode($stackedChartData) !!};
    const labels = Object.keys(rawData);
const dataYes = labels.map(k => rawData[k].yes);
const dataNo = labels.map(k => rawData[k].no);
const dataNA = labels.map(k => rawData[k].na);
const dataUnanswered = labels.map(k => rawData[k].unanswered);


    const ctxBar = document.getElementById('stackedBarChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
    { label: 'Sesuai', data: dataYes, backgroundColor: '#22c55e', barPercentage: 0.6 },
    { label: 'Tidak Sesuai', data: dataNo, backgroundColor: '#ef4444', barPercentage: 0.6 },
    { label: 'N/A', data: dataNA, backgroundColor: '#facc15', barPercentage: 0.6 },
    { label: 'Belum Dijawab', data: dataUnanswered, backgroundColor: '#e2e8f0', barPercentage: 0.6 }
]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { stacked: true, grid: { display: false } },
                y: { stacked: true, beginAtZero: true }
            },
            plugins: {
                legend: { position: 'top', align: 'end' },
                tooltip: { mode: 'index', intersect: false }
            }
        }
    });
</script>
@endsection
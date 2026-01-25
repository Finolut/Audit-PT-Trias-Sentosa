@extends('layouts.admin')

@section('content')
    {{-- HEADER & NAVIGATION --}}
    {{-- Tambahkan -mx-4 atau -mx-6 tergantung padding pembungkus di layouts.admin --}}
    {{-- -mt-6 atau -mt-8 digunakan untuk menariknya ke atas agar benar-benar menempel --}}
   <div class="sticky top-0 z-30 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm -mx-4 sm:-mx-6 lg:-mx-8 !-mt-6 sm:!-mt-8 mb-8">
    <div class="w-full flex items-center justify-between px-6 lg:px-30 py-4">
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
                <div class="px-3 py-1.5 bg-green-50 text-green-700 rounded-md text-xs font-semibold flex items-center gap-1">
                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    <div>SESUAI<br><span class="text-lg font-bold">{{ $totalYes }}</span></div>
                </div>
                <div class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-md text-xs font-semibold flex items-center gap-1">
                    <span class="w-3 h-3 bg-gray-400 rounded-full"></span>
                    <div>PARTIAL<br><span class="text-lg font-bold">{{ $totalDraw }}</span></div>
                </div>
                <div class="px-3 py-1.5 bg-red-50 text-red-700 rounded-md text-xs font-semibold flex items-center gap-1">
                    <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                    <div>TIDAK<br><span class="text-lg font-bold">{{ $totalNo }}</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART SECTION --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Doughnut Chart (Ringkasan) --}}
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Overall Performance</h3>
                <div class="relative h-60">
                    <canvas id="clauseChart"></canvas>
                </div>
            </div>

            {{-- Stacked Bar Chart (Per Sub-Clause) --}}
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Breakdown per Sub-Clause</h3>
                <div class="relative h-60">
                    <canvas id="stackedBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- LOOPING SUB-CLAUSES --}}
    @foreach($subCodes as $code)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                {{-- Header Sub-Klausul --}}
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Sub-Clause</span>
                            <h3 class="text-lg font-bold text-gray-800 mt-0.5">{{ $code }} - {{ $subClauseTitles[$code] ?? 'Detail' }}</h3>
                        </div>
                    </div>
                </div>

                <div class="p-0">
                    {{-- TABEL --}}
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
                                    // PERBAIKAN LOGIKA: Ambil dari relation answerFinals
                                    $final = $item->answerFinals->first();

                                    $yesCount = $final->yes_count ?? 0;
                                    $noCount  = $final->no_count ?? 0;
                                    $finalYes = $final->final_yes ?? 0;
                                    $finalNo  = $final->final_no ?? 0;

                                    // Logika N/A
                                    $isNA = (!$final || ($yesCount == 0 && $noCount == 0)); 
                                @endphp

                                <tr class="hover:bg-gray-50 transition-colors {{ $isNA ? 'bg-gray-50/50' : '' }}">
                                    <td class="px-4 py-3 text-gray-700 leading-snug">
                                        {{ $item->item_text }}
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-blue-600 bg-blue-100 rounded-full">
                                            {{ $item->level_number }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 text-center font-medium {{ $isNA ? 'text-gray-300' : 'text-green-600' }}">
                                        {{ $isNA ? '-' : $yesCount }}
                                    </td>
                                    <td class="px-3 py-3 text-center font-medium {{ $isNA ? 'text-gray-300' : 'text-red-600' }}">
                                        {{ $isNA ? '-' : $noCount }}
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        @if($isNA)
                                            <span class="px-2 py-0.5 text-[10px] font-medium text-yellow-700 bg-yellow-100 rounded border border-yellow-200">N/A</span>
                                        @elseif($finalYes > $finalNo)
                                            <span class="px-2 py-0.5 text-[10px] font-medium text-green-700 bg-green-100 rounded border border-green-200">SESUAI</span>
                                        @elseif($finalNo > $finalYes)
                                            <span class="px-2 py-0.5 text-[10px] font-medium text-red-700 bg-red-100 rounded border border-red-200">TIDAK</span>
                                        @else
                                            <span class="px-2 py-0.5 text-[10px] font-medium text-gray-600 bg-gray-100 rounded border border-gray-200">PARTIAL</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-4 py-4 text-center italic text-gray-400">Data kosong</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- BOX CATATAN AUDITOR --}}
                    <div class="border-t border-gray-100 p-5 bg-gray-50">
                        <h4 class="text-xs font-semibold text-gray-700 uppercase mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Catatan Auditor ({{ $code }})
                        </h4>
                        @if(!empty($auditorNotes[$code]))
                            <div class="bg-white border border-gray-200 rounded-md p-3 shadow-sm">
                                <p class="text-gray-800 text-sm whitespace-pre-wrap">{{ $auditorNotes[$code] }}</p>
                            </div>
                        @else
                            <p class="text-gray-400 text-sm italic">Tidak ada catatan.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. DOUGHNUT CHART
        const ctxDonut = document.getElementById('clauseChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Sesuai', 'Partial', 'Tidak Sesuai', 'N/A'],
                datasets: [{
                    data: [{{ $totalYes }}, {{ $totalDraw }}, {{ $totalNo }}, {{ $totalNA }}],
                    backgroundColor: ['#22c55e', '#94a3b8', '#ef4444', '#facc15'],
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

        // 2. STACKED BAR CHART (Upgrade Visual)
        const rawData = {!! json_encode($stackedChartData) !!};
        const labels = Object.keys(rawData);
        
        const dataYes = labels.map(k => rawData[k].yes);
        const dataNo = labels.map(k => rawData[k].no);
        const dataPartial = labels.map(k => rawData[k].partial);
        const dataNA = labels.map(k => rawData[k].na);

        const ctxBar = document.getElementById('stackedBarChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Sesuai', data: dataYes, backgroundColor: '#22c55e', barPercentage: 0.6 },
                    { label: 'Partial', data: dataPartial, backgroundColor: '#94a3b8', barPercentage: 0.6 },
                    { label: 'Tidak Sesuai', data: dataNo, backgroundColor: '#ef4444', barPercentage: 0.6 },
                    { label: 'N/A', data: dataNA, backgroundColor: '#facc15', barPercentage: 0.6 }
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
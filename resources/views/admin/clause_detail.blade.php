@extends('layouts.admin')

@section('content')
    {{-- HEADER & NAVIGATION --}}
    <div class="sticky top-0 z-30 bg-gray-100/95 backdrop-blur py-4 mb-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('audit.overview', $audit->id) }}" class="text-sm text-gray-500 hover:text-blue-600 font-bold">
                    ← Kembali ke Menu Utama
                </a>
                <h2 class="text-2xl font-extrabold text-gray-800 mt-1">
                    Main Clause {{ $mainClause }}
                </h2>
            </div>
            
            {{-- Mini Stats Summary --}}
            <div class="flex gap-2">
                <div class="px-3 py-1 bg-green-100 text-green-700 rounded text-xs font-bold text-center">
                    YES<br><span class="text-lg">{{ $totalYes }}</span>
                </div>
                <div class="px-3 py-1 bg-red-100 text-red-700 rounded text-xs font-bold text-center">
                    NO<br><span class="text-lg">{{ $totalNo }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- GLOBAL CHART SECTION (Untuk Seluruh Main Clause) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
        {{-- Doughnut Chart --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 text-center">Overall Performance (Clause {{ $mainClause }})</h3>
            <div class="relative h-48">
                <canvas id="clauseChart"></canvas>
            </div>
        </div>

        {{-- Line Chart --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Trend Jawaban per Item</h3>
            <div class="relative h-48">
                <canvas id="lineTrendChart"></canvas>
            </div>
        </div>
    </div>

    {{-- LOOPING SUB-CLAUSES (4.1, 4.2, dst) --}}
    @foreach($subCodes as $code)
        <div class="mb-10 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" id="clause-{{ str_replace('.', '-', $code) }}">
            
            {{-- Header Sub-Klausul --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4 flex justify-between items-center">
                <div>
                    <span class="text-blue-100 font-bold text-xs uppercase tracking-widest">Sub-Clause</span>
                    <h3 class="text-white text-xl font-bold">{{ $code }} - {{ $subClauseTitles[$code] ?? 'Detail' }}</h3>
                </div>
            </div>

            <div class="p-0">
                {{-- TABEL --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase w-1/2">Pertanyaan / Item</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase">Maturity</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-green-600 uppercase">Yes</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-red-600 uppercase">No</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase">Result</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @php 
                                $subItems = $itemsGrouped[$code] ?? collect(); 
                                $currentMaturity = null;
                            @endphp

                            @forelse($subItems as $item)
                                {{-- Separator Maturity Level --}}
                                @if($currentMaturity !== $item->level_number)
                                    <tr class="bg-blue-50/50">
                                        <td colspan="5" class="px-6 py-2 text-[11px] font-bold text-blue-800 uppercase tracking-wide border-t border-blue-100">
                                            Maturity Level {{ $item->level_number }}
                                        </td>
                                    </tr>
                                    @php $currentMaturity = $item->level_number; @endphp
                                @endif

                                @php
                                    $final = $item->answerFinals->first();
                                    $yes = $final->yes_count ?? 0;
                                    $no = $final->no_count ?? 0;
                                    $isNA = ($final && $yes == 0 && $no == 0);
                                @endphp

                                <tr class="hover:bg-gray-50 transition-colors {{ $isNA ? 'bg-gray-50/50' : '' }}">
                                    <td class="px-6 py-3 text-sm text-gray-700 leading-snug">
                                        {{ $item->item_text }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-blue-600 bg-blue-100 rounded-full">
                                            {{ $item->level_number }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold {{ $isNA ? 'text-gray-300' : 'text-green-600' }}">
                                        {{ $isNA ? '-' : $yes }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold {{ $isNA ? 'text-gray-300' : 'text-red-600' }}">
                                        {{ $isNA ? '-' : $no }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($isNA)
                                            <span class="px-2 py-1 text-[10px] font-bold text-yellow-700 bg-yellow-100 rounded border border-yellow-200">N/A</span>
                                        @elseif($final && $final->final_yes > $final->final_no)
                                            <span class="px-2 py-1 text-[10px] font-bold text-green-700 bg-green-100 rounded border border-green-200">SESUAI</span>
                                        @elseif($final && $final->final_no > $final->final_yes)
                                            <span class="px-2 py-1 text-[10px] font-bold text-red-700 bg-red-100 rounded border border-red-200">TIDAK</span>
                                        @elseif($final)
                                            <span class="px-2 py-1 text-[10px] font-bold text-gray-600 bg-gray-100 rounded border border-gray-200">PARTIAL</span>
                                        @else
                                            <span class="text-xs text-gray-300 italic">Empty</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-400 italic">Data item belum dikonfigurasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- BOX CATATAN AUDITOR KHUSUS SUB-KLAUSUL INI --}}
                <div class="bg-amber-50 border-t border-amber-100 p-6">
                    <h4 class="text-sm font-bold text-amber-800 uppercase mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Catatan Auditor ({{ $code }})
                    </h4>
                    @if(!empty($auditorNotes[$code]))
                        <div class="bg-white border border-amber-200 rounded p-3 shadow-sm">
                            <p class="text-gray-800 text-sm whitespace-pre-wrap">{{ $auditorNotes[$code] }}</p>
                        </div>
                    @else
                        <p class="text-gray-400 text-sm italic">Tidak ada catatan.</p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. DOUGHNUT CHART (Summary Global Main Clause)
        const ctxDonut = document.getElementById('clauseChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Sesuai', 'Tidak Sesuai', 'Partial', 'N/A'],
                datasets: [{
                    data: [{{ $totalYes }}, {{ $totalNo }}, {{ $totalDraw }}, {{ $totalNA }}],
                    backgroundColor: ['#22c55e', '#ef4444', '#94a3b8', '#facc15'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { position: 'right', labels: { boxWidth: 10, usePointStyle: true } } }
            }
        });

        // 2. LINE CHART (Trend Item Global)
        const ctxTrend = document.getElementById('lineTrendChart').getContext('2d');
        const gradient = ctxTrend.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: {!! json_encode($items->map(fn($i, $k) => ($k+1))) !!}, // Label angka saja biar rapi
                datasets: [{
                    label: 'Status Item',
                    data: {!! json_encode($chartData) !!}, 
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: {!! json_encode($chartData->map(fn($v) => match($v){ 1=>'#22c55e', -1=>'#ef4444', 0=>'#9ca3b8', default=>'#facc15' })) !!}
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { display: false, min: -1.5, max: 1.5 },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });
    </script>
@endsection
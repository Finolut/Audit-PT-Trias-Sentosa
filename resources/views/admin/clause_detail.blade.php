@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('audit.overview', $audit->id) }}" class="text-sm text-gray-500 hover:underline">← Kembali ke Daftar Klausul</a>
            <h2 class="text-2xl font-bold mt-1">Klausul {{ $clause->clause_code }}: {{ $clause->title }}</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- SUMMARY STATUS --}}
        <div class="bg-white p-6 rounded-lg shadow lg:col-span-1">
            <h3 class="text-lg font-bold text-gray-700 mb-4 text-center">Summary Status</h3>
            <div class="relative h-64">
                <canvas id="clauseChart"></canvas>
            </div>
            <div class="mt-4 grid grid-cols-4 gap-2 text-center text-[10px]">
                <div class="p-2 bg-green-50 rounded">
                    <p class="text-green-600 font-bold text-lg">{{ $totalYes }}</p>
                    <p class="text-gray-500 uppercase font-semibold">Ya</p>
                </div>
                <div class="p-2 bg-red-50 rounded">
                    <p class="text-red-600 font-bold text-lg">{{ $totalNo }}</p>
                    <p class="text-gray-500 uppercase font-semibold">Tidak</p>
                </div>
                <div class="p-2 bg-gray-100 rounded">
                    <p class="text-gray-600 font-bold text-lg">{{ $totalDraw }}</p>
                    <p class="text-gray-500 uppercase font-semibold">Sebagian</p>
                </div>
                <div class="p-2 bg-yellow-50 rounded">
                    <p class="text-yellow-600 font-bold text-lg">{{ $totalNA }}</p>
                    <p class="text-gray-500 uppercase font-semibold">N/A</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <div class="bg-amber-50 px-6 py-4 border-b border-amber-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <h3 class="text-lg font-bold text-amber-800">Catatan & Pertanyaan Auditor</h3>
        </div>
        <div class="p-6">
            @if($auditorQuestion)
                <div class="bg-gray-50 border-l-4 border-amber-400 p-4 rounded shadow-sm">
                    <p class="text-gray-700 whitespace-pre-wrap leading-relaxed italic">"{{ $auditorQuestion }}"</p>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-gray-400 text-sm italic">Tidak ada catatan atau pertanyaan tambahan dari auditor untuk klausul ini.</p>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        </div>

        

        {{-- TABEL DETAIL --}}
        <div class="bg-white rounded-lg shadow lg:col-span-2 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Soal</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase w-24">Maturity</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase w-16">Yes</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase w-16">No</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase w-24">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $currentMaturity = null; @endphp
                        @foreach($items as $item)
                            @if($currentMaturity !== $item->maturityLevel->level_number)
                                <tr class="bg-blue-50">
                                    <td colspan="5" class="px-4 py-1 text-[10px] font-bold text-blue-600 uppercase">
                                        Maturity Level {{ $item->maturityLevel->level_number }}
                                    </td>
                                </tr>
                                @php $currentMaturity = $item->maturityLevel->level_number; @endphp
                            @endif

                            @php
                                $final = $item->answerFinals->first();
                                $yesCount = $final->yes_count ?? 0;
                                $noCount = $final->no_count ?? 0;
                                
                                // Deteksi N/A Mutlak (Jika record ada tapi count 0)
                                $isNA = ($final && $yesCount == 0 && $noCount == 0);
                            @endphp
                            <tr class="hover:bg-gray-50 {{ $isNA ? 'bg-gray-50/50' : '' }}">
                                <td class="px-4 py-3 text-sm text-gray-800">{{ $item->item_text }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-xs font-bold text-blue-500">Lvl {{ $item->maturityLevel->level_number }}</span>
                                </td>
                                <td class="px-4 py-3 text-center font-bold {{ $isNA ? 'text-gray-300' : 'text-green-600' }}">
                                    {{ $isNA ? '-' : $yesCount }}
                                </td>
                                <td class="px-4 py-3 text-center font-bold {{ $isNA ? 'text-gray-300' : 'text-red-600' }}">
                                    {{ $isNA ? '-' : $noCount }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($isNA)
                                        <span class="text-[10px] font-bold text-yellow-700 bg-yellow-100 px-2 py-1 rounded">N/A</span>
                                    @elseif($final && $final->final_yes > $final->final_no)
                                        <span class="text-[10px] font-bold text-green-600 bg-green-100 px-2 py-1 rounded">Sesuai</span>
                                    @elseif($final && $final->final_no > $final->final_yes)
                                        <span class="text-[10px] font-bold text-red-600 bg-red-100 px-2 py-1 rounded">Tidak Sesuai</span>
                                    @elseif($final)
                                        <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded">Sebagian</span>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-300 italic px-2 py-1">Belum diisi</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- LINE CHART --}}
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-700">Dalam Bentuk Diagram Garis</h3>
            <div class="text-[10px] flex gap-3">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span> Ya</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-gray-400"></span> Sebagian</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500"></span> Tidak</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-yellow-500"></span> N/A</span>
            </div>
        </div>
        <div class="h-72">
            <canvas id="lineTrendChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. DOUGHNUT CHART
        const ctxDonut = document.getElementById('clauseChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Ya', 'Tidak', 'Sebagian', 'N/A'],
                datasets: [{
                    data: [{{ $totalYes }}, {{ $totalNo }}, {{ $totalDraw }}, {{ $totalNA }}],
                    backgroundColor: ['#22c55e', '#ef4444', '#9ca3af', '#facc15'],
                    borderWidth: 2
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } }
            }
        });

        // 2. LINE TREND CHART
        const ctxTrend = document.getElementById('lineTrendChart').getContext('2d');
        const gradient = ctxTrend.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(34, 197, 94, 0.1)');
        gradient.addColorStop(1, 'rgba(239, 68, 68, 0.1)');

        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: {!! json_encode($items->map(fn($i, $key) => 'Soal ' . ($key + 1))) !!},
                datasets: [{
                    label: 'Status',
                    data: {!! json_encode($chartData) !!}, 
                    borderColor: '#4f46e5',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: {!! json_encode($chartData->map(function($val) {
                        if($val === 1) return '#22c55e';
                        if($val === -1) return '#ef4444';
                        if($val === 0) return '#9ca3af';
                        return '#facc15'; // Titik Kuning untuk N/A
                    })) !!},
                    pointRadius: 6,
                    spanGaps: true // Garis tetap menyambung meski ada data null
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        min: -1.5,
                        max: 1.5,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                if (value === 1) return 'YES';
                                if (value === 0) return 'DRAW';
                                if (value === -1) return 'NO';
                                return '';
                            }
                        },
                        grid: {
                            color: (context) => context.tick.value === 0 ? '#94a3b8' : '#f1f5f9',
                            lineWidth: (context) => context.tick.value === 0 ? 2 : 1
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const val = context.raw;
                                if (val === 1) return 'Status: YES (Sesuai)';
                                if (val === -1) return 'Status: NO (Tidak Sesuai)';
                                if (val === 0) return 'Status: Sebagian / Seri';
                                if (val === null) return 'Status: N/A (Mutlak)';
                                return 'Status: Belum Terisi';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
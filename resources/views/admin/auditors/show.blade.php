@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    
    <div class="flex items-center gap-4 mb-2">
        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Profil & Riwayat Auditor</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col items-center text-center lg:col-span-1">
            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-3xl font-bold mb-4">
                {{ substr($auditor->name, 0, 2) }}
            </div>
            <h3 class="text-xl font-bold text-gray-800">{{ $auditor->name }}</h3>
            <p class="text-gray-500 text-sm">{{ $auditor->department }}</p>
            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-full mt-2">NIK: {{ $auditor->nik }}</span>
        </div>

<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 lg:col-span-2">
    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Statistik Kinerja</h4>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="p-4 bg-blue-50 rounded-lg border border-blue-100 text-center">
            <div class="text-3xl font-bold text-blue-700">{{ $stats['total'] }}</div>
            <div class="text-xs text-blue-600 mt-1">Total Audit</div>
        </div>
        <div class="p-4 bg-green-50 rounded-lg border border-green-100 text-center">
            <div class="text-3xl font-bold text-green-700">{{ $stats['first_party'] ?? 0 }}</div>
            <div class="text-xs text-green-600 mt-1">Audit Internal</div>
        </div>
        <div class="p-4 bg-orange-50 rounded-lg border border-orange-100 text-center">
            <div class="text-3xl font-bold text-orange-700">{{ $stats['follow_up'] ?? 0 }}</div>
            <div class="text-xs text-orange-600 mt-1">Tindak Lanjut</div>
        </div>
        <div class="p-4 bg-purple-50 rounded-lg border border-purple-100 text-center">
            <div class="text-3xl font-bold text-purple-700">{{ $stats['investigative'] ?? 0 }}</div>
            <div class="text-xs text-purple-600 mt-1">Audit Khusus</div>
        </div>
        <div class="p-4 bg-red-50 rounded-lg border border-red-100 text-center">
            <div class="text-3xl font-bold text-red-700">{{ $stats['unannounced'] ?? 0 }}</div>
            <div class="text-xs text-red-600 mt-1">Audit Mendadak</div>
        </div>
    </div>
</div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-gray-800 text-lg">Riwayat Pemeriksaan</h3>
        </div>
        
        <div class="divide-y divide-gray-100">
            @forelse($history as $audit)
                <div id="audit-{{ $audit->id }}" 
                     class="p-6 transition-all duration-1000 {{ session('highlight_audit') == $audit->id ? 'bg-yellow-50 border-l-4 border-yellow-400' : 'hover:bg-gray-50' }}">
                    
                    <div class="flex flex-col md:flex-row justify-between md:items-start gap-4">
                        <div class="flex-1">

                            <div class="text-[10px] font-mono text-gray-400 mb-1 uppercase">ID: {{ $audit->id }}</div>
                        
<div class="flex items-center gap-3 mb-3">
    @php
        $typeLabels = [
            'first party' => ['label' => 'Audit Internal', 'color' => 'bg-green-100 text-green-700'],
            'follow up' => ['label' => 'Tindak Lanjut', 'color' => 'bg-orange-100 text-orange-700'],
            'investigative' => ['label' => 'Audit Khusus', 'color' => 'bg-purple-100 text-purple-700'],
            'unannounced' => ['label' => 'Audit Mendadak', 'color' => 'bg-red-100 text-red-700'],
            'default' => ['label' => 'Lainnya', 'color' => 'bg-gray-100 text-gray-700']
        ];
        $typeKey = $audit->type ?? 'default';
        $typeConfig = $typeLabels[$typeKey] ?? $typeLabels['default'];
    @endphp
    <span class="px-2.5 py-1 rounded text-xs font-bold {{ $typeConfig['color'] }}">
        {{ $typeConfig['label'] }}
    </span>
    <span class="text-sm text-gray-400">
        {{ \Carbon\Carbon::parse($audit->audit_date ?? $audit->created_at)->translatedFormat('d F Y') }}
    </span>
</div>

                            <div class="space-y-2">
                                @if($audit->audit_code)
                                    <div class="text-sm text-gray-600">
                                        <span class="font-semibold text-gray-800">Kode Audit:</span> 
                                        {{ $audit->audit_code }}
                                    </div>
                                @endif

                                @if($audit->objective)
                                    <div class="text-sm text-gray-600">
                                        <span class="font-semibold text-gray-800">Tujuan:</span> 
                                        {{ $audit->objective }}
                                    </div>
                                @endif

                                @if($audit->standards)
                                    <div class="text-sm text-gray-600">
                                        <span class="font-semibold text-gray-800">Standar:</span> 
                                        {{ is_string($audit->standards) ? implode(', ', json_decode($audit->standards, true)) : $audit->standards }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col items-end">
                            @php
                                $currentStatus = strtoupper($audit->status ?? '');
                            @endphp
                            @if(in_array($currentStatus, ['COMPLETED', 'COMPLETE']))
                                <span class="text-green-600 text-sm font-bold bg-green-50 px-3 py-1 rounded-full border border-green-100 shadow-sm">
                                    ✅ SELESAI
                                </span>
                            @else
                                <span class="text-amber-600 text-sm font-bold bg-amber-50 px-3 py-1 rounded-full border border-amber-100 shadow-sm">
                                    ⏳ PROSES
                                </span>
                            @endif

                            <a href="{{ route('admin.audit.overview', $audit->id) }}" 
                               class="mt-2 text-xs text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1">
                                Detail Laporan 
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-gray-400">
                    Belum ada riwayat audit.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
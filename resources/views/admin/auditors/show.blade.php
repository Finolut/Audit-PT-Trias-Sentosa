@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    
    {{-- Header & Navigasi --}}
    <div class="flex items-center gap-4 mb-2">
        <a href="{{ route('admin.auditors.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Profil & Riwayat Auditor</h2>
    </div>

    {{-- KARTU PROFIL & STATISTIK --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col items-center text-center lg:col-span-1">
            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-3xl font-bold mb-4">
                {{ substr($auditor->name, 0, 2) }}
            </div>
            <h3 class="text-xl font-bold text-gray-800">{{ $auditor->name }}</h3>
            <p class="text-gray-500 text-sm mb-1">{{ $auditor->department }}</p>
            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-mono mt-2">NIK: {{ $auditor->nik }}</span>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 lg:col-span-2">
            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Statistik Kinerja</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="text-3xl font-bold text-blue-700">{{ $stats['total'] }}</div>
                    <div class="text-xs text-blue-600 font-medium mt-1">Total Audit</div>
                </div>
                <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                    <div class="text-3xl font-bold text-green-700">{{ $stats['regular'] }}</div>
                    <div class="text-xs text-green-600 font-medium mt-1">Rutin</div>
                </div>
                <div class="p-4 bg-purple-50 rounded-lg border border-purple-100">
                    <div class="text-3xl font-bold text-purple-700">{{ $stats['special'] }}</div>
                    <div class="text-xs text-purple-600 font-medium mt-1">Khusus/Mendadak</div>
                </div>
                <div class="p-4 bg-orange-50 rounded-lg border border-orange-100">
                    <div class="text-3xl font-bold text-orange-700">{{ $stats['followup'] }}</div>
                    <div class="text-xs text-orange-600 font-medium mt-1">Follow Up</div>
                </div>
            </div>
        </div>
    </div>

    {{-- TIMELINE HISTORY AUDIT --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-gray-800 text-lg">Riwayat Pemeriksaan (Log Aktivitas)</h3>
        </div>
        
        <div class="divide-y divide-gray-100">
            @forelse($history as $audit)
            <div class="p-6 hover:bg-gray-50 transition-all">
                <div class="flex flex-col md:flex-row justify-between md:items-start gap-4">
                    
                    {{-- Kiri: Detail Audit --}}
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-2.5 py-1 rounded text-xs font-bold 
                                {{ $audit->audit_type == 'Special' ? 'bg-purple-100 text-purple-700' : 
                                  ($audit->audit_type == 'FollowUp' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ $audit->audit_type ?? 'Regular' }}
                            </span>
                            <span class="text-sm text-gray-400">
                                {{ \Carbon\Carbon::parse($audit->created_at)->translatedFormat('d F Y, H:i') }}
                            </span>
                        </div>

                        <h4 class="text-lg font-semibold text-gray-800 mb-1">
                            Audit Dept: {{ $audit->department->name ?? 'Unknown Dept' }}
                        </h4>

                        {{-- Menampilkan data dari form input (Scope & Objective) --}}
                        <div class="mt-3 space-y-2">
                            <div class="flex items-start gap-2 text-sm text-gray-600">
                                <span class="text-lg">🎯</span>
                                <div>
                                    <span class="font-semibold text-gray-700">Scope:</span> 
                                    {{ $audit->audit_scope ?? '-' }}
                                </div>
                            </div>
                            <div class="flex items-start gap-2 text-sm text-gray-600">
                                <span class="text-lg">📝</span>
                                <div>
                                    <span class="font-semibold text-gray-700">Tujuan:</span> 
                                    {{ $audit->audit_objective ?? '-' }}
                                </div>
                            </div>
                            <div class="flex items-start gap-2 text-sm text-gray-600">
                                <span class="text-lg">👤</span>
                                <div>
                                    <span class="font-semibold text-gray-700">PIC Auditee:</span> 
                                    {{ $audit->pic_name ?? '-' }}
                                    @if(!empty($audit->pic_nik)) 
                                        <span class="text-gray-400 text-xs">({{ $audit->pic_nik }})</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kanan: Status & Action --}}
                    <div class="flex flex-col items-end gap-2">
                        @if($audit->status == 'COMPLETED')
                            <span class="flex items-center text-green-600 text-sm font-bold bg-green-50 px-3 py-1 rounded-full border border-green-100">
                                ✅ Selesai
                            </span>
                        @else
                            <span class="flex items-center text-yellow-600 text-sm font-bold bg-yellow-50 px-3 py-1 rounded-full border border-yellow-100">
                                ⏳ Sedang Berjalan
                            </span>
                        @endif

                        <a href="{{ route('audit.overview', $audit->id) }}" class="mt-2 text-sm text-blue-600 hover:text-blue-800 font-medium underline">
                            Lihat Detail Hasil &raquo;
                        </a>
                    </div>
                </div>
                
                {{-- Bagian Tim Tambahan (Jika ada data JSON tim) --}}
                @if(!empty($audit->audit_team))
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs font-bold text-gray-500 uppercase mb-2">Tim Tambahan:</p>
                    <div class="flex flex-wrap gap-2">
                        {{-- Asumsi audit_team disimpan sebagai JSON/Array --}}
                        @foreach(is_array($audit->audit_team) ? $audit->audit_team : json_decode($audit->audit_team, true) ?? [] as $member)
                            <div class="px-2 py-1 bg-gray-100 border border-gray-200 rounded text-xs text-gray-600 flex items-center gap-1">
                                <span class="font-bold">{{ $member['name'] ?? '' }}</span>
                                <span class="text-gray-400">({{ $member['role'] ?? '' }})</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
            @empty
            <div class="p-12 text-center">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-lg font-medium text-gray-900">Belum ada riwayat audit</h3>
                <p class="text-gray-500 mt-1">Auditor ini belum melakukan pemeriksaan apapun.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
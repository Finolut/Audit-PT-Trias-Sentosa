@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    
    {{-- Header --}}
    <div class="flex items-center gap-4 mb-2">
        <a href="{{ route('admin.auditors.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Profil & Riwayat Auditor</h2>
    </div>

    {{-- KARTU PROFIL (Sama seperti sebelumnya) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col items-center text-center lg:col-span-1">
            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-3xl font-bold mb-4">
                {{ substr($auditor->name, 0, 2) }}
            </div>
            <h3 class="text-xl font-bold text-gray-800">{{ $auditor->name }}</h3>
            <p class="text-gray-500 text-sm">{{ $auditor->department }}</p>
            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-full mt-2">NIK: {{ $auditor->nik }}</span>
        </div>

        {{-- Statistik --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 lg:col-span-2">
            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Statistik Kinerja</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100 text-center">
                    <div class="text-3xl font-bold text-blue-700">{{ $stats['total'] }}</div>
                    <div class="text-xs text-blue-600 mt-1">Total Audit</div>
                </div>
                <div class="p-4 bg-green-50 rounded-lg border border-green-100 text-center">
                    <div class="text-3xl font-bold text-green-700">{{ $stats['regular'] }}</div>
                    <div class="text-xs text-green-600 mt-1">Rutin</div>
                </div>
                <div class="p-4 bg-purple-50 rounded-lg border border-purple-100 text-center">
                    <div class="text-3xl font-bold text-purple-700">{{ $stats['special'] }}</div>
                    <div class="text-xs text-purple-600 mt-1">Khusus</div>
                </div>
                <div class="p-4 bg-orange-50 rounded-lg border border-orange-100 text-center">
                    <div class="text-3xl font-bold text-orange-700">{{ $stats['followup'] }}</div>
                    <div class="text-xs text-orange-600 mt-1">Follow Up</div>
                </div>
            </div>
        </div>
    </div>

    {{-- TIMELINE HISTORY --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-gray-800 text-lg">Riwayat Pemeriksaan</h3>
        </div>
        
        <div class="divide-y divide-gray-100">
@forelse($history as $audit)
    {{-- Tambahkan ID unik dan logic class untuk warna highlight --}}
    <div id="audit-{{ $audit->id }}" 
         class="p-6 transition-all duration-1000 {{ session('highlight_audit') == $audit->id ? 'bg-yellow-50 border-l-4 border-yellow-400' : 'hover:bg-gray-50' }}">
        
        <div class="flex flex-col md:flex-row justify-between md:items-start gap-4">
            <div class="flex-1">
                {{-- ID Audit (Sangat membantu untuk verifikasi copas) --}}
                <div class="text-[10px] font-mono text-gray-400 mb-1 uppercase">ID: {{ $audit->id }}</div>
                
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-2.5 py-1 rounded text-xs font-bold 
                        {{ $audit->type == 'Special' ? 'bg-purple-100 text-purple-700' : 
                          ($audit->type == 'FollowUp' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700') }}">
                        {{ $audit->type ?? 'Regular' }}
                    </span>
                    <span class="text-sm text-gray-400">
                        {{ \Carbon\Carbon::parse($audit->audit_date)->translatedFormat('d F Y') }}
                    </span>
                </div>

                        {{-- Isi Detail --}}
                        <div class="mt-3 space-y-2">
                            <div class="text-sm text-gray-600">
                                <span class="font-semibold text-gray-800">Scope:</span> {{ $audit->scope ?? '-' }}
                            </div>
                            <div class="text-sm text-gray-600">
                                <span class="font-semibold text-gray-800">Tujuan:</span> {{ $audit->objective ?? '-' }}
                            </div>
                            <div class="text-sm text-gray-600">
                                <span class="font-semibold text-gray-800">PIC Auditee:</span> 
                                {{ $audit->pic_auditee_name ?? '-' }} 
                                <span class="text-xs text-gray-400">({{ $audit->pic_auditee_nik ?? '' }})</span>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="flex flex-col items-end">
                         @if($audit->status == 'COMPLETED')
                            <span class="text-green-600 text-sm font-bold bg-green-50 px-3 py-1 rounded-full border border-green-100">✅ Selesai</span>
                        @else
                            <span class="text-yellow-600 text-sm font-bold bg-yellow-50 px-3 py-1 rounded-full border border-yellow-100">⏳ Proses</span>
                        @endif
<a href="{{ route('audit.overview', $audit->id) }}" class="mt-2 text-xs text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1">
    Detail Laporan 
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
</a>
            </div>
        </div>

                {{-- Tim Tambahan (JSON) --}}
                @if(!empty($audit->audit_team))
                    @php 
                        $team = is_string($audit->audit_team) ? json_decode($audit->audit_team, true) : $audit->audit_team;
                    @endphp
                    @if($team && count($team) > 0)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-2">Tim Tambahan:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($team as $member)
                                <div class="px-2 py-1 bg-gray-100 border border-gray-200 rounded text-xs text-gray-600">
                                    <b>{{ $member['name'] ?? '' }}</b> <span class="text-gray-400">({{ $member['role'] ?? '' }})</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endif

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
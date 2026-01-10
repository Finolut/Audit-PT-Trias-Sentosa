@extends('layouts.admin')

@section('content')
    {{-- Header Section --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="{{ route('layouts.admin') }}" class="hover:text-blue-600 transition-colors">Dashboard</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-800 font-bold">Log Audit</li>
                </ol>
            </nav>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                Log Audit: <span class="text-blue-600">{{ $currentDept->name }}</span>
            </h2>
            <p class="text-gray-500 mt-1">Daftar riwayat audit yang telah dilakukan untuk departemen ini.</p>
        </div>

        {{-- Mini Stats --}}
        <div class="flex gap-4">
            <div class="bg-white px-6 py-3 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-3 bg-blue-50 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Audit</p>
                    <p class="text-xl font-black text-gray-800">{{ $audits->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Audit Log Table --}}
    <div class="bg-white shadow-xl shadow-gray-200/50 rounded-2xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Tanggal Audit</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Auditor</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Team Responder</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Detail</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($audits as $audit)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900">{{ $audit->created_at->format('d M Y') }}</span>
                                    <span class="text-xs text-gray-400">{{ $audit->created_at->format('H:i') }} WIB</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($audit->session->auditor_name ?? 'A', 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ $audit->session->auditor_name ?? '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5 max-w-xs">
                                    @forelse($audit->responders as $resp)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[11px] font-semibold border border-gray-200">
                                            {{ $resp->responder_name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">No responder</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($audit->status == 'COMPLETED')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        On Progress
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('audit.overview', $audit->id) }}" 
                                   class="inline-flex items-center gap-2 bg-gray-900 text-white hover:bg-blue-600 px-4 py-2 rounded-lg text-xs font-bold transition-all transform group-hover:scale-105 active:scale-95">
                                    Lihat Hasil
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-gray-400 font-medium">Belum ada data audit untuk departemen ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
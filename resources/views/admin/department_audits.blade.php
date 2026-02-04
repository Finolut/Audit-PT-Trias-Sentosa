@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Riwayat Audit</h2>
            <p class="text-gray-500 mt-1">
                Daftar riwayat audit untuk departemen: 
                <span class="font-bold text-blue-600">{{ $currentDept->name }}</span>
            </p>
        </div>

        <div class="flex items-center gap-3">
            <form id="yearFilterForm" action="{{ url()->current() }}" method="GET" class="flex items-center bg-white px-3 py-1.5 rounded-xl border border-gray-200 shadow-sm focus-within:ring-2 focus-within:ring-blue-500">
                <label for="year" class="text-xs font-bold text-gray-400 uppercase mr-2">Tahun:</label>
                <select name="year" id="year" class="text-sm border-none focus:ring-0 rounded-lg bg-transparent font-bold text-gray-700 cursor-pointer">
                    <option value="">Semua</option>
                    @for ($y = 2026; $y <= 2030; $y++)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </form>

            <div class="bg-blue-50 px-4 py-2 rounded-xl border border-blue-100 flex items-center gap-2">
                <span class="text-blue-700 font-black text-lg">{{ $audits->count() }}</span>
                <span class="text-blue-600 text-xs font-bold uppercase tracking-wider">Total Audit</span>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Auditor</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Responders</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($audits as $audit)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="text-sm font-bold text-gray-700">{{ $audit->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $audit->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs mr-3">
                                        {{ substr($audit->session->auditor_name ?? 'A', 0, 1) }}
                                    </div>
                                    <div class="text-sm font-semibold text-gray-800">
                                        {{ $audit->session->auditor_name ?? '-' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-wrap justify-center gap-1">
                                    @forelse($audit->responders as $resp)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200 uppercase">
                                            {{ $resp->responder_name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">No responders</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $status = strtolower($audit->status ?? '');
                                @endphp

                                @if(in_array($status, ['complete', 'selesai', 'done']))
                                    <span class="px-3 py-1 inline-flex items-center text-[10px] font-black rounded-full bg-green-50 text-green-600 border border-green-100 uppercase tracking-tighter">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                        SELESAI
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex items-center text-[10px] font-black rounded-full bg-amber-50 text-amber-600 border border-amber-100 uppercase tracking-tighter">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                        BERJALAN
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('admin.audit.overview', $audit->id) }}" 
                                   class=" items-center justify-center px-4 py-2 border border-transparent text-sm font-bold rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-sm hover:shadow-md transition-all active:scale-95">
                                    Lihat Hasil 
                                    <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <p class="text-gray-500 font-bold">Data Tidak Ditemukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
  
    </script>
    @endpush
@endsection
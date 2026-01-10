@extends('layouts.admin')

@section('content')
    <div class="mb-8">
        <a href="{{ route('dept.show', $audit->department_id) }}" class="text-sm text-blue-600 hover:underline font-semibold">← Kembali ke List Audit</a>
        
        <div class="mt-4 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-3xl font-bold text-gray-800">Overview Audit</h2>
            <div class="mt-2 text-gray-600 flex flex-col md:flex-row gap-4 md:items-center">
                <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-full text-xs font-bold uppercase tracking-wide">
                    {{ $audit->session->auditor_department ?? 'Ext' }}
                </span>
                <p><strong>Auditor:</strong> {{ $audit->session->auditor_name ?? '-' }}</p>
                <span class="hidden md:inline text-gray-300">|</span>
                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($audit->created_at)->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($mainClauses as $key => $subClauses)
            <a href="{{ route('audit.clause', ['id' => $audit->id, 'mainClause' => $key]) }}" 
               class="relative block bg-white p-6 rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-blue-300 group overflow-hidden">
                
                {{-- Decorative Background Number --}}
                <span class="absolute -right-4 -bottom-6 text-9xl font-bold text-gray-50 opacity-50 group-hover:text-blue-50 transition-colors">
                    {{ $key }}
                </span>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-3xl font-black text-gray-800 group-hover:text-blue-600 transition-colors">
                            Clause {{ $key }}
                        </span>
                        <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                            ➜
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-600 group-hover:text-blue-700 leading-tight mb-4">
                        {{ $titles[$key] ?? 'General Requirement' }}
                    </h3>

                    <div class="text-xs text-gray-400 font-mono bg-gray-50 rounded p-2 inline-block">
                        Includes: {{ implode(', ', $subClauses) }}
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endsection
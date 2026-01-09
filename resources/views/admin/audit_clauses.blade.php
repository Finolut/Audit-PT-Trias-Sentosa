@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <a href="{{ route('dept.show', $audit->department_id) }}" class="text-sm text-gray-500 hover:underline">← Kembali ke List Audit</a>
        <h2 class="text-2xl font-bold mt-2">Pilih Klausul Audit</h2>
        <p class="text-gray-600">Auditor: {{ $audit->session->auditor_name ?? '-' }} | Tanggal: {{ $audit->created_at->format('d M Y') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($clauses as $clause)
            <a href="{{ route('audit.clause', ['id' => $audit->id, 'clause_id' => $clause->id]) }}" 
               class="block bg-white p-6 rounded-lg shadow hover:shadow-lg transition border-l-4 border-blue-500 group">
                
                <div class="flex items-center justify-between mb-2">
                    <span class="text-2xl font-bold text-gray-800">{{ $clause->clause_code ?? 'X.X' }}</span>
                    <span class="text-gray-400 group-hover:text-blue-500">➜</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 group-hover:text-blue-700">
                    {{ $clause->title ?? 'Judul Klausul' }}
                </h3>
            </a>
        @endforeach
    </div>
@endsection
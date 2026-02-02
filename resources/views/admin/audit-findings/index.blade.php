@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-gray-800">Daftar Semua Temuan Audit</h2>
    <p class="text-gray-500 mt-1">Temuan dari seluruh sesi audit yang telah dilaporkan oleh auditor.</p>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-[#7c1d1d]">
        <h3 class="font-bold text-white flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
            </svg>
            Temuan Audit
        </h3>
    </div>

    <div class="p-4 md:p-6">
        @if($findings->isEmpty())
            <div class="text-center py-12 text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.467-.881-6.08-2.33M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <p class="text-lg">Tidak ada temuan audit yang ditemukan.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($findings as $f)
                    @php
                        $levelConfig = match($f->finding_level) {
                            'MAJOR_NC' => ['label' => 'MAJOR NC', 'color' => 'red'],
                            'MINOR_NC' => ['label' => 'MINOR NC', 'color' => 'yellow'],
                            default    => ['label' => 'OBSERVED', 'color' => 'blue'],
                        };

                        $bgClass = match($levelConfig['color']) {
                            'red'    => 'bg-red-50 border-red-200',
                            'yellow' => 'bg-yellow-50 border-yellow-200',
                            'blue'   => 'bg-blue-50 border-blue-200',
                        };

                        $textClass = match($levelConfig['color']) {
                            'red'    => 'text-red-700',
                            'yellow' => 'text-yellow-700',
                            'blue'   => 'text-blue-700',
                        };
                    @endphp

                    <div class="p-4 rounded-xl border {{ $bgClass }} hover:shadow-sm transition">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex flex-col">
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                    {{ $f->dept_name }}
                                </span>
                                <span class="text-sm font-bold text-gray-800">
                                    Clause {{ $f->clause_code }}
                                </span>
                            </div>
                            <span class="px-2 py-1 text-xs font-bold rounded {{ $textClass }}">
                                {{ $levelConfig['label'] }}
                            </span>
                        </div>

                        <p class="text-gray-700 text-sm leading-relaxed italic mb-3">
                            "{{ $f->finding_note }}"
                        </p>

                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span>Auditor: <span class="font-medium text-gray-800">{{ $f->auditor_name ?? '-' }}</span></span>
                            <span>{{ \Carbon\Carbon::parse($f->created_at)->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $findings->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
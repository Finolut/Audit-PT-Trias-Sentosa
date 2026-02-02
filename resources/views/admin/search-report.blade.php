@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto mt-10">
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
        <h2 class="text-lg font-bold text-blue-700 mb-4">🔍 Cari Laporan Audit</h2>
        <p class="text-sm text-gray-600 mb-6">Masukkan <strong>Kode Audit</strong> untuk membuka detail laporan.</p>

        @if(session('search_error'))
            <div class="mb-5 px-4 py-3 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm font-medium flex items-start gap-2">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                </svg>
                {{ session('search_error') }}
            </div>
        @endif

        <form id="searchForm" action="{{ route('admin.audit.search') }}" method="GET" class="relative">
            <input type="text"
                   name="audit_code"
                   placeholder="Masukkan Kode Audit..."
                   class="w-full pl-4 pr-12 py-3 text-sm bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm placeholder-gray-400"
                   required>
            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white rounded-md px-3 py-1.5 flex items-center justify-center transition-colors">
                <span class="text-sm font-medium">Cari</span>
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Opsional: Tekan Enter untuk submit
    document.getElementById('searchForm').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.submit();
        }
    });
</script>
@endpush
@endsection
@extends('layouts.admin')

@section('content')
<div class="p-6 bg-white">

<div class="mb-6 flex justify-end">
    <a 
        href="{{ route('admin.items.create') }}" 
        class="px-4 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-200 transition"
    >
        + Tambah Soal
    </a>
</div>
   <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('admin.items.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Klausul Utama (4, 5, 6, ..., 10) -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Klausul Utama</label>
                <select 
                    name="main_clause" 
                    id="mainClauseSelect"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition"
                >
                    <option value="">Semua Klausul</option>
                    @for($i = 4; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ request('main_clause') == $i ? 'selected' : '' }}>
                            Klausul {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Sub-Klausul (dinamis berdasarkan Klausul Utama) -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Sub-Klausul</label>
                <select 
                    name="clause_id" 
                    id="subClauseSelect"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition"
                >
                    <option value="">Semua Sub-Klausul</option>
                    @foreach($filteredClauses as $clause)
                        <option value="{{ $clause->id }}" {{ request('clause_id') == $clause->id ? 'selected' : '' }}>
                            {{ $clause->clause_code }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Maturity Level -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Maturity</label>
                <select 
                    name="maturity_level_id" 
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition"
                >
                    <option value="">Semua Level</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ request('maturity_level_id') == $level->id ? 'selected' : '' }}>
                            Level {{ $level->level_number }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-end space-x-2">
                <button 
                    type="submit" 
                    class="px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-200 transition w-full"
                >
                    Filter
                </button>
                <a 
                    href="{{ route('admin.items.index') }}" 
                    class="px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:ring-2 focus:ring-gray-200 transition text-center w-full"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3.5">Klausul</th>
                        <th class="px-5 py-3.5">Maturity</th>
                        <th class="px-5 py-3.5">Isi Soal (Item Text)</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($items as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-4 text-gray-700 font-medium">{{ $item->clause->clause_code }}</td>
                        <td class="px-5 py-4 text-gray-700">Level {{ $item->maturityLevel->level_number }}</td>
                        <td class="px-5 py-4 text-gray-600 max-w-md break-words">{{ $item->item_text }}</td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex justify-center space-x-3">
                                <a 
                                    href="{{ route('admin.items.edit', $item->id) }}" 
                                    class="text-blue-600 hover:text-blue-800 inline-flex items-center gap-1 text-sm font-medium"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button 
                                        type="submit"
                                        class="text-red-600 hover:text-red-800 inline-flex items-center gap-1 text-sm font-medium"
                                        onclick="return confirm('Hapus soal ini?')"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($items->isEmpty())
            <div class="text-center py-12 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-sm">Tidak ada data soal ditemukan.</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const allClauses = @json($clauses->map(fn($c) => ['id' => $c->id, 'code' => $c->clause_code]));
    const mainSelect = document.getElementById('mainClauseSelect');
    const subSelect = document.getElementById('subClauseSelect');

    function updateSubClauses() {
        const selectedMain = mainSelect.value;
        subSelect.innerHTML = '<option value="">Semua Sub-Klausul</option>';

        let filtered = selectedMain
            ? allClauses.filter(c => c.code.startsWith(selectedMain + '.'))
            : allClauses;

        // Natural sort: 4.2 before 4.10
        filtered.sort((a, b) => a.code.localeCompare(b.code, undefined, { numeric: true, sensitivity: 'base' }));

        filtered.forEach(clause => {
            const option = document.createElement('option');
            option.value = clause.id;
            option.textContent = clause.code;
            if ("{{ request('clause_id') }}" == clause.id) {
                option.selected = true;
            }
            subSelect.appendChild(option);
        });
    }

    updateSubClauses();
    mainSelect.addEventListener('change', updateSubClauses);
});
</script>
@endpush
@endsection
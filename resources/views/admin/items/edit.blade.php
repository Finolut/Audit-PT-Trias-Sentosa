@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Soal Audit ISO</h2>
        <p class="text-gray-500 text-sm mt-1">Perbarui detail butir pertanyaan audit.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
        <form action="{{ route('admin.items.update', $item->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-2">Pilih Klausul</label>
                    <select 
                        name="clause_id" 
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition"
                        required
                    >
                        @foreach($clauses as $clause)
                            <option value="{{ $clause->id }}" {{ $item->clause_id == $clause->id ? 'selected' : '' }}>
                                {{ $clause->clause_code }} - {{ $clause->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-2">Maturity Level</label>
                    <select 
                        name="maturity_level_id" 
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition"
                        required
                    >
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ $item->maturity_level_id == $level->id ? 'selected' : '' }}>
                                Level {{ $level->level_number }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-2">Urutan (Order)</label>
                <input 
                    type="number" 
                    name="item_order" 
                    value="{{ $item->item_order }}" 
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition"
                    required
                >
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-2">Teks Pertanyaan (Item Text)</label>
                <textarea 
                    name="item_text" 
                    rows="4" 
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition resize-y"
                    required
                >{{ $item->item_text }}</textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a 
                    href="{{ route('admin.items.index') }}" 
                    class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-200 transition"
                >
                    Perbarui Soal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Tambah Soal Audit ISO</h2>
        <p class="text-gray-500 text-sm">Tambahkan butir pertanyaan baru untuk evaluasi audit.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.items.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                {{-- Pilih Klausul --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Klausul</label>
                    <select name="clause_id" class="w-full rounded-xl border-gray-200 focus:ring-blue-500 shadow-sm text-sm" required>
                        <option value="">-- Pilih Klausul --</option>
                        @foreach($clauses as $clause)
                            <option value="{{ $clause->id }}">{{ $clause->clause_code }} - {{ $clause->title }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Pilih Maturity Level --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Maturity Level</label>
                    <select name="maturity_level_id" class="w-full rounded-xl border-gray-200 focus:ring-blue-500 shadow-sm text-sm" required>
                        <option value="">-- Pilih Level --</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}">Level {{ $level->level_number }} - {{ $level->description }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Urutan (Order)</label>
                <input type="number" name="item_order" class="w-full rounded-xl border-gray-200 shadow-sm text-sm" placeholder="Contoh: 1" required>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-2">Teks Pertanyaan (Item Text)</label>
                <textarea name="item_text" rows="4" class="w-full rounded-xl border-gray-200 shadow-sm text-sm" placeholder="Masukkan detail pertanyaan audit di sini..." required></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.items.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-bold text-sm hover:bg-gray-50 transition-all">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">Simpan Soal</button>
            </div>
        </form>
    </div>
</div>
@endsection
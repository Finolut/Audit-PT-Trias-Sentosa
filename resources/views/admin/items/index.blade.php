@extends('layouts.admin')

@section('content')
<div class="p-6 border-b border-gray-100 bg-gray-50">
    <form method="GET" action="{{ route('admin.items.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Order -->
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Order</label>
            <input type="number" name="order" value="{{ request('order') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
        </div>

        <!-- Klausul -->
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Klausul</label>
            <select name="clause_id" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                <option value="">Semua Klausul</option>
                @foreach($clauses as $clause)
                    <option value="{{ $clause->id }}" {{ request('clause_id') == $clause->id ? 'selected' : '' }}>
                        {{ $clause->clause_code }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Maturity Level -->
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Maturity</label>
            <select name="maturity_level_id" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                <option value="">Semua Level</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" {{ request('maturity_level_id') == $level->id ? 'selected' : '' }}>
                        Level {{ $level->level_number }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Pencarian Isi Soal -->
        <div class="lg:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1">Isi Soal</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari isi soal..." class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
        </div>

        <!-- Tombol Aksi -->
        <div class="flex items-end space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Filter</button>
            <a href="{{ route('admin.items.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-300">Reset</a>
        </div>
    </form>
</div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-400 text-xs uppercase font-bold">
                <tr>
                    <th class="px-6 py-4">Order</th>
                    <th class="px-6 py-4">Klausul</th>
                    <th class="px-6 py-4">Maturity</th>
                    <th class="px-6 py-4">Isi Soal (Item Text)</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @foreach($items as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-bold">{{ $item->item_order }}</td>
                    <td class="px-6 py-4">{{ $item->clause->clause_code }}</td>
                    <td class="px-6 py-4">Level {{ $item->maturityLevel->level_number }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $item->item_text }}</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.items.edit', $item->id) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                        <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline" onclick="return confirm('Hapus soal ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
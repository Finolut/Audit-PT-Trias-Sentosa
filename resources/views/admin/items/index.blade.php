@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6 flex justify-between items-center border-b border-gray-100">
        <h2 class="text-xl font-bold text-gray-800">Daftar Soal Audit ISO</h2>
        <a href="{{ route('admin.items.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold"> + Tambah Soal </a>
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
@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Hasil Audit Detail</h2>
        <a href="{{ route('admin.dept.show', $audit->department_id) }}" class="text-sm text-gray-500 hover:underline">← Kembali</a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow mb-6 grid grid-cols-3 gap-4">
        <div>
            <div class="text-xs text-gray-500 uppercase">Auditor</div>
            <div class="font-bold text-lg">{{ $audit->session->auditor_name ?? '-' }}</div>
        </div>
        <div>
            <div class="text-xs text-gray-500 uppercase">Responder</div>
            <div class="font-bold text-lg text-gray-700">
                @foreach($audit->responders as $r)
                    {{ $r->responder_name }}, 
                @endforeach
            </div>
        </div>
        <div>
            <div class="text-xs text-gray-500 uppercase">Tanggal Selesai</div>
            <div class="font-bold">{{ $audit->created_at->format('d F Y H:i') }}</div>
        </div>
    </div>

    <h3 class="text-lg font-semibold mb-3">Rekapitulasi Jawaban (Final)</h3>
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/2">Pertanyaan / Item</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total YES</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total NO</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kesimpulan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($audit->answerFinals as $final)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $final->item->item_text ?? 'Item dihapus' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-green-600">{{ $final->yes_count }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-red-600">{{ $final->no_count }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($final->final_yes > $final->final_no)
                                <span class="px-2 py-1 text-xs rounded bg-green-200 text-green-800 font-bold">COMPLIANT (YES)</span>
                            @elseif($final->final_no > $final->final_yes)
                                <span class="px-2 py-1 text-xs rounded bg-red-200 text-red-800 font-bold">NON-COMPLIANT (NO)</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-gray-200 text-gray-800">DRAW</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
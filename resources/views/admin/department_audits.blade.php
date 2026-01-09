@extends('layouts.admin')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Audit Log: {{ $currentDept->name }}</h2>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Auditor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Responder</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($audits as $audit)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $audit->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="px-2 py-1 rounded bg-blue-100 text-blue-800">
                                {{ $audit->session->auditor_name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @foreach($audit->responders as $resp)
                                <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs">
                                    {{ $resp->responder_name }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($audit->status == 'COMPLETED')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
<a href="{{ route('audit.overview', $audit->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">
    Lihat Audit ➜
</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data audit untuk departemen ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
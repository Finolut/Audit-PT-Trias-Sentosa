@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-gray-800">Manajemen Token Sesi Audit</h2>
    <p class="text-gray-500 mt-1">Kelola token akses untuk sesi audit internal.</p>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-blue-600">
        <h3 class="font-bold text-white">Daftar Sesi Audit</h3>
    </div>
    <div class="p-4">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
        @endif

        @if($sessions->isEmpty())
            <p class="text-gray-500 text-center py-4">Belum ada sesi token.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Token</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auditor</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departemen</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
<tbody class="divide-y divide-gray-200">
    @foreach($sessions as $session)
    <tr>
        <td class="px-4 py-2 font-mono text-sm font-bold text-blue-600">{{ $session->resume_token }}</td>
        <td class="px-4 py-2 text-sm">{{ $session->auditor_name }}</td>
        <td class="px-4 py-2 text-sm">{{ $session->auditor_nik }}</td>
        <td class="px-4 py-2 text-sm">{{ $session->auditor_department }}</td>
        <td class="px-4 py-2">
            <div class="flex flex-col gap-1">


                <div class="flex items-center gap-1 mt-1">
                    <a href="{{ route('admin.token-sessions.extend.form', $session) }}"
                       class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                        Perpanjang
                    </a>

                    <form action="{{ route('admin.token-sessions.destroy', $session) }}" method="POST" class="inline"
                        onsubmit="return confirm('Hapus sesi ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" title="Hapus"
                            class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </td>
    </tr>
    @endforeach
</tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
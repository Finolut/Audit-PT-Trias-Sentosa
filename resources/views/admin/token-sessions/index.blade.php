@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-gray-800">Manajemen Token Sesi Audit</h2>
    <p class="text-gray-500 mt-1">Kelola token akses untuk sesi audit internal.</p>
</div>

<!-- Form Tambah Sesi -->
<div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm mb-6">
    <h3 class="font-bold text-gray-800 mb-4">Tambah Sesi Baru</h3>
    <form action="{{ route('admin.token-sessions.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Auditor</label>
                <input type="text" name="auditor_name" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="auditor_email" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                <input type="text" name="auditor_nik" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
                <input type="text" name="auditor_department" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
        <button type="submit"
            class="mt-4 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
            Buat Sesi & Generate Token
        </button>
    </form>
</div>

<!-- Daftar Sesi -->
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
                                <div class="flex items-center gap-2">
                                    <!-- Regenerate Token -->
                                    <form action="{{ route('admin.token-sessions.regenerate', $session) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" title="Perbarui Token"
                                            class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">
                                            Perbarui
                                        </button>
                                    </form>

                                    <!-- Hapus -->
                                    <form action="{{ route('admin.token-sessions.destroy', $session) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Hapus sesi ini? Token akan hilang permanen.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus"
                                            class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200">
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
        @endif
    </div>
</div>
@endsection
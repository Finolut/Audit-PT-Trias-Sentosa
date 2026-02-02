@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-gray-800">Perpanjang Masa Berlaku Token</h2>
        <p class="text-gray-500 mt-1">Token: <code class="bg-gray-100 px-2 py-1 rounded">{{ $session->resume_token }}</code></p>
    </div>

    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-800">
                Saat ini berlaku hingga: 
                <strong>{{ $session->resume_token_expires_at ? $session->resume_token_expires_at->format('d M Y H:i') : 'Tidak terbatas' }}</strong>
            </p>
        </div>

        <form action="{{ route('admin.token-sessions.extend', $session) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tambah Masa Berlaku (hari)
                </label>
                <input type="number" name="days" min="1" max="365" value="7" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Maksimal 365 hari sekaligus.</p>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700">
                    Perpanjang Sekarang
                </button>
                <a href="{{ route('admin.token-sessions.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
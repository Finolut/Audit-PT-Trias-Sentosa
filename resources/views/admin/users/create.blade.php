@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header Halaman --}}
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tambah User Baru</h2>
            <p class="text-sm text-gray-500 mt-1">Tambahkan Auditor atau Admin baru ke dalam sistem.</p>
        </div>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
        <p class="font-bold">Berhasil!</p>
        <p>{{ session('success') }}</p>
    </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 bg-gray-50/50">
            <h3 class="font-semibold text-gray-700">Formulir Data User</h3>
        </div>
        
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Nama --}}
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Contoh: Budi Santoso">
                </div>

                {{-- NIK --}}
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                    <input type="text" name="nik" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Nomor Induk Karyawan">
                </div>

                {{-- Departemen --}}
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Departemen</label>
                    <select name="department" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="" disabled selected>Pilih Departemen...</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Role Selection --}}
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role Akses</label>
                    <select name="role" id="roleSelect" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="auditor" selected>Auditor</option>
                        <option value="admin">Admin</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">*Auditor tidak memerlukan password login.</p>
                </div>
            </div>

            {{-- Password Field (Hidden by default for Auditor) --}}
            <div id="passwordContainer" class="hidden border-t border-gray-100 pt-6 mt-2">
                <div class="max-w-md">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Admin <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="passwordInput" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Masukkan password aman">
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-colors shadow-sm flex items-center">
                    <span class="mr-2">💾</span> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('roleSelect');
        const passwordContainer = document.getElementById('passwordContainer');
        const passwordInput = document.getElementById('passwordInput');

        function togglePassword() {
            if (roleSelect.value === 'admin') {
                passwordContainer.classList.remove('hidden');
                passwordInput.setAttribute('required', 'required');
            } else {
                passwordContainer.classList.add('hidden');
                passwordInput.removeAttribute('required');
                passwordInput.value = ''; // Reset value jika ganti ke auditor
            }
        }

        // Jalankan saat load awal (jika browser menyimpan cache input)
        togglePassword();

        // Jalankan saat user mengubah pilihan
        roleSelect.addEventListener('change', togglePassword);
    });
</script>
@endsection
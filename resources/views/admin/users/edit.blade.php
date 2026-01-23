@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header Halaman --}}
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit User: {{ $user->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">Perbarui data user sistem.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
        <p class="font-bold">Berhasil!</p>
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 bg-gray-50/50">
            <h3 class="font-semibold text-gray-700">Formulir Edit User</h3>
        </div>
        
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama --}}
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                {{-- NIK --}}
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                {{-- Departemen --}}
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Departemen</label>
                    <select name="department" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="" disabled>Pilih Departemen...</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->name }}" {{ old('department', $user->department) == $dept->name ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Role --}}
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role Akses</label>
                    <select name="role" id="roleSelect" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="auditor" {{ old('role', $user->role) == 'auditor' ? 'selected' : '' }}>Auditor</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">*Ubah role akan mengatur ulang email & password.</p>
                </div>
            </div>

            {{-- Email & Password (Hanya untuk Admin) --}}
            <div id="adminFieldsContainer" class="{{ old('role', $user->role) === 'admin' ? '' : 'hidden' }} border-t border-gray-100 pt-6 mt-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-md">
                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Admin <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="emailInput" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="contoh@email.com">
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru (Opsional)</label>
                        <input type="password" name="password" id="passwordInput" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Biarkan kosong jika tidak diubah">
                        <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter jika diisi.</p>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <a href="{{ route('admin.users.create') }}" class="px-4 py-2.5 text-gray-600 hover:text-gray-800 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-colors shadow-sm flex items-center">
                    <span class="mr-2">✏️</span> Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('roleSelect');
        const adminFieldsContainer = document.getElementById('adminFieldsContainer');
        const emailInput = document.getElementById('emailInput');
        const passwordInput = document.getElementById('passwordInput');

        function toggleAdminFields() {
            if (roleSelect.value === 'admin') {
                adminFieldsContainer.classList.remove('hidden');
                emailInput.setAttribute('required', 'required');
            } else {
                adminFieldsContainer.classList.add('hidden');
                emailInput.removeAttribute('required');
                emailInput.value = '';
                passwordInput.value = '';
            }
        }

        toggleAdminFields();
        roleSelect.addEventListener('change', toggleAdminFields);
    });
</script>
@endsection
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Pemeriksaan Internal</title>

    <!-- TomSelect CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
</head>
<body>

<div class="container">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">📝 Form Pemeriksaan Internal</h1>
    </div>

    <form method="POST" action="{{ route('audit.start') }}" id="auditForm" class="space-y-6">
        @csrf

        <!-- Informasi Pemeriksaan -->
        <div class="section">
            <h2 class="text-lg font-semibold text-blue-700 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-800 w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                Informasi Pemeriksaan
            </h2>

            <div class="space-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pemeriksaan</label>
                    <select name="audit_type" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Regular">Pemeriksaan Rutin (Terjadwal)</option>
                        <option value="Special">Pemeriksaan Khusus (Mendadak)</option>
                        <option value="FollowUp">Pemeriksaan Lanjutan (Follow Up)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bagian yang Diperiksa</label>
                    <input type="text" name="audit_scope" placeholder="Gudang Bahan Baku / Produksi / Keuangan" required
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Melakukan Pemeriksaan Ini</label>
                    <textarea name="audit_objective" placeholder="Memastikan barang masuk/keluar tercatat dengan benar, atau mengecek kepatuhan SOP kebersihan" required
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition min-h-[80px]"></textarea>
                </div>
            </div>
        </div>

        <!-- Tim Pemeriksa -->
        <div class="section">
            <h2 class="text-lg font-semibold text-blue-700 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-800 w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                Tim Pemeriksa (Auditor)
            </h2>

            <div class="space-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Anda (Sebagai Ketua Tim Pemeriksa)</label>
                    <select id="auditor_select" name="auditor_name" placeholder="Pilih nama Anda..." required
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition"></select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIK Anda</label>
                    <input type="text" id="auditor_nik" name="auditor_nik" readonly
                        class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departemen Anda</label>
                    <input type="text" id="auditor_department" name="auditor_department" readonly
                        class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-500 cursor-not-allowed">
                </div>

                <div class="flex items-start gap-3 pt-2">
                    <input type="checkbox" id="confirmation" name="confirmation" required class="mt-1 h-4 w-4 text-blue-600 rounded focus:ring-blue-500">
                    <label for="confirmation" class="text-sm text-gray-700">
                        Saya menyatakan data di atas benar dan saya bertanggung jawab atas kebenaran informasi yang saya berikan.
                    </label>
                </div>

                <div class="bg-amber-50 border-l-4 border-amber-400 p-3 rounded-r-lg text-sm text-amber-800">
                    ℹ️ Jika ada anggota tim tambahan (misal: pengamat atau ahli), tambahkan di bawah.
                </div>

                <div id="audit-team-container"></div>
                <button type="button" class="w-full py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded-lg transition"
                    onclick="addAuditTeam()">+ Tambah Anggota Tim (Opsional)</button>
            </div>
        </div>

        <!-- Detail Audit (Hidden by default) -->
        <div id="audit-details" class="hidden space-y-6">
            <!-- Departemen yang Di-Audit -->
            <div class="section">
                <h2 class="text-lg font-semibold text-blue-700 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-800 w-6 h-6 rounded-full flex items-center justify-center text-xs">3</span>
                    Departemen yang Akan Di Audit
                </h2>

                <div class="space-y-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Departemen yang Akan Di Audit</label>
                        <select id="department_select" name="department_id" placeholder="Pilih departemen..." required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition"></select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab di Departemen Tersebut</label>
                        <input type="text" name="pic_name" placeholder="Nama lengkap penanggung jawab" required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK Penanggung Jawab (Opsional)</label>
                        <input type="text" name="pic_nik" placeholder="Contoh: 123456"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition">
                    </div>
                </div>
            </div>

            <!-- Jadwal Pemeriksaan -->
            <div class="section">
                <h2 class="text-lg font-semibold text-blue-700 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-800 w-6 h-6 rounded-full flex items-center justify-center text-xs">4</span>
                    Jadwal Pemeriksaan
                </h2>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pemeriksaan</label>
                    <input type="date" name="audit_date" value="{{ date('Y-m-d') }}" required
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition">
                </div>
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm focus:ring-2 focus:ring-blue-200 transition">
                ✅ Mulai Pemeriksaan
            </button>
        </div>
    </form>
</div>

<!-- Data untuk JS -->
<script>
    const AUDITORS = @json($auditors);
    const DEPARTMENTS = @json($departments->map(fn($d) => ['id' => $d->id, 'name' => $d->name]));
</script>

<!-- TomSelect JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<!-- Custom JS -->
<script src="{{ asset('js/form.js') }}"></script>

</body>
</html>
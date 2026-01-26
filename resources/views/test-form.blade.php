<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Pemeriksaan Internal</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- TomSelect CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .ts-border-left {
            border-left: 4px solid #1a365d;
        }
        .btn-primary {
            background-color: #1a365d;
            color: white;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background-color: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 54, 93, 0.25);
        }
        .btn-add {
            background-color: #f1f5f9;
            color: #334155;
            border: 1px dashed #94a3b8;
            transition: background-color 0.2s;
        }
        .btn-add:hover {
            background-color: #e2e8f0;
        }
        .note-box {
            background-color: #fffbeb;
            border-left: 4px solid #ca8a04;
            color: #92400e;
        }
        input, select, textarea {
            border: 1px solid #cbd5e1;
            background: white;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #1a365d;
            box-shadow: 0 0 0 3px rgba(26, 54, 93, 0.1);
        }
    </style>
</head>
<body class="min-h-screen py-10">

<div class="max-w-2xl mx-auto px-4">
    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">

        <h2 class="text-center text-2xl font-bold text-[#1a365d] mb-8 flex items-center justify-center gap-2">
            📝 Form Pemeriksaan Internal
        </h2>

        <form method="POST" action="{{ route('audit.start') }}" id="auditForm">
            @csrf

            <!-- Section 1: Informasi Pemeriksaan -->
            <div class="mb-8 p-5 rounded-lg bg-gray-50 ts-border-left">
                <h3 class="text-lg font-semibold text-[#1a365d] mb-4 flex items-center gap-2">
                    1️⃣ Informasi Pemeriksaan
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-1">Jenis Pemeriksaan</label>
                        <select name="audit_type" required class="w-full px-4 py-2.5 rounded-md">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Regular">Pemeriksaan Rutin (Terjadwal)</option>
                            <option value="Special">Pemeriksaan Khusus (Mendadak)</option>
                            <option value="FollowUp">Pemeriksaan Lanjutan (Follow Up)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-1">Bagian yang Diperiksa</label>
                        <input type="text" name="audit_scope" placeholder="Gudang Bahan Baku / Produksi / Keuangan" required class="w-full px-4 py-2.5 rounded-md">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-1">Alasan Melakukan Pemeriksaan Ini</label>
                        <textarea name="audit_objective" placeholder="Memastikan barang masuk/keluar tercatat dengan benar, atau mengecek kepatuhan SOP kebersihan" required class="w-full px-4 py-2.5 rounded-md" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2: Tim Pemeriksa -->
            <div class="mb-8 p-5 rounded-lg bg-gray-50 ts-border-left">
                <h3 class="text-lg font-semibold text-[#1a365d] mb-4 flex items-center gap-2">
                    2️⃣ Tim Pemeriksa (Auditor)
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-1">Nama Anda (Sebagai Ketua Tim Pemeriksa)</label>
                        <select id="auditor_select" name="auditor_name" placeholder="Pilih nama Anda..." required class="w-full px-4 py-2.5 rounded-md"></select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-1">NIK Anda</label>
                        <input type="text" id="auditor_nik" name="auditor_nik" readonly class="w-full px-4 py-2.5 rounded-md bg-gray-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-1">Departemen Anda</label>
                        <input type="text" id="auditor_department" name="auditor_department" readonly class="w-full px-4 py-2.5 rounded-md bg-gray-100">
                    </div>

                    <div class="flex items-start gap-3 pt-2">
                        <input type="checkbox" id="confirmation" name="confirmation" required class="mt-1 h-5 w-5 text-[#1a365d] rounded focus:ring-[#1a365d]">
                        <label for="confirmation" class="text-sm text-gray-700">
                            Saya menyatakan data di atas benar dan saya bertanggung jawab atas kebenaran informasi yang saya berikan.
                        </label>
                    </div>

                    <div class="note-box p-3 rounded-md text-sm">
                        ℹ️ Jika ada anggota tim tambahan (misal: pengamat atau ahli), tambahkan di bawah.
                    </div>

                    <div id="audit-team-container"></div>
                    <button type="button" class="btn-add py-2.5 px-4 rounded-md text-sm font-medium" onclick="addAuditTeam()">
                        + Tambah Anggota Tim (Opsional)
                    </button>
                </div>
            </div>

            <!-- Section 3 & 4: Departemen & Jadwal (muncul setelah validasi awal) -->
            <div id="audit-details" class="hidden">
                <div class="mb-8 p-5 rounded-lg bg-gray-50 ts-border-left">
                    <h3 class="text-lg font-semibold text-[#1a365d] mb-4 flex items-center gap-2">
                        3️⃣ Departemen yang Akan Di Audit
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-800 mb-1">Departemen yang Akan Di Audit</label>
                            <select id="department_select" name="department_id" placeholder="Pilih departemen..." required class="w-full px-4 py-2.5 rounded-md"></select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-800 mb-1">Penanggung Jawab di Departemen Tersebut</label>
                            <input type="text" name="pic_name" placeholder="Nama lengkap penanggung jawab" required class="w-full px-4 py-2.5 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-800 mb-1">NIK Penanggung Jawab (Opsional)</label>
                            <input type="text" name="pic_nik" placeholder="Contoh: 123456" class="w-full px-4 py-2.5 rounded-md">
                        </div>
                    </div>
                </div>

                <div class="mb-8 p-5 rounded-lg bg-gray-50 ts-border-left">
                    <h3 class="text-lg font-semibold text-[#1a365d] mb-4 flex items-center gap-2">
                        4️⃣ Jadwal Pemeriksaan
                    </h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-1">Tanggal Pemeriksaan</label>
                        <input type="date" name="audit_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 rounded-md">
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full py-3.5 rounded-lg font-bold text-base">
                    ✅ Mulai Pemeriksaan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Data untuk JS -->
<script>
    const AUDITORS = @json($auditors);
    const DEPARTMENTS = @json($departments->map(fn($d) => ['id' => $d->id, 'name' => $d->name]));
</script>

<!-- TomSelect JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<!-- Custom JS (pastikan tetap kompatibel) -->
<script src="{{ asset('js/form.js') }}"></script>

</body>
</html>
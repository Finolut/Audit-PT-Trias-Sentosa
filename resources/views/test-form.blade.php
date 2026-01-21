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
    <link rel="stylesheet" href="form-pemeriksaan.css">
</head>
<body>

<div class="container">
    <h2>📝 Form Pemeriksaan Internal</h2>

    <form method="POST" action="{{ route('audit.start') }}" id="auditForm">
        @csrf

        <div class="section">
            <h3>1️⃣ Informasi Pemeriksaan</h3>

            <label>Jenis Pemeriksaan</label>
            <select name="audit_type" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="Regular">Pemeriksaan Rutin (Terjadwal)</option>
                <option value="Special">Pemeriksaan Khusus (Mendadak)</option>
                <option value="FollowUp">Pemeriksaan Lanjutan (Follow Up)</option>
            </select>

            <label>Bagian yang Diperiksa</label>
            <input type="text" name="audit_scope" placeholder="Gudang Bahan Baku / Produksi / Keuangan" required>

            <label>Alasan Melakukan Pemeriksaan Ini</label>
            <textarea name="audit_objective" placeholder="Memastikan barang masuk/keluar tercatat dengan benar, atau mengecek kepatuhan SOP kebersihan" required></textarea>
        </div>

        <div class="section">
            <h3>2️⃣ Tim Pemeriksa (Auditor)</h3>

            <label>Nama Anda (Sebagai Ketua Tim Pemeriksa)</label>
            <select id="auditor_select" name="auditor_name" placeholder="Pilih nama Anda..." required></select>

            <label>NIK Anda</label>
            <input type="text" id="auditor_nik" name="auditor_nik" readonly>

            <label>Departemen Anda</label>
            <input type="text" id="auditor_department" name="auditor_department" readonly>

            <div class="confirmation-checkbox">
                <input type="checkbox" id="confirmation" name="confirmation" required>
                <label for="confirmation">
                    Saya menyatakan data di atas benar dan saya bertanggung jawab atas kebenaran informasi yang saya berikan.
                </label>
            </div>

            <div class="note">
                ℹ️ Jika ada anggota tim tambahan (misal: pengamat atau ahli), tambahkan di bawah.
            </div>

            <div id="audit-team-container"></div>
            <button type="button" class="btn-add" onclick="addAuditTeam()">+ Tambah Anggota Tim (Opsional)</button>
        </div>

        <div id="audit-details" class="hidden">
            <div class="section">
                <h3>3️⃣ Departemen yang Akan Di Audit</h3>

                <label>Departemen yang Akan Di Audit</label>
                <select id="department_select" name="department_id" placeholder="Pilih departemen..." required></select>

                <label>Penanggung Jawab di Departemen Tersebut</label>
                <input type="text" name="pic_name" placeholder="Nama lengkap penanggung jawab" required>

                <label>NIK Penanggung Jawab (Opsional)</label>
                <input type="text" name="pic_nik" placeholder="Contoh: 123456">
            </div>

            <div class="section">
                <h3>4️⃣ Jadwal Pemeriksaan</h3>

                <label>Tanggal Pemeriksaan</label>
                <input type="date" name="audit_date" value="{{ date('Y-m-d') }}" required>
            </div>

            <button type="submit" class="btn-primary">✅ Mulai Pemeriksaan</button>
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
<script src="form-pemeriksaan.js"></script>

</body>
</html>
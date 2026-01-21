<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Pemeriksaan</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
</head>
<body>

    <div class="mobile-container">
        <header class="header">
            <h3>📝 Pemeriksaan Internal</h3>
            <p>Isi data pemeriksaan di bawah ini.</p>
        </header>

        <form method="POST" action="{{ route('audit.start') }}" id="auditForm">
            @csrf

            <section class="section">
                <h4 class="section-title">Informasi Umum</h4>
                
                <div class="input-group">
                    <label>Jenis Pemeriksaan</label>
                    <select id="audit_type" name="audit_type" required>
                        <option value="">Pilih Jenis...</option>
                        <option value="Regular">Rutin (Terjadwal)</option>
                        <option value="Special">Khusus (Mendadak)</option>
                        <option value="FollowUp">Follow Up</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Area / Lingkup</label>
                    <input type="text" name="audit_scope" placeholder="Cth: Gudang Bahan Baku" required>
                </div>

                <div class="input-group">
                    <label>Tujuan Pemeriksaan</label>
                    <textarea name="audit_objective" placeholder="Singkat saja, misal: Cek stok opname" required></textarea>
                </div>
            </section>

            <section class="section">
                <h4 class="section-title">Data Auditor</h4>
                
                <div class="input-group">
                    <label>Ketua Tim (Anda)</label>
                    <select id="auditor_select" name="auditor_name" placeholder="Cari nama Anda..." required></select>
                </div>

                <div id="auditor-details" class="details-grid hidden">
                    <div class="input-group">
                        <label>NIK</label>
                        <input type="text" id="auditor_nik" name="auditor_nik" readonly class="readonly-input">
                    </div>
                    <div class="input-group">
                        <label>Departemen</label>
                        <input type="text" id="auditor_department" name="auditor_department" readonly class="readonly-input">
                    </div>
                </div>

                <div class="checkbox-wrapper">
                    <input type="checkbox" id="confirmation" name="confirmation" required>
                    <label for="confirmation">Data di atas sudah benar.</label>
                </div>

                <div id="audit-team-container"></div>
                
                <button type="button" class="btn-outline" onclick="addAuditTeam()">
                    + Tambah Anggota Tim
                </button>
            </section>

            <section class="section hidden" id="target-section">
                <h4 class="section-title">Target Pemeriksaan</h4>

                <div class="input-group">
                    <label>Departemen Diperiksa</label>
                    <select id="department_select" name="department_id" placeholder="Cari departemen..." required></select>
                </div>

                <div class="input-group">
                    <label>PIC (Penanggung Jawab)</label>
                    <input type="text" name="pic_name" placeholder="Nama PIC" required>
                </div>
                
                <div class="input-group">
                    <label>Tanggal Audit</label>
                    <input type="date" name="audit_date" value="{{ date('Y-m-d') }}" required>
                </div>
            </section>

            <div class="bottom-action">
                <button type="submit" class="btn-submit">Mulai Pemeriksaan</button>
            </div>
        </form>
    </div>

    <script>
        const AUDITORS = @json($auditors);
        const DEPARTMENTS = @json($departments->map(fn($d) => ['id' => $d->id, 'name' => $d->name]));
    </script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script src="{{ asset('js/form.js') }}"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Pemeriksaan Internal</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
</head>
<body class="form-body">
    
    <div class="form-container">
        <header class="form-header">
            <h1 class="form-title">📝 Pemeriksaan Internal</h1>
            <p class="form-subtitle">Lengkapi data audit di bawah ini</p>
        </header>

        <form method="POST" action="{{ route('audit.start') }}" id="auditForm">
            @csrf

            <div class="card">
                <div class="card-header">
                    <span class="step-badge">1</span> Informasi Dasar
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label" for="audit_type">Jenis Pemeriksaan</label>
                        <select id="audit_type" name="audit_type" class="form-select-native" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Regular">Rutin (Terjadwal)</option>
                            <option value="Special">Khusus (Mendadak)</option>
                            <option value="FollowUp">Lanjutan (Follow Up)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="audit_scope">Lingkup / Area</label>
                        <input type="text" id="audit_scope" name="audit_scope" class="form-input" placeholder="Cth: Gudang Bahan Baku" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="audit_objective">Tujuan Pemeriksaan</label>
                        <textarea id="audit_objective" name="audit_objective" class="form-textarea" placeholder="Tulis tujuan singkat..." required></textarea>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <span class="step-badge">2</span> Data Tim Pemeriksa
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Ketua Tim (Anda)</label>
                        <select id="auditor_select" name="auditor_name" placeholder="Ketik nama Anda..." required></select>
                    </div>

                    <div class="readonly-wrapper">
                        <div class="form-row-mobile">
                            <div class="form-group half">
                                <label class="sub-label">NIK</label>
                                <input type="text" id="auditor_nik" name="auditor_nik" class="form-input readonly" readonly>
                            </div>
                            <div class="form-group half">
                                <label class="sub-label">Departemen</label>
                                <input type="text" id="auditor_department" name="auditor_department" class="form-input readonly" readonly>
                            </div>
                        </div>
                    </div>

                    <div id="audit-team-container"></div>
                    
                    <button type="button" class="btn-dashed" onclick="addAuditTeam()">
                        + Tambah Anggota Tim
                    </button>

                    <div class="confirmation-box">
                        <label class="checkbox-container">
                            <input type="checkbox" id="confirmation" name="confirmation" required>
                            <span class="checkmark"></span>
                            <span class="text">Saya bertanggung jawab atas data ini.</span>
                        </label>
                    </div>
                </div>
            </div>

            <div id="audit-details" class="hidden-section">
                <div class="card">
                    <div class="card-header">
                        <span class="step-badge">3</span> Target & Waktu
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Departemen Diperiksa</label>
                            <select id="department_select" name="department_id" placeholder="Cari departemen..." required></select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">PIC (Penanggung Jawab)</label>
                            <input type="text" id="pic_name" name="pic_name" class="form-input" placeholder="Nama PIC" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tanggal Pemeriksaan</label>
                            <input type="date" id="audit_date" name="audit_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-actions sticky-bottom">
                    <button type="submit" class="btn-primary-block">
                        Mulai Pemeriksaan
                    </button>
                </div>
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
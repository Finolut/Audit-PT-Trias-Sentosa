<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Pemeriksaan Internal</title>

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">

    <!-- TomSelect CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1d4ed8;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-600: #475569;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="form-body">
    <div class="form-container">
        <header class="form-header">
            <h1 class="form-title">📝 Form Pemeriksaan Internal</h1>
            <p class="form-subtitle">Silakan lengkapi informasi audit untuk memulai proses pemeriksaan</p>
        </header>

        <form method="POST" action="{{ route('audit.start') }}" id="auditForm">
            @csrf

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">1️⃣ Informasi Pemeriksaan</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label" for="audit_type">Jenis Pemeriksaan</label>
                        <div class="form-control-container">
                            <select id="audit_type" name="audit_type" class="form-select" required>
                                <option value="">-- Pilih Jenis Pemeriksaan --</option>
                                <option value="Regular">Pemeriksaan Rutin (Terjadwal)</option>
                                <option value="Special">Pemeriksaan Khusus (Mendadak)</option>
                                <option value="FollowUp">Pemeriksaan Lanjutan (Follow Up)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="audit_scope">Bagian yang Diperiksa</label>
                        <div class="form-control-container">
                            <input type="text" id="audit_scope" name="audit_scope" class="form-input" placeholder="Contoh: Gudang Bahan Baku / Produksi / Keuangan" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="audit_objective">Alasan Melakukan Pemeriksaan Ini</label>
                        <div class="form-control-container">
                            <textarea id="audit_objective" name="audit_objective" class="form-textarea" placeholder="Contoh: Memastikan barang masuk/keluar tercatat dengan benar, atau mengecek kepatuhan SOP kebersihan" required></textarea>
                        </div>
                        <p class="form-hint">Jelaskan secara singkat tujuan dan fokus pemeriksaan yang akan dilakukan</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">2️⃣ Tim Pemeriksa (Auditor)</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label" for="auditor_select">Nama Anda (Sebagai Ketua Tim Pemeriksa)</label>
                        <div class="form-control-container">
                            <select id="auditor_select" name="auditor_name" class="form-select" placeholder="Pilih nama Anda..." required></select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="auditor_nik">NIK Anda</label>
                            <div class="form-control-container">
                                <input type="text" id="auditor_nik" name="auditor_nik" class="form-input" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="auditor_department">Departemen Anda</label>
                            <div class="form-control-container">
                                <input type="text" id="auditor_department" name="auditor_department" class="form-input" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="confirmation-box">
                            <input type="checkbox" id="confirmation" name="confirmation" class="confirmation-input" required>
                            <label for="confirmation" class="confirmation-label">
                                Saya menyatakan data di atas benar dan saya bertanggung jawab atas kebenaran informasi yang saya berikan.
                            </label>
                        </div>
                    </div>

                    <div class="info-box">
                        <i class="info-icon">ℹ️</i>
                        <div class="info-content">
                            <p class="info-title">Informasi Tambahan</p>
                            <p class="info-text">Jika ada anggota tim tambahan (misal: pengamat atau ahli), tambahkan di bawah.</p>
                        </div>
                    </div>

                    <div id="audit-team-container" class="team-container"></div>
                    <button type="button" class="btn-tertiary" onclick="addAuditTeam()">
                        <i class="btn-icon">+</i> Tambah Anggota Tim (Opsional)
                    </button>
                </div>
            </div>

            <div id="audit-details" class="hidden">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">3️⃣ Departemen yang Akan Di Audit</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label" for="department_select">Departemen yang Akan Di Audit</label>
                            <div class="form-control-container">
                                <select id="department_select" name="department_id" class="form-select" placeholder="Pilih departemen..." required></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="pic_name">Penanggung Jawab di Departemen Tersebut</label>
                            <div class="form-control-container">
                                <input type="text" id="pic_name" name="pic_name" class="form-input" placeholder="Nama lengkap penanggung jawab" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="pic_nik">NIK Penanggung Jawab (Opsional)</label>
                            <div class="form-control-container">
                                <input type="text" id="pic_nik" name="pic_nik" class="form-input" placeholder="Contoh: 123456">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">4️⃣ Jadwal Pemeriksaan</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label" for="audit_date">Tanggal Pemeriksaan</label>
                            <div class="form-control-container">
                                <input type="date" id="audit_date" name="audit_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="btn-icon">✅</i> Mulai Pemeriksaan
                    </button>
                </div>
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
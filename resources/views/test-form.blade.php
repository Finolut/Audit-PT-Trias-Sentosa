<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Pemeriksaan Internal</title>
    <meta name="description" content="Form audit internal yang responsif dan mudah digunakan di semua perangkat">

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
        
        /* Mobile-specific styles */
        @media (max-width: 480px) {
            :root {
                --radius-lg: 16px;
                --radius-xl: 20px;
            }
        }
    </style>
</head>
<body class="form-body">
    <div class="mobile-container">
        <!-- Mobile Progress Indicator -->
        <div class="progress-container">
            <div class="progress-bar" id="progressBar"></div>
        </div>

        <!-- Back Button for Mobile -->
        <button class="mobile-back-btn" id="backBtn" style="display: none;">
            <i class="arrow-icon">←</i> Kembali
        </button>

        <div class="form-container">
            <header class="form-header">
                <h1 class="form-title">📝 Form Audit Internal</h1>
                <p class="form-subtitle">Lengkapi informasi untuk memulai pemeriksaan</p>
            </header>

            <form method="POST" action="{{ route('audit.start') }}" id="auditForm">
                @csrf

                <!-- Section 1: Informasi Pemeriksaan -->
                <div class="form-section active" id="section1">
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-badge">1</div>
                            <h2 class="section-title">Informasi Pemeriksaan</h2>
                        </div>
                        
                        <div class="section-content">
                            <div class="form-group">
                                <label class="form-label">Jenis Pemeriksaan</label>
                                <div class="btn-group-toggle">
                                    <input type="radio" id="regular" name="audit_type" value="Regular" required>
                                    <label for="regular" class="toggle-btn">Rutin</label>
                                    
                                    <input type="radio" id="special" name="audit_type" value="Special" required>
                                    <label for="special" class="toggle-btn">Khusus</label>
                                    
                                    <input type="radio" id="followup" name="audit_type" value="FollowUp" required>
                                    <label for="followup" class="toggle-btn">Follow Up</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Bagian yang Diperiksa</label>
                                <div class="form-control-container">
                                    <input type="text" name="audit_scope" class="form-input" placeholder="Contoh: Gudang, Produksi, Keuangan" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tujuan Pemeriksaan</label>
                                <div class="form-control-container">
                                    <textarea name="audit_objective" class="form-textarea" placeholder="Jelaskan tujuan pemeriksaan secara singkat..." required></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="section-footer">
                            <button type="button" class="btn-next" onclick="nextSection(1, 2)">
                                Lanjutkan <i class="arrow-icon">→</i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Tim Pemeriksa -->
                <div class="form-section" id="section2">
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-badge">2</div>
                            <h2 class="section-title">Tim Pemeriksa (Auditor)</h2>
                        </div>
                        
                        <div class="section-content">
                            <div class="form-group">
                                <label class="form-label">Ketua Tim Pemeriksaan</label>
                                <div class="form-control-container">
                                    <select id="auditor_select" name="auditor_name" class="form-select" placeholder="Pilih nama Anda..." required></select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">NIK Anda</label>
                                    <div class="form-control-container">
                                        <input type="text" id="auditor_nik" name="auditor_nik" class="form-input" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Departemen</label>
                                    <div class="form-control-container">
                                        <input type="text" id="auditor_department" name="auditor_department" class="form-input" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="confirmation-box">
                                    <input type="checkbox" id="confirmation" name="confirmation" class="confirmation-input" required>
                                    <label for="confirmation" class="confirmation-label">
                                        Saya bertanggung jawab atas kebenaran informasi yang saya berikan
                                    </label>
                                </div>
                            </div>

                            <div class="info-box">
                                <i class="info-icon">ℹ️</i>
                                <p class="info-text">Tambahkan anggota tim tambahan jika diperlukan (opsional)</p>
                            </div>

                            <div id="audit-team-container"></div>
                            <button type="button" class="btn-add-team" onclick="addAuditTeam()">
                                <i class="btn-icon">+</i> Tambah Anggota Tim
                            </button>
                        </div>
                        
                        <div class="section-footer">
                            <div class="footer-buttons">
                                <button type="button" class="btn-back" onclick="prevSection(2, 1)">
                                    <i class="arrow-icon">←</i> Kembali
                                </button>
                                <button type="button" class="btn-next" onclick="nextSection(2, 3)">
                                    Lanjutkan <i class="arrow-icon">→</i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Departemen & Jadwal -->
                <div class="form-section" id="section3">
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-badge">3</div>
                            <h2 class="section-title">Detail Audit</h2>
                        </div>
                        
                        <div class="section-content">
                            <div class="form-group">
                                <label class="form-label">Departemen yang Diaudit</label>
                                <div class="form-control-container">
                                    <select id="department_select" name="department_id" class="form-select" placeholder="Pilih departemen..." required></select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Penanggung Jawab</label>
                                <div class="form-control-container">
                                    <input type="text" name="pic_name" class="form-input" placeholder="Nama lengkap PIC" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">NIK Penanggung Jawab</label>
                                <div class="form-control-container">
                                    <input type="text" name="pic_nik" class="form-input" placeholder="Opsional">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tanggal Pemeriksaan</label>
                                <div class="form-control-container">
                                    <input type="date" name="audit_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="section-footer">
                            <div class="footer-buttons">
                                <button type="button" class="btn-back" onclick="prevSection(3, 2)">
                                    <i class="arrow-icon">←</i> Kembali
                                </button>
                                <button type="submit" class="btn-submit">
                                    <i class="btn-icon">✅</i> Mulai Audit
                                </button>
                            </div>
                        </div>
                    </div>
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

    <!-- Custom JS -->
    <script src="{{ asset('js/form.js') }}"></script>
</body>
</html>
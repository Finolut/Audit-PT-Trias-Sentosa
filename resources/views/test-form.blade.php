<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Internal Audit Charter | PT Trias Sentosa Tbk</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css">

    <style>
        :root {
            --navy: #0c2d5a;
            --slate: #475569;
            --slate-dark: #1e293b;
        }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc;
        }
        .hero-section {
            background: linear-gradient(rgba(12, 45, 90, 0.95), rgba(12, 45, 90, 0.95)), url('industrial-bg.jpg');
            background-size: cover;
            background-position: center;
            min-height: 300px;
            display: flex;
            align-items: center;
        }
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--slate-dark);
            margin: 2.5rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .section-description {
            color: var(--slate);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        .form-input {
            border: 1px solid #cbd5e1;
            border-radius: 0.375rem;
            padding: 0.75rem 1rem;
            width: 100%;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--navy);
            box-shadow: 0 0 0 2px rgba(12, 45, 90, 0.1);
        }
        .main-cta {
            background: var(--navy);
            color: white;
            border-radius: 0.5rem;
            padding: 0.75rem 2rem;
            font-size: 1.125rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.2s;
            width: 100%;
            max-width: 320px;
            margin: 2rem auto 0;
            text-align: center;
        }
        .main-cta:hover:not(:disabled) {
            background: #0a2445;
        }
        .main-cta:disabled {
            opacity: 0.75;
            cursor: not-allowed;
        }
        /* TomSelect override */
        .ts-wrapper .ts-control {
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem 0.75rem !important;
            min-height: 40px !important;
        }
        .ts-wrapper.focus .ts-control {
            border-color: var(--navy) !important;
            box-shadow: 0 0 0 2px rgba(12, 45, 90, 0.1) !important;
        }
        .ts-dropdown {
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1) !important;
        }
        .ts-dropdown .option {
            padding: 0.5rem 0.75rem !important;
        }
        .ts-dropdown .option:hover, .ts-dropdown .option.highlight {
            background-color: rgba(12, 45, 90, 0.08) !important;
            color: var(--slate-dark) !important;
        }
    </style>
</head>
<body class="text-slate-800">

<!-- Mini Hero Section -->
<section class="hero-section text-white">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold mb-3">INTERNAL AUDIT CHARTER</h1>
        <p class="text-xl opacity-90 max-w-3xl">Formalize your audit planning in compliance with ISO 19011 standards for enterprise-grade assurance</p>
    </div>
</section>

<!-- Form Content -->
<div class="max-w-4xl mx-auto px-4 py-8">
    <form id="audit-charter-form" action="{{ route('audit.start') }}" method="POST">
        @csrf
        <!-- HIDDEN FIELDS - POSISI BENAR DI ATAS -->
        <input type="hidden" name="audit_status" value="Planned">
        <input type="hidden" name="created_at" value="{{ date('Y-m-d H:i:s') }}">
        <input type="hidden" id="auditor_dept_id" name="auditor_dept_id" value="">

        <!-- Section 1: Identitas & Standar Audit -->
        <section>
            <h2 class="section-title">Identitas & Standar Audit</h2>
            <p class="section-description">Formalize the foundational elements of your audit engagement per ISO 19011 requirements</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700">Audit Number (ID)</label>
                    <input type="text" name="audit_code" value="IA-{{ date('Y') }}-{{ rand(1000,9999) }}" readonly 
                           class="form-input bg-slate-50 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700">Jenis Pemeriksaan</label>
                    <select name="audit_type" required class="form-input">
                        <option value="First Party">Pihak Pertama (Internal Rutin)</option>
                        <option value="Follow Up">Pemeriksaan Lanjutan (Corrective Action)</option>
                        <option value="Investigative">Investigasi Khusus (Insidentil)</option>
                        <option value="Surprise">Mendadak (Unannounced)</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1 text-slate-700">Referensi Standar / Kriteria Audit</label>
                    <select id="select-standards" name="audit_standards[]" multiple 
                            placeholder="Pilih standar yang relevan..." required 
                            class="form-input">
                        <option value="ISO 9001:2015">ISO 9001:2015 (Mutu)</option>
                        <option value="ISO 14001:2015">ISO 14001:2015 (Lingkungan)</option>
                        <option value="ISO 45001:2018">ISO 45001:2018 (K3)</option>
                        <option value="Company SOP">SOP / Kebijakan Perusahaan</option>
                        <option value="Regulatory">Peraturan Pemerintah / UU</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- Section 2: Tujuan & Lingkup -->
        <section class="mt-10">
            <h2 class="section-title">Tujuan & Lingkup (Objective & Scope)</h2>
            <p class="section-description">Define the strategic purpose and operational boundaries of the audit engagement</p>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700">Audit Objective (Tujuan)</label>
                    <textarea name="audit_objective" rows="3" class="form-input" 
                        placeholder="Contoh: Mengevaluasi efektivitas pengendalian stok gudang dan kepatuhan terhadap prosedur FIFO." required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700">Audit Scope (Lingkup)</label>
                    <select id="select-scope" name="audit_scope[]" multiple 
                            placeholder="Pilih batasan area audit..." required 
                            class="form-input">
                        <option value="Process-Procurement">Proses Pengadaan</option>
                        <option value="Process-Production">Proses Produksi</option>
                        <option value="Process-Finance">Laporan Keuangan</option>
                        <option value="Physical-Asset">Fisik Aset / Inventaris</option>
                        <option value="HR-Competency">Kompetensi SDM</option>
                        <option value="Digital-Security">Keamanan Data / IT</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2 text-slate-700">Metodologi Pemeriksaan</label>
                    <div class="flex flex-wrap gap-3">
                        <label class="flex items-center space-x-2 bg-white px-3 py-2 rounded border border-slate-200 cursor-pointer hover:bg-slate-50">
                            <input type="checkbox" name="methodology[]" value="Document Review" class="rounded text-slate-800 focus:ring-slate-800">
                            <span class="text-sm">Document Review</span>
                        </label>
                        <label class="flex items-center space-x-2 bg-white px-3 py-2 rounded border border-slate-200 cursor-pointer hover:bg-slate-50">
                            <input type="checkbox" name="methodology[]" value="Interview" class="rounded text-slate-800 focus:ring-slate-800">
                            <span class="text-sm">Wawancara (Interview)</span>
                        </label>
                        <label class="flex items-center space-x-2 bg-white px-3 py-2 rounded border border-slate-200 cursor-pointer hover:bg-slate-50">
                            <input type="checkbox" name="methodology[]" value="Observation" class="rounded text-slate-800 focus:ring-slate-800">
                            <span class="text-sm">Observasi Lapangan</span>
                        </label>
                        <label class="flex items-center space-x-2 bg-white px-3 py-2 rounded border border-slate-200 cursor-pointer hover:bg-slate-50">
                            <input type="checkbox" name="methodology[]" value="Sampling" class="rounded text-slate-800 focus:ring-slate-800">
                            <span class="text-sm">Sampling Fisik</span>
                        </label>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3: Tim Pemeriksa -->
        <section class="mt-10">
            <h2 class="section-title">Tim Pemeriksa (Audit Team)</h2>
            <p class="section-description">Ensure team composition meets independence requirements per ISO 19011 clause 5.3</p>
            
            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-2">
                    <span class="text-slate-500">⚠️</span>
                    <div>
                        <strong class="text-slate-700">Prinsip Independensi:</strong> 
                        Auditor tidak boleh mengaudit departemen dimana ia bekerja atau memiliki tanggung jawab langsung.
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700">Lead Auditor</label>
                    <select id="select-auditor" name="lead_auditor_id" 
                            placeholder="Pilih Lead Auditor..." required 
                            class="form-input">
                        <!-- Data akan diisi via JavaScript -->
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700">Deklarasi Kepatuhan</label>
                    <div class="flex items-start gap-3 pt-2">
                        <input type="checkbox" id="independence_decl" name="independence_decl" required 
                               class="mt-1 h-5 w-5 text-slate-800 rounded focus:ring-slate-800">
                        <label for="independence_decl" class="text-sm text-slate-600">
                            Saya menyatakan <strong>bebas dari konflik kepentingan</strong> terhadap area yang akan diaudit dan akan bertindak objektif.
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-slate-700">Anggota Tim Tambahan</label>
                <div id="team-container" class="space-y-4"></div>
                <button type="button" id="add-team-btn" 
                        class="mt-3 text-sm text-slate-600 font-medium hover:text-slate-800 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Auditor / Observer / Expert
                </button>
            </div>
        </section>

        <!-- Section 4: Target Audit & Jadwal -->
        <section class="mt-10">
            <h2 class="section-title">Target Audit & Jadwal</h2>
            <p class="section-description">Schedule the operational execution phase with precise timing and resource allocation</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700">Departemen Auditee</label>
                    <select id="select-department" name="auditee_dept_id" 
                            placeholder="Pilih Departemen..." required 
                            class="form-input">
                        <!-- Data akan diisi via JavaScript -->
                    </select>
                    <div id="conflict-warning" class="hidden text-xs text-red-600 font-bold mt-1">
                        ⛔ KONFLIK: Lead Auditor berasal dari departemen ini!
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700">PIC / Penanggung Jawab Area</label>
                    <input type="text" name="auditee_pic" placeholder="Nama Manager/SPV Area" 
                           class="form-input">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700">Tanggal Audit</label>
                    <input type="date" name="audit_date" required class="form-input">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700">Jam Mulai</label>
                    <input type="time" name="start_time" required class="form-input">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700">Estimasi Selesai</label>
                    <input type="time" name="end_time" required class="form-input">
                </div>
            </div>
        </section>

        <!-- CTA Utama -->
        <div class="mt-12 text-center">
            <button type="submit" id="submit-btn" class="main-cta">
                Start Audit Process <span>→</span>
            </button>
            <p class="text-slate-500 text-sm mt-2">Formulir ini akan memulai proses audit sesuai standar ISO 19011</p>
        </div>
    </form>
</div>

<div class="absolute top-6 right-6 z-20">
    <a href="{{ route('audit.resume.form') }}" 
       class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white text-sm font-semibold transition">
       <span>📂</span> Lanjutkan Audit Tertunda
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- DATA DARI LARAVEL ---
    const DEPARTMENTS = @json($departments); 
    const AUDITORS = @json($auditorsList); 

    // --- GLOBAL VARIABLES ---
    let deptSelect = null;
    let memberCount = 0;
    let formSubmitted = false;

    // --- PROTEKSI DOUBLE SUBMIT ---
    const form = document.getElementById('audit-charter-form');
    const submitBtn = document.getElementById('submit-btn');
    
    form.addEventListener('submit', function(e) {
        if (formSubmitted) {
            e.preventDefault();
            return;
        }
        formSubmitted = true;
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Processing... <span>→</span>';
    });

    // --- INITIALIZATION ---

    // Standards & Scope
    new TomSelect('#select-standards', { 
        plugins: ['remove_button'],
        maxItems: null,
        placeholder: 'Pilih standar yang relevan...'
    });
    
    new TomSelect('#select-scope', { 
        plugins: ['remove_button'],
        maxItems: null,
        placeholder: 'Pilih batasan area audit...'
    });

    // Lead Auditor (HANYA INI YANG PAKAI TOMSELECT)
    new TomSelect('#select-auditor', {
        valueField: 'nik',
        labelField: 'name',
        searchField: 'name',
        options: AUDITORS,
        create: false,
        placeholder: 'Pilih Lead Auditor...',
        onChange: function(value) {
            const auditor = AUDITORS.find(a => a.nik == value);
            if(auditor) {
                document.getElementById('auditor_dept_id').value = auditor.dept;
                validateIndependence();
            }
        }
    });

    // Departemen Auditee
    deptSelect = new TomSelect('#select-department', {
        valueField: 'id',
        labelField: 'name',
        searchField: 'name',
        options: DEPARTMENTS,
        create: false,
        placeholder: 'Pilih Departemen...',
        onChange: function(value) {
            validateIndependence();
        }
    });

    // --- VALIDASI INDEPENDENSI ---
    function validateIndependence() {
        const auditorDeptName = document.getElementById('auditor_dept_id').value;
        const auditeeDeptId = deptSelect.getValue();
        const selectedDeptData = DEPARTMENTS.find(d => d.id == auditeeDeptId);
        const auditeeDeptName = selectedDeptData ? selectedDeptData.name : '';

        const warning = document.getElementById('conflict-warning');
        const submitBtn = document.getElementById('submit-btn');

        if (auditorDeptName && auditeeDeptName && auditorDeptName === auditeeDeptName) {
            warning.classList.remove('hidden');
            submitBtn.disabled = true;
        } else {
            warning.classList.add('hidden');
            if (!formSubmitted) {
                submitBtn.disabled = false;
            }
        }
    }

    // --- DYNAMIC TEAM MEMBER (INPUT MANUAL) ---
    function addTeamMember() {
        memberCount++;
        const container = document.getElementById('team-container');
        
        const row = document.createElement('div');
        row.className = "p-4 bg-slate-50 rounded-lg border border-slate-200 relative";
        row.id = `member-row-${memberCount}`;

        row.innerHTML = `
            <button type="button" onclick="removeTeamMember(${memberCount})" 
                    class="absolute top-2 right-2 text-slate-400 hover:text-red-500 text-lg font-bold">×</button>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                <div>
                    <label class="text-[10px] font-bold uppercase text-slate-500 mb-1 block">Nama Personil</label>
                    <input type="text" name="audit_team[${memberCount}][name]" 
                           class="w-full text-sm border border-slate-300 rounded px-3 py-2" 
                           placeholder="Ketik nama personil...">
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase text-slate-500 mb-1 block">NIK</label>
                    <input type="text" name="audit_team[${memberCount}][nik]" 
                           class="w-full text-sm border border-slate-300 rounded px-3 py-2" 
                           placeholder="NIK (Opsional)">
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase text-slate-500 mb-1 block">Departemen</label>
                    <input type="text" name="audit_team[${memberCount}][department]" 
                           class="w-full text-sm border border-slate-300 rounded px-3 py-2" 
                           placeholder="Departemen">
                </div>
            </div>
            
            <div class="w-full">
                <label class="text-[10px] font-bold uppercase text-slate-500 mb-1 block">Peran dalam Audit</label>
                <select name="audit_team[${memberCount}][role]" 
                        class="w-full text-sm border border-slate-300 rounded px-3 py-2 bg-white">
                    <option value="Auditor">Auditor Anggota</option>
                    <option value="Observer">Observer (Pengamat)</option>
                    <option value="Technical Expert">Tenaga Ahli Teknis</option>
                </select>
            </div>
        `;

        container.appendChild(row);
    }

    // Fungsi hapus anggota tim
    window.removeTeamMember = function(index) {
        const row = document.getElementById(`member-row-${index}`);
        if (row) row.remove();
    };

    // Event listener tombol tambah
    document.getElementById('add-team-btn').addEventListener('click', addTeamMember);
});
</script>
</body>
</html>
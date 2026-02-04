<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Internal Audit | PT Trias Sentosa Tbk</title>
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
            background:
                linear-gradient(
                    rgba(12, 45, 90, 0.88),
                    rgba(12, 45, 90, 0.88)
                ),
                url('https://media.licdn.com/dms/image/v2/D563DAQEpYdKv0Os29A/image-scale_191_1128/image-scale_191_1128/0/1690510724603/pt_trias_sentosa_tbk_cover?e=2147483647&v=beta&t=dOGhpl6HrbRAla_mDVT5azyevrvu-cOGFxPcrlizZ6M');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 260px;
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
    <div class="max-w-7xl mx-auto px-4 lg:px-6">
        <h1 class="text-3xl md:text-4xl font-bold mb-3">
            INTERNAL AUDIT
        </h1>
        <p class="text-base md:text-lg opacity-90 max-w-3xl">
            Official charter defining the objectives, scope, and criteria of internal audits in accordance with ISO 14001.
        </p>
    </div>
</section>

<!-- Form Content -->
<div class="max-w-7xl mx-auto px-4 lg:px-6 py-8">
    <form id="audit-charter-form" action="{{ route('audit.start') }}" method="POST">
        @csrf
        <!-- HIDDEN FIELDS - POSISI BENAR DI ATAS -->
        <input type="hidden" name="audit_status" value="Planned">
        <input type="hidden" name="created_at" value="{{ date('Y-m-d H:i:s') }}">
        <input type="hidden" id="auditor_dept_id" name="auditor_dept_id" value="">

        <!-- Section 1: Identitas & Standar Audit -->
        <section>
            <h2 class="section-title">Identitas & Standar Audit</h2>
    
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
<div>
    <label class="block text-sm font-semibold mb-1 text-slate-700">Referensi Standar / Kriteria Audit</label>
    <select id="select-standards" name="audit_standards[]" multiple 
            required 
            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <option value="ISO 9001:2015 (Quality Management System)">ISO 9001:2015 (Quality Management System)</option>
        <option value="ISO 14001:2015 (Environmental Management System)">ISO 14001:2015 (Environmental Management System)</option>
    </select>
</div>
                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700">Referensi Standar / Kriteria Audit</label>
                    <select id="select-standards" name="audit_standards[]" multiple 
                            placeholder="Pilih standar yang relevan..." required 
                            class="form-input">
                        <option value="ISO 14001:2015 (Environmental Management System)">ISO 14001:2015 (Environmental Management System)</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- Section 2: Tujuan & Lingkup -->
        <section class="mt-10">
            <h2 class="section-title">Tujuan & Lingkup (Objective & Scope)</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700">Audit Objective (Tujuan)</label>
                    <textarea name="audit_objective" rows="3" class="form-input" 
                        placeholder="Contoh: Mengevaluasi efektivitas pengendalian stok gudang dan kepatuhan terhadap prosedur FIFO." required></textarea>
                </div>
                <div>
     <label class="block text-sm font-semibold mb-1 text-slate-700">Audit Scope (Lingkup)</label>
    <select id="select-scope" name="audit_scope[]" multiple
            required
            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <option value="process procurement">Proses Pengadaan</option>
        <option value="process production">Proses Produksi</option>
        <option value="process finance">Proses Keuangan</option>
        <option value="asset physical">Aset Fisik & Inventaris</option>
        <option value="hr competency">Kompetensi Sumber Daya Manusia</option>
        <option value="it security">Keamanan Sistem Informasi & Data</option>
        <option value="document control">Kontrol Dokumen & Rekaman</option>
        <option value="management review">Tinjauan Manajemen</option>
    </select>
</div>

            </div>

            <div class="mt-6">
                <label class="block text-sm font-semibold mb-2 text-slate-700">
                    Metodologi Pemeriksaan
                </label>
                <div class="flex flex-wrap gap-3">
                    <label class="flex items-center space-x-2 bg-white px-3 py-2 rounded border border-slate-200 cursor-pointer hover:bg-slate-50">
                        <input type="checkbox" name="methodology[]" value="Document Review"
                               class="rounded text-slate-800 focus:ring-slate-800">
                        <span class="text-sm">Document Review</span>
                    </label>
                    <label class="flex items-center space-x-2 bg-white px-3 py-2 rounded border border-slate-200 cursor-pointer hover:bg-slate-50">
                        <input type="checkbox" name="methodology[]" value="Wawancara (Interview)"
                               class="rounded text-slate-800 focus:ring-slate-800">
                        <span class="text-sm">Wawancara (Interview)</span>
                    </label>
                    <label class="flex items-center space-x-2 bg-white px-3 py-2 rounded border border-slate-200 cursor-pointer hover:bg-slate-50">
                        <input type="checkbox" name="methodology[]" value="Observasi Lapangan"
                               class="rounded text-slate-800 focus:ring-slate-800">
                        <span class="text-sm">Observasi Lapangan</span>
                    </label>
                    <label class="flex items-center space-x-2 bg-white px-3 py-2 rounded border border-slate-200 cursor-pointer hover:bg-slate-50">
                        <input type="checkbox" name="methodology[]" value="Sampling Fisik"
                               class="rounded text-slate-800 focus:ring-slate-800">
                        <span class="text-sm">Sampling Fisik</span>
                    </label>
                </div>
            </div>
        </section>

        <!-- Section 3 & 4: Tim Pemeriksa + Target Audit & Jadwal (SIDE-BY-SIDE) -->
<div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Column 1: Tim Pemeriksa -->
    <section>
        <h2 class="section-title">Tim Pemeriksa (Audit Team)</h2>
    
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-semibold mb-1 text-slate-700">
                    Lead Auditor
                </label>
                <select id="select-auditor" name="lead_auditor_id"
                        required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <!-- Data via JavaScript -->
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1 text-slate-700">
                    Email Lead Auditor <span class="text-slate-400 font-normal">(opsional)</span>
                </label>
                <input type="email" name="lead_auditor_email"
                       placeholder="nama@perusahaan.com"
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-xs text-slate-500 mt-1">
                    Digunakan untuk komunikasi dan distribusi laporan audit.
                </p>
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

    <!-- Column 2: Target Audit & Jadwal -->
    <section>
        <h2 class="section-title">Target Audit & Jadwal</h2>

        <!-- Departemen Auditee (Multi) -->
        <div class="mb-6">
            <label class="block text-sm font-semibold mb-1 text-slate-700">
                Departemen yang akan di audit
            </label>
            <select id="select-department"
                    name="auditee_dept_ids[]"
                    multiple
                    required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <!-- Data via JavaScript -->
            </select>

            <div id="conflict-warning"
                 class="hidden text-xs text-red-600 font-bold mt-1">
                ⛔ KONFLIK: Lead Auditor berasal dari salah satu departemen yang dipilih!
            </div>

            <p class="text-xs text-slate-500 mt-1">
                Audit dapat mencakup lebih dari satu departemen dalam satu penugasan.
            </p>
        </div>

        <!-- Jadwal Audit -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-1 text-slate-700">
                    Tanggal Mulai Audit
                </label>
                <input type="date" name="audit_start_date" required 
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1 text-slate-700">
                    Tanggal Selesai Audit
                </label>
                <input type="date" name="audit_end_date" required 
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>

        <p class="text-xs text-slate-500 mt-2">
            Rentang tanggal digunakan untuk audit yang berlangsung lebih dari satu hari.
        </p>
    </section>
</div>

        <!-- CTA Utama -->
        <div class="mt-10 text-center">
<!-- Update tombol submit dengan animasi loading -->
<button type="submit" id="submit-btn" class="main-cta">
    <span id="btn-text">Start Audit Process</span>
    <span>→</span>
    
    <!-- Loading Icon (sama seperti admin) -->
    <svg id="loading-icon" class="hidden animate-spin ml-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
</button>
        </div>
    </form>
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

    // --- PROTEKSI DOUBLE SUBMIT DENGAN ANIMASI LOADING ---
    const form = document.getElementById('audit-charter-form');
    const submitBtn = document.getElementById('submit-btn');
    const loadingIcon = document.getElementById('loading-icon');
    const btnText = document.getElementById('btn-text');
    
    form.addEventListener('submit', function(e) {
        if (formSubmitted) {
            e.preventDefault();
            return;
        }
        
        // 1. Disable tombol agar tidak diklik 2x
        formSubmitted = true;
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
        
        // 2. Tampilkan Spinner & ubah teks
        loadingIcon.classList.remove('hidden');
        btnText.innerText = 'Processing...';
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
        const auditorDept = document.getElementById('auditor_dept_id').value;
        const auditeeDeptIds = deptSelect.getValue(); // array
        const warning = document.getElementById('conflict-warning');
        const submitBtn = document.getElementById('submit-btn');

        const conflict = auditeeDeptIds.some(id => {
            const dept = DEPARTMENTS.find(d => d.id == id);
            return dept && dept.name === auditorDept;
        });

        if (conflict) {
            warning.classList.remove('hidden');
            submitBtn.disabled = true;
        } else {
            warning.classList.add('hidden');
            submitBtn.disabled = false;
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
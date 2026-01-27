<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Internal Audit Charter Form</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f1f5f9; }
        .ts-border-left { border-left: 4px solid #0f172a; }
        /* TomSelect Override for Tailwind integration */
        .ts-control { border-radius: 0.375rem; padding: 0.6rem 0.75rem; border-color: #cbd5e1; }
        .ts-wrapper.focus .ts-control { border-color: #0f172a; box-shadow: 0 0 0 2px rgba(15, 23, 42, 0.1); }
        /* Custom Utilities */
        .section-card { background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 1.5rem; }
        .req-star { color: #ef4444; }
    </style>
</head>
<body class="py-10 px-4 text-slate-800">

<div class="max-w-4xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">📄 Internal Audit Charter</h1>
            <p class="text-slate-500 text-sm">Formulir Perencanaan Pemeriksaan Internal (ISO 19011 Compliant)</p>
        </div>
    </div>

<form action="{{ route('audit.start') }}" method="POST">
    @csrf <div id="team-container" class="space-y-2">
        <input type="hidden" name="audit_status" value="Planned"> <input type="hidden" name="created_at" value="{{ date('Y-m-d H:i:s') }}">
        
        <div class="section-card ts-border-left">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2 border-b pb-2">
                1️⃣ Identitas & Standar Audit
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold mb-1">Audit Number (ID) <span class="req-star">*</span></label>
                    <input type="text" name="audit_code" value="IA-{{ date('Y') }}-{{ rand(1000,9999) }}" readonly 
                           class="w-full bg-slate-100 border border-slate-300 rounded-md px-4 py-2 text-slate-600 font-mono cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Jenis Pemeriksaan <span class="req-star">*</span></label>
                    <select name="audit_type" required class="w-full border-slate-300 rounded-md px-4 py-2 focus:ring-slate-800">
                        <option value="First Party">Pihak Pertama (Internal Rutin)</option>
                        <option value="Follow Up">Pemeriksaan Lanjutan (Corrective Action)</option>
                        <option value="Investigative">Investigasi Khusus (Insidentil)</option>
                        <option value="Surprise">Mendadak (Unannounced)</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Referensi Standar / Kriteria Audit <span class="req-star">*</span></label>
                    <select id="select-standards" name="audit_standards[]" multiple placeholder="Pilih standar yang relevan..." required>
                        <option value="ISO 9001:2015">ISO 9001:2015 (Mutu)</option>
                        <option value="ISO 14001:2015">ISO 14001:2015 (Lingkungan)</option>
                        <option value="ISO 45001:2018">ISO 45001:2018 (K3)</option>
                        <option value="Company SOP">SOP / Kebijakan Perusahaan</option>
                        <option value="Regulatory">Peraturan Pemerintah / UU</option>
                    </select>
                    <p class="text-xs text-slate-500 mt-1">Dokumen acuan yang menjadi dasar kepatuhan.</p>
                </div>
            </div>
        </div>

        <div class="section-card ts-border-left border-l-blue-600">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2 border-b pb-2">
                2️⃣ Tujuan & Lingkup (Objective & Scope)
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Audit Objective (Tujuan) <span class="req-star">*</span></label>
                    <textarea name="audit_objective" rows="2" class="w-full border border-slate-300 rounded-md px-4 py-2" 
                        placeholder="Contoh: Mengevaluasi efektivitas pengendalian stok gudang dan kepatuhan terhadap prosedur FIFO." required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Audit Scope (Lingkup) <span class="req-star">*</span></label>
                    <select id="select-scope" name="audit_scope[]" multiple placeholder="Pilih batasan area audit..." required>
                        <option value="Process-Procurement">Proses Pengadaan</option>
                        <option value="Process-Production">Proses Produksi</option>
                        <option value="Process-Finance">Laporan Keuangan</option>
                        <option value="Physical-Asset">Fisik Aset / Inventaris</option>
                        <option value="HR-Competency">Kompetensi SDM</option>
                        <option value="Digital-Security">Keamanan Data / IT</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Metodologi Pemeriksaan <span class="req-star">*</span></label>
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center space-x-2 bg-slate-50 px-3 py-2 rounded border border-slate-200 cursor-pointer hover:bg-slate-100">
                            <input type="checkbox" name="methodology[]" value="Document Review" class="rounded text-blue-800 focus:ring-blue-800">
                            <span class="text-sm">Document Review</span>
                        </label>
                        <label class="flex items-center space-x-2 bg-slate-50 px-3 py-2 rounded border border-slate-200 cursor-pointer hover:bg-slate-100">
                            <input type="checkbox" name="methodology[]" value="Interview" class="rounded text-blue-800 focus:ring-blue-800">
                            <span class="text-sm">Wawancara (Interview)</span>
                        </label>
                        <label class="flex items-center space-x-2 bg-slate-50 px-3 py-2 rounded border border-slate-200 cursor-pointer hover:bg-slate-100">
                            <input type="checkbox" name="methodology[]" value="Observation" class="rounded text-blue-800 focus:ring-blue-800">
                            <span class="text-sm">Observasi Lapangan</span>
                        </label>
                        <label class="flex items-center space-x-2 bg-slate-50 px-3 py-2 rounded border border-slate-200 cursor-pointer hover:bg-slate-100">
                            <input type="checkbox" name="methodology[]" value="Sampling" class="rounded text-blue-800 focus:ring-blue-800">
                            <span class="text-sm">Sampling Fisik</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="section-card ts-border-left border-l-purple-600">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2 border-b pb-2">
                3️⃣ Tim Pemeriksa (Audit Team)
            </h3>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded p-3 mb-4 text-sm text-yellow-800 flex items-start gap-2">
                <span>⚠️</span>
                <div>
                    <strong>Prinsip Independensi:</strong> Auditor tidak boleh mengaudit departemen dimana ia bekerja atau memiliki tanggung jawab langsung.
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Lead Auditor <span class="req-star">*</span></label>
                    <select id="select-auditor" name="lead_auditor_id" placeholder="Cari nama Lead Auditor..." required></select>
                    <input type="hidden" id="auditor_dept_id">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Deklarasi Kepatuhan</label>
                    <div class="flex items-start gap-3 pt-2">
                        <input type="checkbox" id="independence_decl" name="independence_decl" required 
                               class="mt-1 h-5 w-5 text-purple-600 rounded focus:ring-purple-600">
                        <label for="independence_decl" class="text-sm text-slate-600">
                            Saya menyatakan <strong>bebas dari konflik kepentingan</strong> terhadap area yang akan diaudit dan akan bertindak objektif.
                        </label>
                    </div>
                </div>
            </div>

            <label class="block text-sm font-semibold mb-2">Anggota Tim Tambahan</label>
            <div id="team-container" class="space-y-2"></div>
            <button type="button" onclick="addTeamMember()" 
                    class="mt-3 text-sm text-purple-700 font-medium hover:underline flex items-center gap-1">
                + Tambah Auditor / Observer / Expert
            </button>
        </div>

        <div class="section-card ts-border-left border-l-emerald-600">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2 border-b pb-2">
                4️⃣ Target Audit & Jadwal
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                <div>
                    <label class="block text-sm font-semibold mb-1">Departemen Auditee <span class="req-star">*</span></label>
                    <select id="select-department" name="auditee_dept_id" placeholder="Pilih Departemen..." required></select>
                    <p id="conflict-warning" class="hidden text-xs text-red-600 mt-1 font-bold">
                        ⛔ KONFLIK: Lead Auditor berasal dari departemen ini!
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">PIC / Penanggung Jawab Area</label>
                    <input type="text" name="auditee_pic" placeholder="Nama Manager/SPV Area" class="w-full border border-slate-300 rounded-md px-4 py-2">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-semibold mb-1">Tanggal Audit <span class="req-star">*</span></label>
                    <input type="date" name="audit_date" required class="w-full border border-slate-300 rounded-md px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Jam Mulai</label>
                    <input type="time" name="start_time" required class="w-full border border-slate-300 rounded-md px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Estimasi Selesai</label>
                    <input type="time" name="end_time" required class="w-full border border-slate-300 rounded-md px-4 py-2">
                </div>
            </div>
        </div>

        <div class="flex gap-4 pt-4">
<button type="submit" class="btn btn-primary">Mulai Audit</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    // --- Mock Data (Replace with Backend JSON) ---
    const DEPARTMENTS = [
        {id: 1, name: 'Warehouse & Logistics'},
        {id: 2, name: 'Production'},
        {id: 3, name: 'Finance & Accounting'},
        {id: 4, name: 'Human Resources (HR)'},
        {id: 5, name: 'IT & Security'}
    ];
    
    const AUDITORS = [
        {id: 101, name: 'Andi Saputra', dept_id: 5, nik: 'IT-001'},
        {id: 102, name: 'Budi Santoso', dept_id: 4, nik: 'HR-023'},
        {id: 103, name: 'Citra Lestari', dept_id: 3, nik: 'FA-011'}
    ];

    // --- TomSelect Initialization ---
    
    // 1. Standards (Multi)
    new TomSelect('#select-standards', { plugins: ['remove_button'] });

    // 2. Scope (Multi)
    new TomSelect('#select-scope', { plugins: ['remove_button'] });

    // 3. Lead Auditor
    new TomSelect('#select-auditor', {
        valueField: 'id',
        labelField: 'name',
        searchField: 'name',
        options: AUDITORS,
        onChange: function(value) {
            const auditor = AUDITORS.find(a => a.id == value);
            if(auditor) {
                document.getElementById('auditor_dept_id').value = auditor.dept_id;
                validateIndependence();
            }
        }
    });

    // 4. Department Auditee
    new TomSelect('#select-department', {
        valueField: 'id',
        labelField: 'name',
        searchField: 'name',
        options: DEPARTMENTS,
        onChange: function(value) {
            validateIndependence();
        }
    });

    // --- Logic: Conflict of Interest Check ---
    function validateIndependence() {
        const auditorDept = document.getElementById('auditor_dept_id').value;
        const auditeeDept = document.getElementById('select-department').value;
        const warning = document.getElementById('conflict-warning');
        const submitBtn = document.querySelector('button[type="submit"]');

        if (auditorDept && auditeeDept && auditorDept === auditeeDept) {
            warning.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            alert('PERINGATAN STANDAR ISO:\n\nAuditor tidak diperbolehkan mengaudit departemennya sendiri (Konflik Kepentingan). Silakan ganti Lead Auditor atau Departemen.');
        } else {
            warning.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    // --- Logic: Dynamic Team Member ---
    let memberCount = 0;
    function addTeamMember() {
        memberCount++;
        const html = `
            <div class="flex flex-col md:flex-row gap-3 bg-slate-50 p-3 rounded border border-slate-200 animate-fade-in" id="member-${memberCount}">
                <div class="flex-grow">
                    <input type="text" name="audit_team[${memberCount}][name]" placeholder="Nama Anggota Tim" class="w-full text-sm border-slate-300 rounded px-3 py-2">
                </div>
                <div class="w-full md:w-1/3">
                    <select name="audit_team[${memberCount}][role]"" class="w-full text-sm border-slate-300 rounded px-3 py-2 bg-white">
                        <option value="Auditor">Auditor Anggota</option>
                        <option value="Observer">Observer (Pengamat)</option>
                        <option value="Technical Expert">Tenaga Ahli Teknis</option>
                    </select>
                </div>
                <button type="button" onclick="document.getElementById('member-${memberCount}').remove()" class="text-red-500 hover:bg-red-50 px-2 rounded">✕</button>
            </div>
        `;
        document.getElementById('team-container').insertAdjacentHTML('beforeend', html);
    }

    // MOCK DATA DEPARTEMEN (Pastikan ID ini UUID sesuai database Anda)
    // Contoh: 'b1f8...' bukan '1'
    const DEPARTMENTS = [
        @foreach($departments as $dept)
            {id: '{{ $dept->id }}', name: '{{ $dept->name }}'},
        @endforeach
    ];
    
    // Ambil data auditor dari Controller (Data yang Anda berikan tadi)
    const AUDITORS = @json($auditorsList); 

    // Initialize TomSelect Lead Auditor
    new TomSelect('#select-auditor', {
        valueField: 'id',
        labelField: 'name',
        searchField: 'name',
        options: AUDITORS,
        onChange: function(value) {
            const auditor = AUDITORS.find(a => a.id == value);
            if(auditor) {
                // Simpan nama departemen auditor untuk pengecekan konflik
                document.getElementById('auditor_dept_id').value = auditor.dept;
                validateIndependence();
            }
        }
    });

    // Initialize TomSelect Departemen Auditee
    new TomSelect('#select-department', {
        valueField: 'id',
        labelField: 'name',
        searchField: 'name',
        options: DEPARTMENTS,
        onChange: function(value) {
            validateIndependence();
        }
    });

    function validateIndependence() {
        const auditorDeptName = document.getElementById('auditor_dept_id').value; // String (e.g., 'BOPET')
        const auditeeDeptSelect = document.getElementById('select-department').tomselect;
        const auditeeDeptName = auditeeDeptSelect.options[auditeeDeptSelect.getValue()]?.name;

        const warning = document.getElementById('conflict-warning');
        const submitBtn = document.querySelector('button[type="submit"]');

        if (auditorDeptName && auditeeDeptName && auditorDeptName === auditeeDeptName) {
            warning.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50');
        } else {
            warning.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50');
        }
    }

    // Perbaikan fungsi Tambah Anggota Tim
    let memberCount = 0;
    function addTeamMember() {
        memberCount++;
        const html = `
            <div class="flex gap-3 bg-slate-50 p-3 rounded" id="member-${memberCount}">
                <input type="text" name="audit_team[${memberCount}][name]" placeholder="Nama Anggota" class="flex-grow border rounded px-3 py-2">
                <select name="audit_team[${memberCount}][role]" class="border rounded px-3 py-2">
                    <option value="Auditor">Auditor</option>
                    <option value="Observer">Observer</option>
                </select>
                <button type="button" onclick="document.getElementById('member-${memberCount}').remove()">✕</button>
            </div>
        `;
        document.getElementById('team-container').insertAdjacentHTML('beforeend', html);
    }
</script>

</body>
</html>
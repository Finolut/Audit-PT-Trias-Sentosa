<!DOCTYPE html>
<html>
<head>
    <title>Sistem Audit Internal</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f3f4f6; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #111827; margin-bottom: 30px; }
        label { display: block; font-weight: 600; margin-bottom: 5px; color: #374151; }
        select, input { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; margin-bottom: 15px; box-sizing: border-box; }
        
        .section-box { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .blue-box { background: #eff6ff; border: 1px solid #bfdbfe; }
        .green-box { background: #f0fdf4; border: 1px solid #bbf7d0; }
        .pink-box { background: #fdf2f8; border: 1px solid #fbcfe8; }
        .yellow-box { background: #fffbeb; border: 1px solid #fcd34d; display: none; text-align: center; }

        .btn { width: 100%; padding: 12px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 16px; margin-top: 10px; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-warning { background: #d97706; color: white; }
        
        .hidden { display: none; }
        .responder-row { display: flex; gap: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h2>📋 Setup Audit Internal</h2>

    <div id="resume-alert" class="section-box yellow-box">
        <h3 style="margin-top:0; color:#92400e;">⚠️ Audit Belum Selesai Ditemukan</h3>
        <p>Anda memiliki audit yang sedang berjalan untuk departemen <strong id="resume-dept"></strong> tanggal <span id="resume-date"></span>.</p>
        <a id="resume-btn" href="#" class="btn btn-warning" style="display:block; text-decoration:none; box-sizing:border-box;">Lanjutkan Audit Terakhir</a>
        <br>
        <small style="color:#666;">Atau abaikan dan isi form di bawah untuk membuat audit baru.</small>
    </div>

    <form method="POST" action="{{ route('audit.start') }}">
        @csrf

        <div class="section-box blue-box">
            <h4 style="margin-top:0; color: #1e40af;">1. Identitas Auditor</h4>
            
            <label>Nama Auditor (Pilih)</label>
            <select id="auditor_select" name="auditor_name" required onchange="selectAuditor(this)">
                <option value="">-- Pilih Nama Anda --</option>
                @foreach($auditors as $aud)
                    <option value="{{ $aud['name'] }}" 
                            data-nik="{{ $aud['nik'] }}" 
                            data-dept="{{ $aud['dept'] }}">
                        {{ $aud['name'] }}
                    </option>
                @endforeach
            </select>

            <div style="display: flex; gap: 15px;">
                <div style="flex: 1;">
                    <label>NIK</label>
                    <input type="text" id="auditor_nik" name="auditor_nik" readonly style="background: #e5e7eb; cursor: not-allowed;">
                </div>
                <div style="flex: 1;">
                    <label>Departemen Asal</label>
                    <input type="text" id="auditor_department" name="auditor_department" readonly style="background: #e5e7eb; cursor: not-allowed;">
                </div>
            </div>
        </div>

        <div id="audit-details" class="hidden">
            
            <div class="section-box pink-box">
                <h4 style="margin-top:0; color: #9d174d;">2. Responder (Opsional)</h4>
                <div id="responders-container"></div>
                <button type="button" onclick="addResponder()" style="padding: 5px 10px; background: white; border: 1px solid #ccc; cursor: pointer;">+ Tambah Responder</button>
            </div>

            <div class="section-box green-box">
                <h4 style="margin-top:0; color: #166534;">3. Detail Audit Baru</h4>
                
                <label>Departemen yang Di-Audit</label>
                <select name="department_id" required>
                    <option value="">-- Pilih Departemen Target --</option>
                    @foreach ($departments as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>

                <label>Tanggal Audit</label>
                <input type="date" name="audit_date" value="{{ date('Y-m-d') }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Mulai Audit Baru</button>
        </div>

    </form>
</div>

<script>
    let responderIndex = 0;

    // Fungsi saat memilih nama dari dropdown
    async function selectAuditor(selectElement) {
        const option = selectElement.options[selectElement.selectedIndex];
        const nik = option.getAttribute('data-nik');
        const dept = option.getAttribute('data-dept');
        const detailsDiv = document.getElementById('audit-details');
        const resumeAlert = document.getElementById('resume-alert');

        // Reset
        resumeAlert.style.display = 'none';

        if (selectElement.value) {
            // Isi NIK dan Dept otomatis
            document.getElementById('auditor_nik').value = nik;
            document.getElementById('auditor_department').value = dept;
            
            // Tampilkan form bawah
            detailsDiv.classList.remove('hidden');

            // Cek ke server apakah ada audit pending
            if(nik && nik !== 'N/A') {
                checkPendingAudit(nik);
            }
        } else {
            // Kosongkan jika batal pilih
            document.getElementById('auditor_nik').value = '';
            document.getElementById('auditor_department').value = '';
            detailsDiv.classList.add('hidden');
        }
    }

    // Fungsi Ajax cek pending audit
    async function checkPendingAudit(nik) {
        try {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const res = await fetch('/audit/check-resume', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ nik: nik })
            });

            const data = await res.json();

            if (data.found) {
                // Tampilkan alert kuning
                document.getElementById('resume-alert').style.display = 'block';
                document.getElementById('resume-dept').innerText = data.dept_name;
                document.getElementById('resume-date').innerText = data.date;
                document.getElementById('resume-btn').href = data.resume_link;
            }
        } catch (error) {
            console.error('Gagal cek pending audit:', error);
        }
    }

    // Fungsi Tambah Responder
    function addResponder() {
        const container = document.getElementById('responders-container');
        const html = `
            <div class="responder-row">
                <input type="text" name="responders[${responderIndex}][name]" placeholder="Nama Responder" required style="margin-bottom:0;">
                <button type="button" onclick="this.parentElement.remove()" style="background:#fecaca; border:1px solid #ef4444; color:#b91c1c; cursor:pointer;">X</button>
            </div>
            <input type="hidden" name="responders[${responderIndex}][department]" value="">
        `;
        container.insertAdjacentHTML('beforeend', html);
        responderIndex++;
    }
</script>

</body>
</html>
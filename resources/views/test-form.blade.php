<!DOCTYPE html>
<html>
<head>
    <title>Audit Internal</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f3f4f6; padding: 20px; color: #1f2937; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        
        h2 { text-align: center; color: #111827; margin-bottom: 30px; border-bottom: 2px solid #e5e7eb; padding-bottom: 15px; }
        h4 { margin: 0 0 15px 0; font-size: 1.1rem; display: flex; align-items: center; gap: 8px; }
        
        .section-card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin-bottom: 25px; background: #fff; position: relative; overflow: hidden; }
        .section-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; }
        
        .card-1 { border-left-color: #3b82f6; } .card-1 h4 { color: #1d4ed8; }
        .card-2 { border-left-color: #8b5cf6; } .card-2 h4 { color: #6d28d9; }
        .card-3 { border-left-color: #ec4899; } .card-3 h4 { color: #be185d; }
        .card-4 { border-left-color: #10b981; } .card-4 h4 { color: #047857; }
        
        label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem; color: #4b5563; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; margin-bottom: 15px; font-size: 14px; box-sizing: border-box; }
        textarea { resize: vertical; height: 80px; }
        
        .row { display: flex; gap: 15px; }
        .col { flex: 1; }

        .dynamic-row { display: flex; gap: 10px; align-items: center; margin-bottom: 10px; background: #f9fafb; padding: 10px; border-radius: 6px; border: 1px dashed #d1d5db; }
        .btn-add { background: white; border: 1px dashed #6b7280; color: #374151; padding: 8px; width: 100%; cursor: pointer; border-radius: 6px; font-size: 13px; }
        .btn-add:hover { background: #f3f4f6; border-color: #374151; }
        .btn-remove { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; padding: 8px 12px; border-radius: 4px; cursor: pointer; }

        .btn-primary { width: 100%; padding: 15px; background: #2563eb; color: white; border: none; border-radius: 8px; font-weight: bold; font-size: 16px; cursor: pointer; transition: background 0.2s; }
        .btn-primary:hover { background: #1d4ed8; }

        .hidden { display: none; }
        .readonly-field { background-color: #f3f4f6; color: #6b7280; cursor: not-allowed; }
    </style>
</head>
<body>

<div class="container">
    <h2>📋 Form Setup Audit Internal</h2>

    <form method="POST" action="{{ route('audit.start') }}">
        @csrf

        <div class="section-card card-1">
            <h4><span>1️⃣</span> Informasi Audit</h4>
            
            <div class="row">
                <div class="col">
                    <label>Jenis Audit</label>
                    <select name="audit_type" required>
                        <option value="Regular">Regular / Terjadwal</option>
                        <option value="Special">Khusus / Insidental</option>
                        <option value="FollowUp">Follow Up</option>
                    </select>
                </div>
                <div class="col">
                    <label>Ruang Lingkup (Scope)</label>
                    <input type="text" name="audit_scope" placeholder="Contoh: Operasional Gudang, Produksi Line 1..." required>
                </div>
            </div>
            
            <label>Tujuan Audit (Objective)</label>
            <textarea name="audit_objective" placeholder="Jelaskan tujuan utama audit ini..." required></textarea>
        </div>

        <div class="section-card card-2">
            <h4><span>2️⃣</span> Auditor</h4>
            
            <label>Auditor Utama (Lead)</label>
            <select id="auditor_select" name="auditor_name" required onchange="selectAuditor(this)">
                <option value="">-- Pilih Nama Anda --</option>
                @foreach($auditors as $aud)
                    <option value="{{ $aud['name'] }}" data-nik="{{ $aud['nik'] }}" data-dept="{{ $aud['dept'] }}">
                        {{ $aud['name'] }}
                    </option>
                @endforeach
            </select>
            
            <div class="row">
                <div class="col">
                    <label>NIK</label>
                    <input type="text" id="auditor_nik" name="auditor_nik" class="readonly-field" readonly>
                </div>
                <div class="col">
                    <label>Departemen Asal</label>
                    <input type="text" id="auditor_department" name="auditor_department" class="readonly-field" readonly>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px dashed #e5e7eb; margin: 15px 0;">

            <label>Tim Audit Tambahan (Opsional)</label>
            <div id="audit-team-container"></div>
            <button type="button" class="btn-add" onclick="addAuditTeam()">+ Tambah Anggota Tim (Observer/Member)</button>
        </div>

        <div id="audit-details" class="hidden">

            <div class="section-card card-3">
                <h4><span>3️⃣</span> Auditee (Target)</h4>
                
                <label>Departemen Target</label>
                <select name="department_id" required>
                    <option value="">-- Pilih Departemen --</option>
                    @foreach ($departments as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>

                <label>PIC Auditee (Wajib)</label>
                <div style="background: #fdf2f8; padding: 10px; border-radius: 6px; border: 1px solid #fbcfe8;">
                    <div class="row" style="margin-bottom: 0;">
                        <div class="col" style="flex: 2;">
                            <input type="text" name="pic_name" placeholder="Nama PIC" required style="margin-bottom: 5px;">
                        </div>
                        <div class="col" style="flex: 1;">
                            <input type="text" name="pic_nik" placeholder="NIK PIC (Opsional)" style="margin-bottom: 5px;">
                        </div>
                    </div>
                    <small style="color: #db2777;">* PIC adalah orang yang bertanggung jawab saat diaudit.</small>
                </div>
            </div>

            <div class="section-card card-4">
                <h4><span>4️⃣</span> Pelaksanaan</h4>
                
                <div class="row">
                    <div class="col">
                        <label>Tanggal Audit</label>
                        <input type="date" name="audit_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col">
                        <label>Status Awal</label>
                        <input type="text" value="OPEN / IN PROGRESS" class="readonly-field" readonly>
                    </div>
                </div>
            </div>

            <div style="text-align: center; margin-bottom: 20px; color: #6b7280; font-size: 0.9rem;">
                ℹ️ <em>Bagian <strong>5️⃣ Penutup (Kesimpulan & Catatan)</strong> akan diisi setelah audit selesai dilakukan.</em>
            </div>

            <button type="submit" class="btn-primary">Mulai Audit & Simpan Data</button>
        </div>

    </form>
</div>

<script>
    let teamIndex = 0;

    function selectAuditor(select) {
        const opt = select.options[select.selectedIndex];
        const nik = opt.getAttribute('data-nik');
        const dept = opt.getAttribute('data-dept');
        
        if (select.value) {
            document.getElementById('auditor_nik').value = nik;
            document.getElementById('auditor_department').value = dept;
            document.getElementById('audit-details').classList.remove('hidden');
        } else {
            document.getElementById('audit-details').classList.add('hidden');
        }
    }

    function addAuditTeam() {
        const container = document.getElementById('audit-team-container');
        const html = `
            <div class="dynamic-row">
                <div style="flex:2">
                    <input type="text" name="audit_team[${teamIndex}][name]" placeholder="Nama Anggota Tim" required style="margin:0;">
                </div>
                <div style="flex:1">
                    <select name="audit_team[${teamIndex}][role]" style="margin:0;">
                        <option value="Member">Member</option>
                        <option value="Observer">Observer</option>
                        <option value="Specialist">Technical Specialist</option>
                    </select>
                </div>
                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">✕</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        teamIndex++;
    }
</script>

</body>
</html>
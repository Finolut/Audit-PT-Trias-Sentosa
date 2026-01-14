<!DOCTYPE html>
<html>
<head>
    <title>Form Pemeriksaan Internal</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            padding: 15px;
            color: #1e293b;
            line-height: 1.5;
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        h2 {
            text-align: center;
            color: #0f172a;
            margin-bottom: 25px;
            font-size: 1.6rem;
        }
        .section {
            background: #f1f5f9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 4px solid #3b82f6;
        }
        .section h3 {
            margin-top: 0;
            color: #1e40af;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        label {
            display: block;
            font-weight: bold;
            margin: 15px 0 6px 0;
            color: #1e293b;
            font-size: 1rem;
        }
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
            box-sizing: border-box;
            background: white;
        }
        textarea {
            min-height: 80px;
            resize: vertical;
        }
        .btn-add {
            background: #e2e8f0;
            color: #334155;
            border: 1px dashed #94a3b8;
            padding: 10px;
            width: 100%;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }
        .btn-add:hover {
            background: #cbd5e1;
        }
        .btn-primary {
            width: 100%;
            padding: 16px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            font-size: 18px;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn-primary:hover {
            background: #1d4ed8;
        }
        .note {
            background: #fffbeb;
            padding: 12px;
            border-radius: 8px;
            border-left: 4px solid #ca8a04;
            font-size: 14px;
            color: #92400e;
            margin: 15px 0;
        }
        .hidden { display: none; }
    </style>
</head>
<body>

<div class="container">
    <h2>📝 Form Pemeriksaan Internal</h2>

    <form method="POST" action="{{ route('audit.start') }}">
        @csrf

        <div class="section">
            <h3>1️⃣ Informasi Pemeriksaan</h3>

            <label>Jenis Pemeriksaan</label>
            <select name="audit_type" required style="font-size: 16px;">
                <option value="">-- Pilih Jenis --</option>
                <option value="Regular">Pemeriksaan Rutin (Terjadwal)</option>
                <option value="Special">Pemeriksaan Khusus (Mendadak)</option>
                <option value="FollowUp">Pemeriksaan Lanjutan (Follow Up)</option>
            </select>

            <label>Bagian yang Diperiksa</label>
            <input type="text" name="audit_scope" placeholder="Contoh: Gudang Bahan Baku, Bagian Keuangan, Produksi Lantai 2" required>

            <label>Alasan Melakukan Pemeriksaan Ini</label>
            <textarea name="audit_objective" placeholder="Contoh: Memastikan barang masuk/keluar tercatat dengan benar, atau mengecek kepatuhan SOP kebersihan" required></textarea>
        </div>

        <div class="section">
            <h3>2️⃣ Tim Pemeriksa (Auditor)</h3>

            <label>Nama Anda (Sebagai Ketua Tim Pemeriksa)</label>
            <select id="auditor_select" name="auditor_name" required onchange="selectAuditor(this)" style="font-size: 16px;">
                <option value="">-- Pilih Nama Anda --</option>
                @foreach($auditors as $aud)
                    <option value="{{ $aud['name'] }}" data-nik="{{ $aud['nik'] }}" data-dept="{{ $aud['dept'] }}">
                        {{ $aud['name'] }}
                    </option>
                @endforeach
            </select>

            <label>NIK Anda</label>
            <input type="text" id="auditor_nik" name="auditor_nik" readonly style="background: #f1f5f9;">

            <label>Departemen Anda</label>
            <input type="text" id="auditor_department" name="auditor_department" readonly style="background: #f1f5f9;">

            <div class="note">
                ℹ️ Jika ada anggota tim tambahan (misal: pengamat atau ahli), tambahkan di bawah.
            </div>

            <div id="audit-team-container"></div>
            <button type="button" class="btn-add" onclick="addAuditTeam()">+ Tambah Anggota Tim (Opsional)</button>
        </div>

        <div id="audit-details" class="hidden">

            <div class="section">
                <h3>3️⃣ Tim yang Diperiksa (Auditee)</h3>

                <label>Departemen yang Diperiksa</label>
                <select name="department_id" required style="font-size: 16px;">
                    <option value="">-- Pilih Departemen --</option>
                    @foreach ($departments as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>

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

            <div class="note" style="text-align: center; margin-top: 20px;">
                ℹ️ Catatan akhir dan kesimpulan akan diisi setelah pemeriksaan selesai.
            </div>

            <button type="submit" class="btn-primary">✅ Mulai Pemeriksaan & Simpan</button>
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
            <div style="margin-top: 12px; padding: 12px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                <label>Nama Anggota</label>
                <input type="text" name="audit_team[${teamIndex}][name]" placeholder="Contoh: Budi Santoso" required>
                
                <label>Perannya</label>
                <select name="audit_team[${teamIndex}][role]" style="font-size: 15px; width: 100%; padding: 10px; margin-bottom: 10px;">
                    <option value="Member">Anggota Tim</option>
                    <option value="Observer">Pengamat</option>
                    <option value="Specialist">Ahli Teknis</option>
                </select>
                
                <label>Departemennya</label>
                <input type="text" name="audit_team[${teamIndex}][department]" placeholder="Contoh: IT, HRD, Produksi">
                
                <button type="button" style="background: #fee2e2; color: #b91c1c; border: none; padding: 6px 10px; border-radius: 6px; margin-top: 8px;" 
                        onclick="this.parentElement.remove()">✕ Hapus Anggota Ini</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        teamIndex++;
    }
</script>

</body>
</html>
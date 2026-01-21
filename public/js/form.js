let teamIndex = 0;

// Inisialisasi Auditor Select
new TomSelect('#auditor_select', {
    valueField: 'name',
    labelField: 'name',
    searchField: ['name'],
    options: AUDITORS,
    create: false,
    placeholder: 'Ketik nama Anda...',
    onChange: function(value) {
        const auditor = AUDITORS.find(a => a.name === value);
        if (auditor) {
            document.getElementById('auditor_nik').value = auditor.nik;
            document.getElementById('auditor_department').value = auditor.dept;
            document.getElementById('audit-details').classList.remove('hidden');
        } else {
            document.getElementById('auditor_nik').value = '';
            document.getElementById('auditor_department').value = '';
            document.getElementById('audit-details').classList.add('hidden');
        }
    }
});

// Inisialisasi Department Select
new TomSelect('#department_select', {
    valueField: 'id',
    labelField: 'name',
    searchField: ['name'],
    options: DEPARTMENTS,
    create: false,
    placeholder: 'Ketik nama departemen...'
});

// Validasi form
document.getElementById('auditForm').addEventListener('submit', function(e) {
    const auditor = document.getElementById('auditor_select').value;
    const dept = document.getElementById('department_select').value;
    const confirmed = document.getElementById('confirmation').checked;

    if (!auditor || !dept || !confirmed) {
        e.preventDefault();
        alert('Harap lengkapi semua bagian wajib, termasuk centang konfirmasi.');
    }
});

// Fungsi tambah anggota tim
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
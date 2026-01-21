let teamIndex = 0;

// Konfigurasi Umum TomSelect
const tomSelectConfig = {
    create: false,
    maxItems: 1,
    valueField: 'id',
    labelField: 'name',
    searchField: ['name'],
    // Mencegah keyboard muncul & zoom saat dropdown diklik di HP
    onDropdownOpen: function() { this.blur(); } 
};

// 1. Auditor Select
new TomSelect('#auditor_select', {
    ...tomSelectConfig,
    options: AUDITORS,
    valueField: 'name', // Override untuk auditor pakai nama
    placeholder: 'Cari Nama Anda...',
    render: {
        option: (data, escape) => 
            `<div class="option">
                <strong>${escape(data.name)}</strong>
                <div style="font-size: 0.85em; color: #666">${escape(data.dept)}</div>
             </div>`
    },
    onChange: function(value) {
        const data = AUDITORS.find(a => a.name === value);
        const detailsDiv = document.getElementById('auditor-details');
        const targetSection = document.getElementById('target-section');

        if (data) {
            document.getElementById('auditor_nik').value = data.nik;
            document.getElementById('auditor_department').value = data.dept;
            detailsDiv.classList.remove('hidden');
            targetSection.classList.remove('hidden');
        } else {
            detailsDiv.classList.add('hidden');
            targetSection.classList.add('hidden');
        }
    }
});

// 2. Department Select
new TomSelect('#department_select', {
    ...tomSelectConfig,
    options: DEPARTMENTS,
    placeholder: 'Pilih Departemen...',
});

// 3. Tambah Anggota Tim
function addAuditTeam() {
    const container = document.getElementById('audit-team-container');
    const div = document.createElement('div');
    div.className = 'team-member-card';
    div.innerHTML = `
        <button type="button" class="remove-btn" onclick="this.parentElement.remove()">×</button>
        <div class="input-group" style="margin-bottom:10px">
            <label>Nama Anggota</label>
            <input type="text" name="audit_team[${teamIndex}][name]" placeholder="Nama Lengkap" required>
        </div>
        <div class="details-grid">
            <div class="input-group" style="margin:0">
                <label>Peran</label>
                <select name="audit_team[${teamIndex}][role]">
                    <option value="Member">Anggota</option>
                    <option value="Observer">Pengamat</option>
                    <option value="Specialist">Ahli</option>
                </select>
            </div>
            <div class="input-group" style="margin:0">
                <label>Dept Asal</label>
                <input type="text" name="audit_team[${teamIndex}][department]" placeholder="Cth: HRD">
            </div>
        </div>
    `;
    container.appendChild(div);
    teamIndex++;
}

// 4. Validasi Simple saat Submit
document.getElementById('auditForm').addEventListener('submit', function(e) {
    if (!document.getElementById('confirmation').checked) {
        e.preventDefault();
        alert("⚠️ Mohon centang persetujuan data terlebih dahulu.");
        document.getElementById('confirmation').focus();
    }
});
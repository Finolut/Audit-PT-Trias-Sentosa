let teamIndex = 0;

// Config dasar agar tidak perlu ulang-ulang
const tsConfig = {
    create: false,
    maxItems: 1,
    valueField: 'id',
    labelField: 'name',
    searchField: ['name'],
    onDropdownOpen: function() { this.blur(); } // Trik Mobile: tutup keyboard virtual saat dropdown buka
};

// 1. Inisialisasi Auditor Select (Fitur Utama)
const auditorSelect = new TomSelect('#auditor_select', {
    ...tsConfig,
    valueField: 'name', // Value simpan Nama
    options: AUDITORS,
    placeholder: 'Cari nama Anda...',
    // Render tampilan dropdown yg informatif (seperti request awal)
    render: {
        option: function(data, escape) {
            return `<div class="py-2 px-1">
                        <div class="font-bold text-gray-800">${escape(data.name)}</div>
                        <div class="text-xs text-gray-500">NIK: ${escape(data.nik)} • ${escape(data.dept)}</div>
                    </div>`;
        },
        item: function(data, escape) {
            return `<div>${escape(data.name)}</div>`;
        }
    },
    onChange: function(value) {
        const auditor = AUDITORS.find(a => a.name === value);
        const detailsSection = document.getElementById('audit-details');
        
        if (auditor) {
            // AUTOFILL Logic
            document.getElementById('auditor_nik').value = auditor.nik;
            document.getElementById('auditor_department').value = auditor.dept;
            
            // Show Next Section
            detailsSection.classList.remove('hidden-section');
            
            // Smooth Scroll ke bawah sedikit
            setTimeout(() => {
                detailsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 300);
        } else {
            // Reset logic
            document.getElementById('auditor_nik').value = '';
            document.getElementById('auditor_department').value = '';
            detailsSection.classList.add('hidden-section');
        }
    }
});

// 2. Inisialisasi Department Select
new TomSelect('#department_select', {
    ...tsConfig,
    options: DEPARTMENTS,
    placeholder: 'Pilih Departemen...',
});

// 3. Logic Tambah Tim (Tetap ada)
function addAuditTeam() {
    const container = document.getElementById('audit-team-container');
    const div = document.createElement('div');
    div.className = 'team-member-item'; // Class baru yg simple
    
    div.innerHTML = `
        <button type="button" class="remove-team-btn" onclick="this.parentElement.remove()">×</button>
        
        <div class="form-group" style="margin-bottom:8px">
            <input type="text" name="audit_team[${teamIndex}][name]" class="form-input" placeholder="Nama Anggota" required>
        </div>
        
        <div class="form-row-mobile">
            <div class="half">
                <select name="audit_team[${teamIndex}][role]" class="form-input">
                    <option value="Member">Anggota</option>
                    <option value="Observer">Pengamat</option>
                    <option value="Specialist">Ahli</option>
                </select>
            </div>
            <div class="half">
                <input type="text" name="audit_team[${teamIndex}][department]" class="form-input" placeholder="Dept. Asal">
            </div>
        </div>
    `;
    
    container.appendChild(div);
    teamIndex++;
}

// 4. Validasi Form (Penting agar data tidak kosong)
document.getElementById('auditForm').addEventListener('submit', function(e) {
    const auditor = document.getElementById('auditor_select').value;
    const dept = document.getElementById('department_select').value;
    const confirmed = document.getElementById('confirmation').checked;
    let isValid = true;

    // Remove error styles first
    document.querySelectorAll('.error-border').forEach(el => el.classList.remove('error-border'));

    if (!auditor) {
        document.querySelector('#auditor_select').nextElementSibling.classList.add('error-border');
        isValid = false;
    }
    if (!dept) {
        document.querySelector('#department_select').nextElementSibling.classList.add('error-border');
        isValid = false;
    }
    if (!confirmed) {
        document.querySelector('.confirmation-box').classList.add('error-border');
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
        alert('Mohon lengkapi data wajib dan centang konfirmasi.');
    }
});
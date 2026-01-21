// form.js
let teamIndex = 0;

// Inisialisasi Auditor Select
new TomSelect('#auditor_select', {
    valueField: 'name',
    labelField: 'name',
    searchField: ['name'],
    options: AUDITORS,
    create: false,
    placeholder: 'Ketik nama Anda...',
    render: {
        option: function(item, escape) {
            return `<div class="option">
                <div class="option-name">${escape(item.name)}</div>
                <div class="option-meta">NIK: ${escape(item.nik)} • ${escape(item.dept)}</div>
            </div>`;
        },
        item: function(item, escape) {
            return `<div class="item">${escape(item.name)} <span class="item-meta">(${escape(item.dept)})</span></div>`;
        }
    },
    onChange: function(value) {
        const auditor = AUDITORS.find(a => a.name === value);
        if (auditor) {
            document.getElementById('auditor_nik').value = auditor.nik;
            document.getElementById('auditor_department').value = auditor.dept;
            document.getElementById('audit-details').classList.remove('hidden');
            
            // Smooth scroll to next section
            setTimeout(() => {
                document.getElementById('audit-details').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start'
                });
            }, 300);
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
    placeholder: 'Ketik nama departemen...',
    render: {
        option: function(item, escape) {
            return `<div class="option">${escape(item.name)}</div>`;
        }
    }
});

// Validasi form
document.getElementById('auditForm').addEventListener('submit', function(e) {
    const auditor = document.getElementById('auditor_select').value;
    const dept = document.getElementById('department_select').value;
    const confirmed = document.getElementById('confirmation').checked;

    if (!auditor || !dept || !confirmed) {
        e.preventDefault();
        alert('Harap lengkapi semua bagian wajib, termasuk centang konfirmasi.');
        
        // Highlight missing fields
        if (!auditor) {
            document.getElementById('auditor_select').closest('.form-control-container').style.borderColor = '#ef4444';
        }
        if (!dept) {
            document.getElementById('department_select').closest('.form-control-container').style.borderColor = '#ef4444';
        }
        if (!confirmed) {
            document.querySelector('.confirmation-box').style.borderColor = '#ef4444';
            document.querySelector('.confirmation-box').style.borderWidth = '2px';
        }
        
        // Remove highlight after 3 seconds
        setTimeout(() => {
            if (!auditor) document.getElementById('auditor_select').closest('.form-control-container').style.borderColor = '';
            if (!dept) document.getElementById('department_select').closest('.form-control-container').style.borderColor = '';
            if (!confirmed) {
                document.querySelector('.confirmation-box').style.borderColor = '';
                document.querySelector('.confirmation-box').style.borderWidth = '';
            }
        }, 3000);
    }
});

// Fungsi tambah anggota tim
function addAuditTeam() {
    const container = document.getElementById('audit-team-container');
    
    const teamMember = document.createElement('div');
    teamMember.className = 'team-member';
    teamMember.innerHTML = `
        <button type="button" class="remove-team-btn" onclick="this.parentElement.remove()">×</button>
        <div class="form-group">
            <label class="form-label">Nama Anggota</label>
            <div class="form-control-container">
                <input type="text" name="audit_team[${teamIndex}][name]" class="form-input" placeholder="Contoh: Budi Santoso" required>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Perannya</label>
            <div class="form-control-container">
                <select name="audit_team[${teamIndex}][role]" class="form-select">
                    <option value="Member">Anggota Tim</option>
                    <option value="Observer">Pengamat</option>
                    <option value="Specialist">Ahli Teknis</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Departemennya</label>
            <div class="form-control-container">
                <input type="text" name="audit_team[${teamIndex}][department]" class="form-input" placeholder="Contoh: IT, HRD, Produksi">
            </div>
        </div>
    `;
    
    container.appendChild(teamMember);
    teamIndex++;
    
    // Initialize TomSelect for role dropdown if needed
    // (In this case, regular select is sufficient)
    
    // Smooth scroll to the new member
    setTimeout(() => {
        teamMember.scrollIntoView({ behavior: 'smooth', block: 'center' });
        teamMember.style.boxShadow = '0 0 0 2px #3b82f6';
        setTimeout(() => {
            teamMember.style.boxShadow = '';
        }, 1000);
    }, 100);
}

// Add subtle animations for form interactions
document.querySelectorAll('.form-input, .form-textarea, .form-select').forEach(input => {
    input.addEventListener('focus', function() {
        this.closest('.form-control-container').style.borderColor = '#3b82f6';
    });
    
    input.addEventListener('blur', function() {
        this.closest('.form-control-container').style.borderColor = '';
    });
});
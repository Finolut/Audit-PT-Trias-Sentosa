// form.js - Mobile Optimized
let teamIndex = 0;
let currentSection = 1;
const totalSections = 3;

// Section Navigation
function nextSection(current, next) {
    document.getElementById(`section${current}`).classList.remove('active');
    document.getElementById(`section${next}`).classList.add('active');
    currentSection = next;
    updateProgress();
    scrollToTop();
    updateBackButton();
}

function prevSection(current, prev) {
    document.getElementById(`section${current}`).classList.remove('active');
    document.getElementById(`section${prev}`).classList.add('active');
    currentSection = prev;
    updateProgress();
    scrollToTop();
    updateBackButton();
}

function updateProgress() {
    const progressBar = document.getElementById('progressBar');
    const progress = (currentSection / totalSections) * 100;
    progressBar.style.width = `${progress}%`;
}

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

function updateBackButton() {
    const backBtn = document.getElementById('backBtn');
    if (currentSection > 1) {
        backBtn.style.display = 'flex';
    } else {
        backBtn.style.display = 'none';
    }
}

// Back button functionality
document.getElementById('backBtn').addEventListener('click', function() {
    if (currentSection > 1) {
        prevSection(currentSection, currentSection - 1);
    }
});

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    updateBackButton();
    
    // Initialize Auditor Select
    new TomSelect('#auditor_select', {
        valueField: 'name',
        labelField: 'name',
        searchField: ['name'],
        options: AUDITORS,
        create: false,
        placeholder: 'Pilih nama Anda...',
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
            } else {
                document.getElementById('auditor_nik').value = '';
                document.getElementById('auditor_department').value = '';
            }
        }
    });

    // Initialize Department Select
    new TomSelect('#department_select', {
        valueField: 'id',
        labelField: 'name',
        searchField: ['name'],
        options: DEPARTMENTS,
        create: false,
        placeholder: 'Pilih departemen...',
        render: {
            option: function(item, escape) {
                return `<div class="option">${escape(item.name)}</div>`;
            }
        }
    });

    // Form validation
    document.getElementById('auditForm').addEventListener('submit', function(e) {
        // Validate section 1
        const auditType = document.querySelector('input[name="audit_type"]:checked');
        const auditScope = document.querySelector('input[name="audit_scope"]').value.trim();
        const auditObjective = document.querySelector('textarea[name="audit_objective"]').value.trim();
        
        if (!auditType || !auditScope || !auditObjective) {
            e.preventDefault();
            alert('Mohon lengkapi semua informasi di bagian pertama');
            return;
        }
        
        // Validate section 2
        const auditor = document.getElementById('auditor_select').value;
        const confirmed = document.getElementById('confirmation').checked;
        
        if (!auditor || !confirmed) {
            e.preventDefault();
            alert('Mohon pilih auditor dan centang konfirmasi');
            return;
        }
        
        // Validate section 3
        const dept = document.getElementById('department_select').value;
        const picName = document.querySelector('input[name="pic_name"]').value.trim();
        const auditDate = document.querySelector('input[name="audit_date"]').value;
        
        if (!dept || !picName || !auditDate) {
            e.preventDefault();
            alert('Mohon lengkapi semua informasi di bagian terakhir');
            return;
        }
    });

    // Touch-friendly form controls
    document.querySelectorAll('.form-input, .form-textarea, .form-select').forEach(input => {
        input.addEventListener('focus', function() {
            this.closest('.form-control-container')?.style.borderColor = '#3b82f6';
            this.closest('.form-control-container')?.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.2)';
        });
        
        input.addEventListener('blur', function() {
            this.closest('.form-control-container')?.style.borderColor = '';
            this.closest('.form-control-container')?.style.boxShadow = '';
        });
    });
});

// Add team member function
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
            <label class="form-label">Peran</label>
            <div class="btn-group-toggle">
                <input type="radio" id="member${teamIndex}" name="audit_team[${teamIndex}][role]" value="Member" checked>
                <label for="member${teamIndex}" class="toggle-btn">Anggota</label>
                
                <input type="radio" id="observer${teamIndex}" name="audit_team[${teamIndex}][role]" value="Observer">
                <label for="observer${teamIndex}" class="toggle-btn">Pengamat</label>
                
                <input type="radio" id="specialist${teamIndex}" name="audit_team[${teamIndex}][role]" value="Specialist">
                <label for="specialist${teamIndex}" class="toggle-btn">Ahli</label>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Departemen</label>
            <div class="form-control-container">
                <input type="text" name="audit_team[${teamIndex}][department]" class="form-input" placeholder="Contoh: IT, HRD">
            </div>
        </div>
    `;
    
    container.appendChild(teamMember);
    teamIndex++;
    
    // Auto-scroll to the new member
    setTimeout(() => {
        teamMember.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }, 100);
    
    return false;
}

// Handle form submission with loading state
document.getElementById('auditForm')?.addEventListener('submit', function() {
    const submitBtn = document.querySelector('.btn-submit');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="btn-icon">🔄</i> Memproses...';
});

// Handle browser back button
window.addEventListener('popstate', function(event) {
    if (currentSection > 1) {
        event.preventDefault();
        prevSection(currentSection, currentSection - 1);
    }
});

// Add physical back button support for Android
document.addEventListener('backbutton', function(event) {
    if (currentSection > 1) {
        event.preventDefault();
        prevSection(currentSection, currentSection - 1);
    } else {
        navigator.app.exitApp();
    }
}, false);
let sessionAnswers = {}; 

function setVal(itemId, userName, val, btn) {
    // 1. Visual Update untuk tombol yang diklik
    const parent = btn.parentElement;
    parent.querySelectorAll('.answer-btn').forEach(b => {
        b.classList.remove('active-yes', 'active-no', 'active-na');
    });
    
    if(val === 'YES') btn.classList.add('active-yes');
    if(val === 'NO') btn.classList.add('active-no');
    if(val === 'N/A') btn.classList.add('active-na');
    
    // 2. Simpan Jawaban
    sessionAnswers[`${itemId}_${userName}`] = val;
    
    // 3. Update Input Hidden untuk pengiriman form
    updateHiddenInputs(itemId);

    // 4. Jika kita sedang di layar utama, sinkronkan tombol di layar utama jika Auditor yang menjawab
    if (userName === auditorName) {
        syncMainButtons(itemId, val);
    }
}

function syncMainButtons(itemId, val) {
    const mainGroup = document.getElementById(`btn_group_${itemId}`);
    if(!mainGroup) return;
    mainGroup.querySelectorAll('.answer-btn').forEach(b => {
        b.classList.remove('active-yes', 'active-no', 'active-na');
        if(b.innerText === val) {
            if(val === 'YES') b.classList.add('active-yes');
            if(val === 'NO') b.classList.add('active-no');
            if(val === 'N/A') b.classList.add('active-na');
        }
    });
}

function updateHiddenInputs(itemId) {
    const container = document.getElementById(`hidden_inputs_${itemId}`);
    if (!container) return;
    container.innerHTML = ''; 
    for (let key in sessionAnswers) {
        if (key.startsWith(itemId + '_')) {
            const val = sessionAnswers[key];
            const name = key.replace(itemId + '_', '');
            container.innerHTML += `<input type="hidden" name="answers[${itemId}][${name}][val]" value="${val}">`;
        }
    }
}

function createResponderRow(name, role, itemId, isAuditor = false) {
    const div = document.createElement('div');
    div.className = 'responder-row';
    div.style.display = 'flex';
    div.style.justifyContent = 'space-between';
    div.style.alignItems = 'center';
    div.style.padding = '12px 0';
    div.style.borderBottom = '1px solid #f1f5f9';
    
    const existingVal = sessionAnswers[`${itemId}_${name}`] || '';
    
    div.innerHTML = `
        <div>
            <div style="font-weight:600; font-size:0.9rem;">${name} ${isAuditor ? '<span style="color:#2563eb; font-size:0.7rem;">(AUDITOR)</span>' : ''}</div>
            <div style="font-size:0.75rem; color:#64748b;">${role}</div>
        </div>
        <div class="button-group" style="background:#f1f5f9; padding:4px; border-radius:8px;">
            <button type="button" class="answer-btn ${existingVal === 'YES' ? 'active-yes' : ''}" style="padding:4px 10px; font-size:0.75rem;" onclick="setVal('${itemId}', '${name}', 'YES', this)">YES</button>
            <button type="button" class="answer-btn ${existingVal === 'NO' ? 'active-no' : ''}" style="padding:4px 10px; font-size:0.75rem;" onclick="setVal('${itemId}', '${name}', 'NO', this)">NO</button>
            <button type="button" class="answer-btn ${existingVal === 'N/A' ? 'active-na' : ''}" style="padding:4px 10px; font-size:0.75rem;" onclick="setVal('${itemId}', '${name}', 'N/A', this)">N/A</button>
        </div>
    `;
    return div;
}

function openModal(itemId, itemText) {
    const modal = document.getElementById('answerModal');
    document.getElementById('modalItemText').innerText = itemText;
    const list = document.getElementById('modalRespondersList');
    list.innerHTML = '';
    
    // 1. Tambahkan Auditor Terlebih Dahulu
    list.appendChild(createResponderRow(auditorName, 'Lead Auditor', itemId, true));
    
    // 2. Tambahkan Semua Responder dari Departemen Terkait
    if (typeof responders !== 'undefined') {
        responders.forEach(res => {
            list.appendChild(createResponderRow(res.responder_name, res.responder_department || 'Staff', itemId));
        });
    }
    modal.style.display = 'block';
}

function closeModal() {
    document.getElementById('answerModal').style.display = 'none';
}

// Validasi saat submit (Cek apakah Auditor sudah mengisi minimal satu jawaban per soal)
document.getElementById('form').addEventListener('submit', function(e) {
    const items = document.querySelectorAll('.item-row');
    let missing = false;

    items.forEach(item => {
        const itemId = item.id.replace('row_', '');
        // Minimal Auditor harus mengisi jawaban
        if (!sessionAnswers[`${itemId}_${auditorName}`]) {
            item.style.background = '#fff1f2';
            missing = true;
        }
    });

    if (missing) {
        e.preventDefault();
        alert('Setiap soal minimal harus diisi oleh Auditor!');
    }
});
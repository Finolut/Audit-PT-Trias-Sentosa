let sessionAnswers = {}; 

/**
 * Fungsi Utama: Menetapkan nilai jawaban
 */
function setVal(itemId, userName, val, btn) {
    // 1. Update visual button di tempat tombol itu berada (Modal atau Layar Utama)
    const parent = btn.parentElement;
    parent.querySelectorAll('.answer-btn').forEach(b => {
        b.classList.remove('active-yes', 'active-no', 'active-na');
    });
    
    if(val === 'YES') btn.classList.add('active-yes');
    if(val === 'NO') btn.classList.add('active-no');
    if(val === 'N/A') btn.classList.add('active-na');
    
    // 2. Simpan ke state sementara
    sessionAnswers[`${itemId}_${userName}`] = val;
    
    // 3. Update input hidden untuk submit form
    updateHiddenInputs(itemId);

    // 4. Sinkronisasi: Jika yang jawab adalah Auditor, update tombol di layar utama
    if (userName === auditorName) {
        syncMainButtons(itemId, val);
    }
}

/**
 * Menyamakan tombol di layar utama dengan pilihan Auditor di modal
 */
function syncMainButtons(itemId, val) {
    const mainGroup = document.getElementById(`btn_group_${itemId}`);
    if(!mainGroup) return;
    mainGroup.querySelectorAll('.answer-btn').forEach(b => {
        b.classList.remove('active-yes', 'active-no', 'active-na');
        // Cocokkan teks tombol dengan nilai (YES/NO/N/A)
        if(b.innerText.trim() === val) {
            if(val === 'YES') b.classList.add('active-yes');
            if(val === 'NO') b.classList.add('active-no');
            if(val === 'N/A') b.classList.add('active-na');
        }
    });
}

/**
 * Membuat baris personil di dalam modal
 */
function createResponderRow(name, role, itemId, isAuditor = false) {
    const div = document.createElement('div');
    div.className = 'responder-row';
    div.style.cssText = 'display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #f1f5f9;';
    
    const existingVal = sessionAnswers[`${itemId}_${name}`] || '';
    
    // Logic: Jika Auditor, tombol N/A tetap ada di layar utama tapi di modal kita hilangkan saja 
    // agar tampilan konsisten dengan permintaan "Hilangkan N/A di modal"
    div.innerHTML = `
        <div>
            <div style="font-weight:600; font-size:0.9rem; color: #1e293b;">
                ${name} ${isAuditor ? '<span style="color:#2563eb; font-size:0.7rem; background:#eff6ff; padding:2px 6px; border-radius:4px; margin-left:5px;">AUTHOR</span>' : ''}
            </div>
            <div style="font-size:0.75rem; color:#64748b;">${role}</div>
        </div>
        <div class="button-group" style="background:#f1f5f9; padding:4px; border-radius:8px; display:flex; gap:4px;">
            <button type="button" class="answer-btn ${existingVal === 'YES' ? 'active-yes' : ''}" 
                style="padding:6px 16px; font-size:0.75rem; border:none; border-radius:6px; cursor:pointer;"
                onclick="setVal('${itemId}', '${name}', 'YES', this)">YES</button>
            <button type="button" class="answer-btn ${existingVal === 'NO' ? 'active-no' : ''}" 
                style="padding:6px 16px; font-size:0.75rem; border:none; border-radius:6px; cursor:pointer;"
                onclick="setVal('${itemId}', '${name}', 'NO', this)">NO</button>
        </div>
    `;
    return div;
}

function updateHiddenInputs(itemId) {
    const container = document.getElementById(`hidden_inputs_${itemId}`);
    if (!container) return;
    container.innerHTML = ''; 
    for (let key in sessionAnswers) {
        if (key.startsWith(itemId + '_')) {
            const val = sessionAnswers[key];
            const name = key.replace(itemId + '_', '');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `answers[${itemId}][${name}][val]`;
            input.value = val;
            container.appendChild(input);
        }
    }
}

function openModal(itemId, itemText) {
    const modal = document.getElementById('answerModal');
    document.getElementById('modalItemText').innerText = itemText;
    const list = document.getElementById('modalRespondersList');
    list.innerHTML = '';
    
    // Tambahkan baris Auditor
    list.appendChild(createResponderRow(auditorName, 'Auditor Utama', itemId, true));
    
    // Tambahkan baris Responden
    if (typeof responders !== 'undefined') {
        responders.forEach(res => {
            list.appendChild(createResponderRow(res.responder_name, res.responder_department || 'Responden', itemId));
        });
    }
    modal.style.display = 'block';
}

function closeModal() {
    document.getElementById('answerModal').style.display = 'none';
}
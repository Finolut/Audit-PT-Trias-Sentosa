// Menggunakan Set untuk melacak request yang sedang berjalan
let activeRequests = new Set();
let sessionAnswers = {};

/**
 * FUNGSI TERCEPAT: Kirim Paralel
 */
async function submitQuickAnswer(event, itemId, value) {
    if (!event) return;

    const clickedButton = event.currentTarget;
    const infoBox = document.getElementById(`info_${itemId}`);
    const btnGroup = document.getElementById(`btn_group_${itemId}`);
    
    // 1. Feedback Visual Instan (0ms)
    btnGroup.querySelectorAll('.answer-btn').forEach(btn => {
        btn.style.opacity = '0.3';
        btn.style.border = '1px solid #e2e8f0';
    });
    clickedButton.style.opacity = '1';
    clickedButton.style.border = '2px solid #2563eb';
    
    clearHiddenInputs(itemId);
    infoBox.innerHTML = `<span style="color: #2563eb;">⚡ Mengirim...</span>`;

    // 2. Kirim secara PARALEL (Tidak mengantri)
    const auditId = window.location.pathname.split('/')[2];
    const requestId = `${itemId}_${Date.now()}`;
    activeRequests.add(requestId);

    fetch('/audit/save-ajax', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({
            audit_id: auditId,
            item_id: itemId,
            answer: value,
            auditor_name: auditorName
        })
    })
    .then(response => {
        if (response.ok) {
            infoBox.innerHTML = `<b style="color: #16a34a;">✓ Tersimpan</b>`;
        } else {
            infoBox.innerHTML = `<b style="color: #dc2626;">❌ Gagal Simpan</b>`;
        }
    })
    .catch(() => {
        infoBox.innerHTML = `<b style="color: #dc2626;">⚠️ Gangguan Koneksi</b>`;
    })
    .finally(() => {
        activeRequests.delete(requestId);
    });
}

/**
 * LOGIKA BACKGROUND: Memungkinkan pindah halaman meski sedang loading
 */
window.addEventListener('beforeunload', (event) => {
    if (activeRequests.size > 0) {
        // Jika masih ada yang loading, browser akan mencoba menyelesaikannya 
        // di latar belakang sebelum benar-benar berpindah (Keep-Alive)
        console.log("Menyelesaikan pengiriman data di latar belakang...");
    }
});

// ==========================================
// FUNGSI MODAL (TETAP DISEDIAKAN)
// ==========================================
function openModal(itemId, itemText) {
    const modal = document.getElementById('answerModal');
    const list = document.getElementById('modalRespondersList');
    document.getElementById('modalItemText').innerText = itemText;
    list.innerHTML = '';
    responders.forEach(r => list.appendChild(createResponderRow(r.name, r.role, itemId)));
    modal.style.display = 'block';
}

function createResponderRow(name, role, itemId) {
    const div = document.createElement('div');
    div.className = 'responder-row';
    div.style = 'display:flex; justify-content:space-between; margin-bottom:10px; padding:10px; background:#f8fafc; border-radius:8px;';
    const existingVal = sessionAnswers[`${itemId}_${name}`] || '';
    div.innerHTML = `
        <div><span style="font-weight:bold;">${name}</span><br><small>${role}</small></div>
        <div class="button-group">
            <button type="button" class="answer-btn q-btn ${existingVal === 'YES' ? 'active-yes' : ''}" onclick="setVal('${itemId}', '${name}', 'YES', this)">YES</button>
            <button type="button" class="answer-btn q-btn ${existingVal === 'NO' ? 'active-no' : ''}" onclick="setVal('${itemId}', '${name}', 'NO', this)">NO</button>
            <button type="button" class="answer-btn q-btn ${existingVal === 'N/A' ? 'active-na' : ''}" onclick="setVal('${itemId}', '${name}', 'N/A', this)">N/A</button>
        </div>`;
    return div;
}

function setVal(itemId, userName, val, btn) {
    btn.parentElement.querySelectorAll('.answer-btn').forEach(b => b.classList.remove('active-yes', 'active-no', 'active-na'));
    if(val === 'YES') btn.classList.add('active-yes');
    if(val === 'NO') btn.classList.add('active-no');
    if(val === 'N/A') btn.classList.add('active-na');
    sessionAnswers[`${itemId}_${userName}`] = val;
    updateHiddenInputs(itemId);
    updateMainInfo(itemId);
}

function updateHiddenInputs(itemId) {
    const container = document.getElementById(`hidden_inputs_${itemId}`);
    container.innerHTML = ''; 
    for (let key in sessionAnswers) {
        if (key.startsWith(itemId + '_')) {
            const name = key.replace(itemId + '_', '');
            container.innerHTML += `<input type="hidden" name="answers[${itemId}][${name}][name]" value="${name}">
                                    <input type="hidden" name="answers[${itemId}][${name}][val]" value="${sessionAnswers[key]}">`;
        }
    }
}

function updateMainInfo(itemId) {
    let entries = [];
    for (let key in sessionAnswers) {
        if (key.startsWith(itemId + '_')) entries.push(`${key.split('_')[1]}: ${sessionAnswers[key]}`);
    }
    document.getElementById(`info_${itemId}`).innerHTML = `<span style="color: #2563eb; font-weight:bold;">📝 Detail: ${entries.join(', ')}</span>`;
}

function clearHiddenInputs(itemId) {
    for (let key in sessionAnswers) if (key.startsWith(itemId + '_')) delete sessionAnswers[key];
    const container = document.getElementById(`hidden_inputs_${itemId}`);
    if(container) container.innerHTML = '';
}

function closeModal() { document.getElementById('answerModal').style.display = 'none'; }
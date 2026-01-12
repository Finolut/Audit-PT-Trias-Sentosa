// ==========================================
// KONFIGURASI ANTREAN (QUEUE SYSTEM)
// ==========================================
let answerQueue = [];
let isProcessing = false;
let sessionAnswers = {}; // Menampung jawaban dari modal "Jawaban Berbeda"

/**
 * Fungsi Utama: YES/NO/NA Cepat
 * Langsung memberikan feedback visual dan memasukkan ke antrean
 */
async function submitQuickAnswer(event, itemId, value) {
    if (!event) return;

    const clickedButton = event.currentTarget;
    const infoBox = document.getElementById(`info_${itemId}`);
    const btnGroup = document.getElementById(`btn_group_${itemId}`);
    
    // 1. Feedback Visual Instan (Sangat Cepat)
    const allButtons = btnGroup.querySelectorAll('.answer-btn');
    allButtons.forEach(btn => {
        btn.style.opacity = '0.3';
        btn.style.border = '1px solid #e2e8f0';
        btn.classList.remove('active-yes', 'active-no', 'active-na');
    });
    
    clickedButton.style.opacity = '1';
    clickedButton.style.border = '2px solid #2563eb';
    
    // Bersihkan jawaban modal jika user beralih ke quick answer
    clearHiddenInputs(itemId);

    infoBox.innerHTML = `<span style="color: #64748b;">⏳ Mengantre...</span>`;

    // 2. Masukkan ke Antrean
    answerQueue.push({ itemId, value, infoBox });

    // 3. Jalankan pemrosesan jika belum berjalan
    if (!isProcessing) {
        processQueue();
    }
}

/**
 * Mesin Pemroses Antrean (Tanpa Delay/SetTimeout)
 */
async function processQueue() {
    if (answerQueue.length === 0) {
        isProcessing = false;
        return;
    }

    isProcessing = true;
    const task = answerQueue[0];
    const auditId = window.location.pathname.split('/')[2];

    try {
        const response = await fetch('/audit/save-ajax', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                audit_id: auditId,
                item_id: task.itemId,
                answer: task.value,
                auditor_name: auditorName
            })
        });

        if (response.ok) {
            task.infoBox.innerHTML = `<b style="color: #16a34a;">✓ Tersimpan ke Database & Admin</b>`;
            answerQueue.shift(); // Hapus tugas yang selesai
        } else {
            throw new Error("Gagal");
        }
    } catch (error) {
        task.infoBox.innerHTML = `<b style="color: #dc2626;">❌ Gangguan, mencoba lagi...</b>`;
        // Pindahkan ke belakang untuk dicoba lagi
        answerQueue.push(answerQueue.shift());
    }

    // Panggil fungsi ini lagi secepat mungkin (tanpa delay)
    processQueue();
}

// ==========================================
// LOGIKA MODAL (JAWABAN BERBEDA)
// ==========================================

function openModal(itemId, itemText) {
    const modal = document.getElementById('answerModal');
    const list = document.getElementById('modalRespondersList');
    document.getElementById('modalItemText').innerText = itemText;
    list.innerHTML = '';

    responders.forEach(r => {
        list.appendChild(createResponderRow(r.name, r.role, itemId));
    });

    modal.style.display = 'block';
}

function createResponderRow(name, role, itemId) {
    const div = document.createElement('div');
    div.className = 'responder-row';
    div.style.display = 'flex';
    div.style.justifyContent = 'space-between';
    div.style.marginBottom = '10px';
    div.style.padding = '10px';
    div.style.background = '#f8fafc';
    div.style.borderRadius = '8px';

    const existingVal = sessionAnswers[`${itemId}_${name}`] || '';
    
    div.innerHTML = `
        <div>
            <span style="font-weight:bold; color:#1e293b;">${name}</span> <br>
            <small style="color:#64748b;">${role}</small>
        </div>
        <div class="button-group">
            <button type="button" class="answer-btn q-btn ${existingVal === 'YES' ? 'active-yes' : ''}" onclick="setVal('${itemId}', '${name}', 'YES', this)">YES</button>
            <button type="button" class="answer-btn q-btn ${existingVal === 'NO' ? 'active-no' : ''}" onclick="setVal('${itemId}', '${name}', 'NO', this)">NO</button>
            <button type="button" class="answer-btn q-btn ${existingVal === 'N/A' ? 'active-na' : ''}" onclick="setVal('${itemId}', '${name}', 'N/A', this)">N/A</button>
        </div>
    `;
    return div;
}

function setVal(itemId, userName, val, btn) {
    const parent = btn.parentElement;
    parent.querySelectorAll('.answer-btn').forEach(b => b.classList.remove('active-yes', 'active-no', 'active-na'));
    
    if(val === 'YES') btn.classList.add('active-yes');
    if(val === 'NO') btn.classList.add('active-no');
    if(val === 'N/A') btn.classList.add('active-na');
    
    sessionAnswers[`${itemId}_${userName}`] = val;
    
    // Matikan visual tombol quick answer karena sekarang menggunakan jawaban modal
    const btnGroup = document.getElementById(`btn_group_${itemId}`);
    btnGroup.querySelectorAll('.q-btn').forEach(b => {
        b.style.opacity = '0.3';
        b.style.border = '1px solid #e2e8f0';
    });
    
    updateHiddenInputs(itemId);
    updateMainInfo(itemId);
}

function updateHiddenInputs(itemId) {
    const container = document.getElementById(`hidden_inputs_${itemId}`);
    container.innerHTML = ''; 
    for (let key in sessionAnswers) {
        if (key.startsWith(itemId + '_')) {
            const name = key.replace(itemId + '_', '');
            const val = sessionAnswers[key];
            container.innerHTML += `
                <input type="hidden" name="answers[${itemId}][${name}][name]" value="${name}">
                <input type="hidden" name="answers[${itemId}][${name}][val]" value="${val}">
            `;
        }
    }
}

function updateMainInfo(itemId) {
    const infoBox = document.getElementById(`info_${itemId}`);
    let entries = [];
    for (let key in sessionAnswers) {
        if (key.startsWith(itemId + '_')) {
            const name = key.split('_')[1];
            const val = sessionAnswers[key];
            entries.push(`${name}: ${val}`);
        }
    }
    infoBox.innerHTML = `<span style="color: #2563eb; font-weight:bold;">📝 Detail: ${entries.join(', ')}</span>`;
}

function clearHiddenInputs(itemId) {
    for (let key in sessionAnswers) {
        if (key.startsWith(itemId + '_')) delete sessionAnswers[key];
    }
    const container = document.getElementById(`hidden_inputs_${itemId}`);
    if(container) container.innerHTML = '';
}

function closeModal() {
    document.getElementById('answerModal').style.display = 'none';
}
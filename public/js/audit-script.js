/* 1. HAPUS answerQueue, isProcessing, dan fungsi processQueue/submitQuickAnswer 
   2. Gunakan fungsi-fungsi di bawah ini untuk mengelola state form secara lokal.
*/

// Objek untuk menyimpan state jawaban sementara sebelum form disubmit
let sessionAnswers = {}; 

/**
 * Membuat baris responder di dalam modal
 */
function createResponderRow(name, role, itemId) {
    const div = document.createElement('div');
    div.className = 'responder-row';
    
    // Ambil nilai yang sudah dipilih sebelumnya (jika ada di sessionAnswers)
    const existingVal = sessionAnswers[`${itemId}_${name}`] || '';
    
    div.innerHTML = `
        <div style="margin-bottom: 10px;">
            <span style="font-weight:bold">${name}</span> <br>
            <small class="badge" style="background:#e2e8f0; padding: 2px 6px; border-radius: 4px;">${role}</small>
        </div>
        <div class="button-group">
            <button type="button" class="answer-btn q-btn ${existingVal === 'YES' ? 'active-yes' : ''}" onclick="setVal('${itemId}', '${name}', 'YES', this)">YES</button>
            <button type="button" class="answer-btn q-btn ${existingVal === 'NO' ? 'active-no' : ''}" onclick="setVal('${itemId}', '${name}', 'NO', this)">NO</button>
            <button type="button" class="answer-btn q-btn ${existingVal === 'N/A' ? 'active-na' : ''}" onclick="setVal('${itemId}', '${name}', 'N/A', this)">N/A</button>
        </div>
        <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 10px 0;">
    `;
    return div;
}

/**
 * Menetapkan nilai jawaban untuk satu orang per item
 */
function setVal(itemId, userName, val, btn) {
    // 1. Update visual button di dalam modal
    const parent = btn.parentElement;
    parent.querySelectorAll('.answer-btn').forEach(b => {
        b.classList.remove('active-yes', 'active-no', 'active-na');
    });
    
    if(val === 'YES') btn.classList.add('active-yes');
    if(val === 'NO') btn.classList.add('active-no');
    if(val === 'N/A') btn.classList.add('active-na');
    
    // 2. Simpan ke variabel session
    sessionAnswers[`${itemId}_${userName}`] = val;
    
    // 3. Update input hidden agar bisa dikirim saat form submit
    updateHiddenInputs(itemId);
    
    // 4. Update info teks di layar utama
    updateMainInfo(itemId);
}

/**
 * Membuat/Update input hidden secara dinamis di dalam form
 */
function updateHiddenInputs(itemId) {
    const container = document.getElementById(`hidden_inputs_${itemId}`);
    if (!container) return;

    container.innerHTML = ''; 
    for (let key in sessionAnswers) {
        if (key.startsWith(itemId + '_')) {
            const name = key.replace(itemId + '_', '');
            const val = sessionAnswers[key];
            
            // Generate input hidden untuk dikirim via POST standar
            container.innerHTML += `
                <input type="hidden" name="answers[${itemId}][${name}][val]" value="${val}">
            `;
        }
    }
}

/**
 * Memberi feedback visual di list pertanyaan bahwa data sudah siap disubmit
 */
function updateMainInfo(itemId) {
    const infoBox = document.getElementById(`info_${itemId}`);
    if (!infoBox) return;

    let names = [];
    for (let key in sessionAnswers) {
        if (key.startsWith(itemId + '_')) {
            names.push(key.replace(itemId + '_', ''));
        }
    }
    
    if (names.length > 0) {
        infoBox.innerHTML = `
            <span style="color: #2563eb; font-size: 0.85em;">
                <i class="fas fa-check-circle"></i> Siap simpan (${names.length} orang)
            </span>
        `;
    }
}

function closeModal() {
    const modal = document.getElementById('answerModal');
    if (modal) modal.style.display = 'none';
}

function clearHiddenInputs(itemId) {
    for (let key in sessionAnswers) {
        if (key.startsWith(itemId + '_')) delete sessionAnswers[key];
    }
    const container = document.getElementById(`hidden_inputs_${itemId}`);
    if (container) container.innerHTML = '';
}
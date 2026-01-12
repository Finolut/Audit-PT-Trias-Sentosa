// Objek untuk menyimpan state jawaban sementara di memori browser
let sessionAnswers = {}; 

/**
 * Fungsi Utama: Menetapkan nilai jawaban (YES/NO/N/A)
 */
function setVal(itemId, userName, val, btn) {
    // 1. Update visual button (Active State)
    const parent = btn.parentElement;
    parent.querySelectorAll('.answer-btn').forEach(b => {
        b.classList.remove('active-yes', 'active-no', 'active-na');
    });
    
    // Tambahkan class warna sesuai pilihan
    if(val === 'YES') btn.classList.add('active-yes');
    if(val === 'NO') btn.classList.add('active-no');
    if(val === 'N/A') btn.classList.add('active-na');
    
    // 2. Simpan ke variabel session
    sessionAnswers[`${itemId}_${userName}`] = val;
    
    // 3. Update input hidden di dalam form
    updateHiddenInputs(itemId);
    
    // 4. Update info teks di layar utama
    updateMainInfo(itemId);
}

/**
 * Membuat baris responder di dalam modal secara dinamis
 */
function createResponderRow(name, role, itemId) {
    const div = document.createElement('div');
    div.className = 'responder-row';
    div.style.marginBottom = "15px";
    div.style.padding = "10px";
    div.style.borderBottom = "1px solid #eee";
    
    const existingVal = sessionAnswers[`${itemId}_${name}`] || '';
    
    div.innerHTML = `
        <div style="margin-bottom: 8px;">
            <strong style="display:block;">${name}</strong>
            <small style="color:#666;">${role}</small>
        </div>
        <div class="button-group">
            <button type="button" class="answer-btn ${existingVal === 'YES' ? 'active-yes' : ''}" onclick="setVal('${itemId}', '${name}', 'YES', this)">YES</button>
            <button type="button" class="answer-btn ${existingVal === 'NO' ? 'active-no' : ''}" onclick="setVal('${itemId}', '${name}', 'NO', this)">NO</button>
            <button type="button" class="answer-btn ${existingVal === 'N/A' ? 'active-na' : ''}" onclick="setVal('${itemId}', '${name}', 'N/A', this)">N/A</button>
        </div>
    `;
    return div;
}

/**
 * Update input hidden agar data ikut terkirim saat FORM SUBMIT
 */
function updateHiddenInputs(itemId) {
    const container = document.getElementById(`hidden_inputs_${itemId}`);
    if (!container) return;

    container.innerHTML = ''; 
    for (let key in sessionAnswers) {
        if (key.startsWith(itemId + '_')) {
            const val = sessionAnswers[key];
            const name = key.replace(itemId + '_', '');
            
            // Membuat input hidden untuk Laravel
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `answers[${itemId}][${name}][val]`;
            input.value = val;
            container.appendChild(input);
        }
    }
}

/**
 * Feedback Visual Langsung
 */
function updateMainInfo(itemId) {
    const infoBox = document.getElementById(`info_${itemId}`);
    if (!infoBox) return;

    let count = 0;
    for (let key in sessionAnswers) {
        if (key.startsWith(itemId + '_')) count++;
    }
    
    if (count > 0) {
        infoBox.innerHTML = `<b style="color: #16a34a;">✓ Siap Simpan (${count} jawaban)</b>`;
    }
}

/**
 * Kendali Modal
 */
function openModal(itemId, itemText) {
    const modal = document.getElementById('answerModal');
    document.getElementById('modalItemText').innerText = itemText;
    const list = document.getElementById('modalRespondersList');
    list.innerHTML = '';
    
    // Loop responders yang didapat dari Blade
    if (typeof responders !== 'undefined') {
        responders.forEach(res => {
            list.appendChild(createResponderRow(res.responder_name, res.responder_department || '-', itemId));
        });
    }
    
    modal.style.display = 'block';
}

function closeModal() {
    document.getElementById('answerModal').style.display = 'none';
}

// Tutup modal jika klik di luar area modal
window.onclick = function(event) {
    const modal = document.getElementById('answerModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
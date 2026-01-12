// Objek untuk menyimpan state jawaban sementara
let sessionAnswers = {}; 

/**
 * Menetapkan nilai jawaban
 */
function setVal(itemId, userName, val, btn) {
    const parent = btn.parentElement;
    parent.querySelectorAll('.answer-btn').forEach(b => {
        b.classList.remove('active-yes', 'active-no', 'active-na');
    });
    
    if(val === 'YES') btn.classList.add('active-yes');
    if(val === 'NO') btn.classList.add('active-no');
    if(val === 'N/A') btn.classList.add('active-na');
    
    sessionAnswers[`${itemId}_${userName}`] = val;
    
    updateHiddenInputs(itemId);
    // updateMainInfo dihapus agar tidak muncul teks "Siap Simpan"
}

/**
 * Update input hidden
 */
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

/**
 * Validasi Form sebelum Submit
 */
document.getElementById('form').addEventListener('submit', function(e) {
    // Ambil semua container item-row untuk dicek
    const allItems = document.querySelectorAll('.item-row');
    let firstMissingItem = null;

    allItems.forEach(item => {
        // Cek apakah di dalam container ini sudah ada input hidden yang terisi
        const hiddenInputs = item.querySelector('[id^="hidden_inputs_"]');
        if (!hiddenInputs || hiddenInputs.children.length === 0) {
            item.style.backgroundColor = '#fff1f2'; // Beri highlight merah tipis
            item.style.borderRadius = '8px';
            if (!firstMissingItem) firstMissingItem = item;
        } else {
            item.style.backgroundColor = 'transparent'; // Reset jika sudah diisi
        }
    });

    if (firstMissingItem) {
        e.preventDefault(); // Batalkan submit
        alert('Mohon lengkapi semua jawaban sebelum melanjutkan!');
        
        // Scroll ke soal pertama yang belum diisi
        firstMissingItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

/**
 * Kendali Modal (Responder)
 */
function createResponderRow(name, role, itemId) {
    const div = document.createElement('div');
    div.className = 'responder-row';
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

function openModal(itemId, itemText) {
    const modal = document.getElementById('answerModal');
    document.getElementById('modalItemText').innerText = itemText;
    const list = document.getElementById('modalRespondersList');
    list.innerHTML = '';
    
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
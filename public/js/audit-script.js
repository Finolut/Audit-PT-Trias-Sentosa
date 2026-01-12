let sessionAnswers = {};
let currentEditingItemId = null;

async function submitQuickAnswer(event, itemId, value) {
    // Cegah error jika event tidak terkirim
    if (!event) return;

    const clickedButton = event.currentTarget;
    const infoBox = document.getElementById(`info_${itemId}`);
    const btnGroup = document.getElementById(`btn_group_${itemId}`);
    
    // Beri indikasi loading
    const originalInfo = infoBox.innerHTML;
    infoBox.innerHTML = `<span style="color: #2563eb;">⚡ Menyimpan...</span>`;
    
    // Ambil Audit ID dari URL (asumsi /audit/{id}/{clause})
    const pathParts = window.location.pathname.split('/');
    const auditId = pathParts[2]; 

    try {
        const response = await fetch('/audit/save-ajax', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                audit_id: auditId,
                item_id: itemId,
                answer: value,
                auditor_name: auditorName // Pastikan variabel ini ada di script Blade
            })
        });

        const result = await response.json();

        if (response.ok) {
            // 1. Update teks info
            infoBox.innerHTML = `<b style="color: #16a34a;">✓ Tersimpan: ${value}</b>`;
            
            // 2. Reset semua tombol di grup tersebut
            const allButtons = btnGroup.querySelectorAll('.answer-btn');
            allButtons.forEach(btn => {
                btn.style.opacity = '0.4';
                btn.style.border = '1px solid #e2e8f0';
                btn.style.transform = 'scale(1)';
            });

            // 3. Highlight tombol yang baru saja diklik
            clickedButton.style.opacity = '1';
            clickedButton.style.transform = 'scale(1.1)';
            clickedButton.style.border = '2px solid #2563eb';
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error(error);
        alert("Gagal menyimpan: " + error.message);
        infoBox.innerHTML = originalInfo;
    }
}

function createResponderRow(name, role, itemId) {
    const div = document.createElement('div');
    div.className = 'responder-row';
    const existingVal = sessionAnswers[`${itemId}_${name}`] || '';
    
    div.innerHTML = `
        <div>
            <span style="font-weight:bold">${name}</span> <br>
            <small class="badge" style="background:#e2e8f0">${role}</small>
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
    
    // Reset quick buttons visually
    const btnGroup = document.getElementById(`btn_group_${itemId}`);
    btnGroup.querySelectorAll('.q-btn').forEach(b => b.classList.remove('active-yes', 'active-no', 'active-na'));
    
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
    let names = [];
    for (let key in sessionAnswers) {
        if (key.startsWith(itemId + '_')) names.push(key.replace(itemId + '_', ''));
    }
    infoBox.innerHTML = `<span style="color: #2563eb; font-weight:bold;">✓ Jawaban tersimpan untuk: ${names.join(', ')}</span>`;
}

function clearHiddenInputs(itemId) {
    for (let key in sessionAnswers) {
        if (key.startsWith(itemId + '_')) delete sessionAnswers[key];
    }
}

function closeModal() {
    document.getElementById('answerModal').style.display = 'none';
}
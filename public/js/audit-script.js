let answerQueue = [];
let isProcessing = false;

async function submitQuickAnswer(button, itemId, value) {
    const infoBox = document.getElementById(`info_${itemId}`);
    const btnGroup = document.getElementById(`btn_group_${itemId}`);

    // Visual feedback
    btnGroup.querySelectorAll('.answer-btn').forEach(btn => {
        btn.style.opacity = '0.4';
        btn.style.border = '1px solid #e2e8f0';
    });

    button.style.opacity = '1';
    button.style.border = '2px solid #2563eb';

    infoBox.innerHTML = `<span style="color:#64748b">⏳ Antre...</span>`;

    answerQueue.push({ itemId, value, infoBox });
    processQueue();
}


async function processQueue() {
    if (isProcessing || answerQueue.length === 0) return;

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
            task.infoBox.innerHTML = `<b style="color: #16a34a;">✓ Tersimpan</b>`;
            answerQueue.shift(); // Hapus yang sudah sukses
        } else {
            throw new Error("Gagal");
        }
    } catch (error) {
        task.infoBox.innerHTML = `<b style="color: #dc2626;">❌ Re-trying...</b>`;
        // Pindah ke paling belakang jika gagal untuk coba lagi nanti
        answerQueue.push(answerQueue.shift());
    }

    isProcessing = false;
    setTimeout(processQueue, 150); // Jeda 150ms antar pengiriman
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
let sessionAnswers = {};
let currentEditingItemId = null;

function submitQuickAnswer(itemId, val) {
    clearHiddenInputs(itemId);
    sessionAnswers[`${itemId}_${auditorName}`] = val;
    
    const btnGroup = document.getElementById(`btn_group_${itemId}`);
    btnGroup.querySelectorAll('.q-btn').forEach(btn => btn.classList.remove('active-yes', 'active-no', 'active-na'));
    
    if(val === 'YES') btnGroup.querySelector('.q-btn-yes').classList.add('active-yes');
    if(val === 'NO') btnGroup.querySelector('.q-btn-no').classList.add('active-no');
    if(val === 'N/A') btnGroup.querySelector('.q-btn-na').classList.add('active-na');

    updateHiddenInputs(itemId);
    const infoBox = document.getElementById(`info_${itemId}`);
    infoBox.innerHTML = `<span style="color: #16a34a;">Terpilih secara cepat: <strong>${val}</strong></span>`;
}

function openModal(itemId, itemText) {
    currentEditingItemId = itemId;
    document.getElementById('modalItemText').innerText = itemText;
    const listDiv = document.getElementById('modalRespondersList');
    listDiv.innerHTML = '';
    
    // Auditor Row
    listDiv.appendChild(createResponderRow(auditorName, 'Auditor', itemId));
    
    // Responders Rows
    responders.forEach(resp => {
        listDiv.appendChild(createResponderRow(resp.responder_name, 'Responder', itemId));
    });
    
    document.getElementById('answerModal').style.display = 'block';
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
let currentItem = null;

// Fungsi Modal (Tetap Ada jika Responder > 0)
function openModal(itemId) {
    currentItem = itemId;
    document.querySelectorAll('#modal input[type=radio]').forEach(r => r.checked = false);
    document.getElementById('modal').style.display = 'block';
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
}

function confirmAnswer() {
    let yes = 0, no = 0, na = 0;
    let infoHtml = '';

    const auditorRadio = document.querySelector('input[name="modal_auditor_answer"]:checked');
    if (!auditorRadio) { alert('Auditor wajib memilih!'); return; }

    const auditorAnswer = auditorRadio.value;
    if (auditorAnswer === 'YES') yes++; else if (auditorAnswer === 'NO') no++; else na++;

    // Wadah input hidden
    const container = document.getElementById(`hidden_inputs_${currentItem}`);
    container.innerHTML = '';

    // Auditor hidden fields
    container.innerHTML += `<input type="hidden" name="answers[${currentItem}][auditor_name]" value="${auditorName}">`;
    container.innerHTML += `<input type="hidden" name="answers[${currentItem}][answer]" value="${auditorAnswer}">`;

    infoHtml += `<div class="answer-auditor">Auditor: ${auditorAnswer}</div>`;

    // Responders hidden fields
    document.querySelectorAll('input[name^="modal_responder["]:checked').forEach(radio => {
        const name = radio.name.match(/modal_responder\[(.*)\]/)[1];
        const ans = radio.value;
        if (ans === 'YES') yes++; else if (ans === 'NO') no++; else na++;

        container.innerHTML += `<input type="hidden" name="answers[${currentItem}][responders][${name}]" value="${ans}">`;
        infoHtml += `<div class="answer-responder">${name}: ${ans}</div>`;
    });

    document.getElementById(`info_${currentItem}`).innerHTML = `<strong>Y:${yes}, N:${no}, NA:${na}</strong>` + infoHtml;
    closeModal();
}

function setAbsoluteNA(itemId) {
    if (!confirm("Set semua N/A?")) return;
    const container = document.getElementById(`hidden_inputs_${itemId}`);
    container.innerHTML = '';

    container.innerHTML += `<input type="hidden" name="answers[${itemId}][auditor_name]" value="${auditorName}">`;
    container.innerHTML += `<input type="hidden" name="answers[${itemId}][answer]" value="N/A">`;

    let respInfo = "";
    respondersList.forEach(r => {
        container.innerHTML += `<input type="hidden" name="answers[${itemId}][responders][${r.responder_name}]" value="N/A">`;
        respInfo += `<div>${r.responder_name}: N/A</div>`;
    });

    document.getElementById(`info_${itemId}`).innerHTML = `<strong style="color: #6c757d;">MUTLAK N/A</strong><div>Auditor: N/A</div>` + respInfo;
}

// Fungsi untuk Auditor Single (Tanpa Modal)
function submitQuickAnswer(itemId, val) {
    // 1. Bersihkan input hidden lama
    const container = document.getElementById(`hidden_inputs_${itemId}`);
    container.innerHTML = '';

    // 2. Buat Input Hidden Auditor Name
    const inputName = document.createElement('input');
    inputName.type = 'hidden';
    inputName.name = `answers[${itemId}][auditor_name]`;
    inputName.value = auditorName;
    container.appendChild(inputName);

    // 3. Buat Input Hidden Answer
    const inputAns = document.createElement('input');
    inputAns.type = 'hidden';
    inputAns.name = `answers[${itemId}][answer]`;
    inputAns.value = val;
    container.appendChild(inputAns);

    // 4. Update Tampilan Info
    const infoBox = document.getElementById(`info_${itemId}`);
    let color = (val === 'YES') ? '#16a34a' : (val === 'NO' ? '#dc2626' : '#6b7280');
    infoBox.innerHTML = `<strong style="color: ${color}">Terpilih: ${val}</strong>`;

    // 5. Efek Visual Tombol (Highlight)
    const btnGroup = document.getElementById(`btn_group_${itemId}`);
    btnGroup.querySelectorAll('.answer-btn').forEach(btn => {
        btn.classList.remove('active-yes', 'active-no', 'active-na');
    });

    if (val === 'YES') btnGroup.querySelector('.q-btn-yes').classList.add('active-yes');
    if (val === 'NO') btnGroup.querySelector('.q-btn-no').classList.add('active-no');
    if (val === 'N/A') btnGroup.querySelector('.q-btn-na').classList.add('active-na');
}

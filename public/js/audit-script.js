// audit-script.js
/**
 * Variabel Global & State
 */
let sessionAnswers = {};

/**
 * Inisialisasi Data: Memuat jawaban yang sudah ada di database ke dalam UI
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Script loaded');
    
    if (typeof dbAnswers !== 'undefined' && dbAnswers !== null) {
        console.log('✅ Loading saved answers:', dbAnswers);
        
        // 1. Looping setiap jawaban dari database
        Object.keys(dbAnswers).forEach(itemId => {
            const userAnswers = dbAnswers[itemId];
            
            Object.keys(userAnswers).forEach(userName => {
                const answerData = userAnswers[userName];
                const value = answerData.answer;

                console.log(`  - Item ${itemId}, User ${userName}: ${value}`);

                // 2. Masukkan ke state sessionAnswers
                const key = `${itemId}_${userName}`;
                sessionAnswers[key] = value;

                // 3. Jika ini adalah jawaban Auditor, beri warna pada tombol utama
                if (userName === auditorName) {
                    const group = document.getElementById(`btn_group_${itemId}`);
                    if (group) {
                        const buttons = group.querySelectorAll('.answer-btn');
                        buttons.forEach(b => {
                            b.classList.remove('active-yes', 'active-no', 'active-na');
                        });
                        
                        if (value === 'YES') buttons[0]?.classList.add('active-yes');
                        else if (value === 'NO') buttons[1]?.classList.add('active-no');
                        else if (value === 'N/A') buttons[2]?.classList.add('active-na');
                    }
                }

                // 4. Update input hidden
                updateHiddenInputs(itemId);

                // 5. Update info box perbedaan
                updateInfoBox(itemId);

                // 6. Isi Finding Level dan Finding Note jika ada
                if (answerData.finding_level) {
                    const findingSelect = document.querySelector(`select[name="finding_level[${itemId}][${userName}]"]`);
                    if (findingSelect) {
                        findingSelect.value = answerData.finding_level;
                    }
                }
                
                if (answerData.finding_note) {
                    const findingTextarea = document.querySelector(`textarea[name="finding_note[${itemId}][${userName}]"]`);
                    if (findingTextarea) {
                        findingTextarea.value = answerData.finding_note;
                    }
                }
                
                // 7. Set Answer ID untuk update (bukan insert baru)
                const answerIdInput = document.querySelector(`input[name="answer_id_map[${itemId}][${userName}]"]`);
                if (answerIdInput && answerData.id) {
                    answerIdInput.value = answerData.id;
                }
            });
        });
        
        console.log('✅ Answers loaded successfully!');
    } else {
        console.log('⚠️ No saved answers found or dbAnswers not defined');
    }
});

/**
 * Fungsi Utama: Menetapkan nilai jawaban (YES/NO/N/A)
 */
function setVal(itemId, userName, value, btnElement) {
    // 1. Simpan jawaban ke sessionAnswers
    const key = `${itemId}_${userName}`;
    sessionAnswers[key] = value;

    // 2. Jika yang diklik adalah tombol Auditor (di luar modal)
    if (userName === auditorName && btnElement) {
        // Hapus semua class active dari tombol dalam grup yang sama
        const group = document.getElementById(`btn_group_${itemId}`);
        const buttons = group.querySelectorAll('.answer-btn');
        buttons.forEach(b => {
            b.classList.remove('active-yes', 'active-no', 'active-na');
        });

        // Tambahkan class sesuai jawaban
        if (value === 'YES') btnElement.classList.add('active-yes');
        else if (value === 'NO') btnElement.classList.add('active-no');
        else if (value === 'N/A') btnElement.classList.add('active-na');
    }

    // 3. Update input hidden untuk submit form
    updateHiddenInputs(itemId);

    // 4. Update kotak info (perbedaan jawaban)
    updateInfoBox(itemId);
}

/**
 * Sinkronisasi data ke input hidden form
 */
function updateHiddenInputs(itemId) {
    // Cari container hidden inputs atau buat baru
    let container = document.getElementById(`hidden_inputs_${itemId}`);
    if (!container) {
        container = document.createElement('div');
        container.id = `hidden_inputs_${itemId}`;
        container.style.display = 'none';
        const form = document.getElementById('form');
        if (form) {
            form.appendChild(container);
        }
    }

    // Bersihkan container
    container.innerHTML = '';

    // Tambahkan input hidden untuk setiap jawaban
    for (let key in sessionAnswers) {
        if (key.startsWith(`${itemId}_`)) {
            const val = sessionAnswers[key];
            const name = key.replace(`${itemId}_`, '');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `answers[${itemId}][${name}][val]`;
            input.value = val;
            container.appendChild(input);
        }
    }
}

/**
 * Update info box perbedaan jawaban
 */
function updateInfoBox(itemId) {
    const infoBox = document.getElementById(`info_${itemId}`);
    if (!infoBox) return;

    let answers = {};
    for (let key in sessionAnswers) {
        if (key.startsWith(`${itemId}_`)) {
            const user = key.replace(`${itemId}_`, '');
            answers[user] = sessionAnswers[key];
        }
    }

    const auditorAnswer = answers[auditorName];
    if (!auditorAnswer) {
        infoBox.style.display = 'none';
        return;
    }

    // Helper warna & teks
    const getColor = (val) => {
        if (val === 'YES') return '#16a34a';
        if (val === 'NO') return '#dc2626';
        return '#64748b';
    };

    const getDisplayText = (val) => {
        if (val === 'YES') return 'Iya';
        if (val === 'NO') return 'Tidak';
        return 'N/A';
    };

    let diffList = [];
    let hasDifference = false;

    for (let user in answers) {
        if (user !== auditorName && answers[user] !== auditorAnswer) {
            hasDifference = true;
            diffList.push({
                name: user,
                answer: answers[user],
                display: getDisplayText(answers[user])
            });
        }
    }

    if (hasDifference) {
        const responderListHtml = diffList
            .map(d => `
                <div class="diff-item">
                    <span class="diff-user">${d.name}</span>
                    <span class="diff-val" style="color: ${getColor(d.answer)}">${d.display}</span>
                </div>
            `).join('');

        infoBox.innerHTML = `
            <div class="info-box-wrapper">
                <div class="info-box-body">
                    <div class="diff-item auditor-row">
                        <span class="diff-user"><strong>${auditorName} (Anda)</strong></span>
                        <span class="diff-val" style="color: ${getColor(auditorAnswer)}"><strong>${getDisplayText(auditorAnswer)}</strong></span>
                    </div>
                    <div class="diff-divider"></div>
                    ${responderListHtml}
                </div>
            </div>
        `;
        infoBox.style.display = 'block';
    } else {
        infoBox.style.display = 'none';
    }
}

/**
 * Fungsi pembantu khusus klik dari dalam modal agar UI modal langsung update
 */
function setValFromModal(itemId, userName, value, btnElement) {
    // Jalankan fungsi setVal utama untuk simpan data
    setVal(itemId, userName, value, null);

    // Update UI tombol di dalam modal secara instan
    const parent = btnElement.parentElement;
    parent.querySelectorAll('.modal-resp-btn').forEach(btn => btn.classList.remove('active'));
    btnElement.classList.add('active');
    
    // Jika yang diubah di modal adalah akun Auditor, sinkronkan tombol di halaman utama
    if (userName === auditorName) {
        const mainGroup = document.getElementById(`btn_group_${itemId}`);
        if (mainGroup) {
            const mainButtons = mainGroup.querySelectorAll('.answer-btn');
            mainButtons.forEach(b => b.classList.remove('active-yes', 'active-no', 'active-na'));
            
            if (value === 'YES') mainButtons[0].classList.add('active-yes');
            if (value === 'NO') mainButtons[1].classList.add('active-no');
            if (value === 'N/A') mainButtons[2].classList.add('active-na');
        }
    }
}

/**
 * Kontrol Modal
 */
function openModal(itemId, itemText) {
    const modal = document.getElementById('answerModal');
    document.getElementById('modalItemText').innerText = itemText;
    const list = document.getElementById('modalRespondersList');
    list.innerHTML = '';

    if (Array.isArray(responders)) {
        responders.forEach(res => {
            const isAuditor = (res.responder_name === auditorName);
            list.appendChild(createResponderRow(
                res.responder_name,
                res.responder_department || 'Departemen Tidak Diketahui',
                itemId,
                isAuditor
            ));
        });
    }

    modal.style.display = 'flex';
    setTimeout(() => {
        document.querySelector('.modal-content').classList.add('active');
    }, 10);
}

function closeModal() {
    const modalContent = document.querySelector('.modal-content');
    modalContent.classList.remove('active');
    
    setTimeout(() => {
        document.getElementById('answerModal').style.display = 'none';
    }, 300);
}

// Close modal when clicking outside content
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        closeModal();
    }
});

/**
 * Membuat baris responder di dalam modal secara dinamis
 */
function createResponderRow(name, role, itemId, isAuditor = false) {
    const responderDiv = document.createElement('div');
    responderDiv.className = 'responder-item';
    
    const currentVal = sessionAnswers[`${itemId}_${name}`] || '';
    
    responderDiv.innerHTML = `
        <div class="responder-info">
            <div class="responder-name">
                ${name} ${isAuditor ? '<span class="responder-author-tag">AUTHOR</span>' : ''}
            </div>
            <div class="responder-role">${role}</div>
        </div>
        <div class="responder-buttons">
            <button type="button" 
                class="modal-resp-btn btn-yes ${currentVal === 'YES' ? 'active' : ''}" 
                onclick="setValFromModal('${itemId}', '${name}', 'YES', this)">
                <i class="fas fa-check"></i>
            </button>
            
            <button type="button" 
                class="modal-resp-btn btn-no ${currentVal === 'NO' ? 'active' : ''}" 
                onclick="setValFromModal('${itemId}', '${name}', 'NO', this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    return responderDiv;
}
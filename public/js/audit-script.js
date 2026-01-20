// audit-script.js
/**
 * Variabel Global & State
 */
let sessionAnswers = {}; 

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

    // 4. Update kotak info (perbedaan jawaban) yang kita buat sebelumnya
    updateInfoBox(itemId);
}

/**
 * Menghitung perbandingan suara Author vs Responder secara real-time
 */
function calculateScore(itemId) {
    const infoBox = document.getElementById(`info_${itemId}`);
    if (!infoBox) return;

    let yesCount = 0;
    let noCount = 0;
    let details = [];

    for (let key in sessionAnswers) {
        if (key.startsWith(itemId + '_')) {
            const val = sessionAnswers[key];
            if (val === 'YES') yesCount++;
            if (val === 'NO') noCount++;
            details.push(val);
        }
    }

    if (details.length > 0) {
        let statusText = "";
        let statusColor = "#64748b";

        if (yesCount > noCount) {
            statusText = `✓ Terpenuhi (Skor: 1)`;
            statusColor = "#16a34a";
        } else if (noCount > yesCount) {
            statusText = `✗ Tidak Terpenuhi (Skor: 0)`;
            statusColor = "#dc2626";
        } else {
            statusText = `! Hasil Seri (${yesCount} vs ${noCount})`;
            statusColor = "#f59e0b";
        }

        infoBox.style.display = 'block';
        infoBox.innerHTML = `
            <div style="margin-top: 10px; padding: 8px; background: #f8fafc; border-radius: 6px; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.75rem; color: #475569; margin-bottom: 4px;">
                    <strong>Hasil:</strong> <span style="color: ${statusColor}; font-weight: bold;">${statusText}</span>
                </div>
                <div style="font-size: 0.7rem; color: #94a3b8;">
                    Detail: ${yesCount} YES, ${noCount} NO
                </div>
            </div>
        `;
    }
}

/**
 * Menyamakan tombol di layar utama dengan pilihan Auditor di modal
 */
function syncMainButtons(itemId, val) {
    const mainGroup = document.getElementById(`btn_group_${itemId}`);
    if(!mainGroup) return;
    mainGroup.querySelectorAll('.answer-btn').forEach(b => {
        b.classList.remove('active-yes', 'active-no', 'active-na');
        if(b.innerText.trim().includes(val)) {
            if(val === 'YES') b.classList.add('active-yes');
            if(val === 'NO') b.classList.add('active-no');
            if(val === 'N/A') b.classList.add('active-na');
        }
    });
}

/**
 * Sinkronisasi data ke input hidden form
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
 * Membuat baris responder di dalam modal secara dinamis
 */
function createResponderRow(name, role, itemId, isAuditor = false) {
    const responderDiv = document.createElement('div');
    responderDiv.className = 'responder-item';
    
    const val = sessionAnswers[`${itemId}_${name}`] || '';
    
    responderDiv.innerHTML = `
        <div class="responder-info">
            <div class="responder-name">
                ${name} ${isAuditor ? '<span class="responder-author">AUTHOR</span>' : ''}
            </div>
            <div class="responder-role">${role}</div>
        </div>
        <div class="responder-buttons">
            <button type="button" class="responder-btn yes ${val === 'YES' ? 'active' : ''}" 
                onclick="setVal('${itemId}', '${name}', 'YES', this)">
                <i class="fas fa-check"></i> YES
            </button>
            <button type="button" class="responder-btn no ${val === 'NO' ? 'active' : ''}" 
                onclick="setVal('${itemId}', '${name}', 'NO', this)">
                <i class="fas fa-times"></i> NO
            </button>
        </div>
    `;
    return responderDiv;
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
 * Validasi Sebelum Lanjut (Submit)
 */
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const allItems = document.querySelectorAll('.item-card');
            let firstMissingItem = null;

            allItems.forEach(item => {
                const itemId = item.id.replace('row_', '');
                // Cek apakah Auditor sudah mengisi jawaban untuk item ini
                if (!sessionAnswers[`${itemId}_${auditorName}`]) {
                    item.style.backgroundColor = '#fff1f2';
                    item.style.borderColor = '#fecaca';
                    if (!firstMissingItem) firstMissingItem = item;
                } else {
                    item.style.backgroundColor = '#f9fafb';
                    item.style.borderColor = '';
                }
            });

            if (firstMissingItem) {
                e.preventDefault();
                alert('Mohon lengkapi semua jawaban Auditor sebelum melanjutkan!');
                firstMissingItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(() => {
                    firstMissingItem.style.backgroundColor = '';
                    firstMissingItem.style.borderColor = '';
                }, 3000);
            }
        });
    }
});

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
        infoBox.innerHTML = '';
        return;
    }

    // Helper untuk memberi warna pada teks YES/NO/NA
    const getColor = (val) => {
        if (val === 'YES') return 'var(--success)'; // Hijau
        if (val === 'NO') return 'var(--danger)';  // Merah
        return 'var(--gray-600)';                   // Abu-abu untuk N/A
    };

    const getDisplayText = (val) => {
        if (val === 'YES') return 'Ya';
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
        const responderListText = diffList
            .map(d => `<span class="responder-name">${d.name}</span>: <strong class="answer-value" style="color: ${getColor(d.answer)}">${d.display}</strong>`)
            .join('<br>');

        infoBox.innerHTML = `
            <div class="score-info-content">
                <div class="warning-header">
                    <i class="fas fa-exclamation-triangle warning-icon"></i>
                    <div>
                        <div class="auditor-answer ${auditorAnswer.toLowerCase()}">
                            <strong>${auditorName}:</strong> ${getDisplayText(auditorAnswer)}
                        </div>
                    </div>
                </div>
                <div class="responder-diff">
                    <strong>Perbedaan jawaban:</strong><br>
                    ${responderListText}
                </div>
            </div>
        `;
        infoBox.style.display = 'block';
    } else {
        infoBox.style.display = 'none';
        infoBox.innerHTML = '';
    }
}
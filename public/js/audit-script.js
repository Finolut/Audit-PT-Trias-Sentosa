/**
 * Variabel Global & State
 */
let sessionAnswers = {}; 

/**
 * Fungsi Utama: Menetapkan nilai jawaban (YES/NO/N/A)
 */
function setVal(itemId, userName, val, btn) {
    // 1. Update visual tombol yang diklik
    const parent = btn.parentElement;
    parent.querySelectorAll('.answer-btn').forEach(b => {
        b.classList.remove('active-yes', 'active-no', 'active-na');
    });
    
    if(val === 'YES') btn.classList.add('active-yes');
    if(val === 'NO') btn.classList.add('active-no');
    if(val === 'N/A') btn.classList.add('active-na');
    
    // 2. Simpan Jawaban ke memori
    sessionAnswers[`${itemId}_${userName}`] = val;
    
    // 3. Update Input Hidden agar data terkirim ke Laravel
    updateHiddenInputs(itemId);

    // 4. Sinkronisasi visual tombol utama jika yang menjawab adalah Auditor (Author)
    if (userName === auditorName) {
        syncMainButtons(itemId, val);
    }

    // 5. Update info ringkasan skor di layar
    calculateScore(itemId);
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
        if(b.innerText.trim() === val) {
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
    const div = document.createElement('div');
    div.style.cssText = 'display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #f1f5f9;';
    
    const val = sessionAnswers[`${itemId}_${name}`] || '';
    
    div.innerHTML = `
        <div>
            <div style="font-weight:600; font-size:0.9rem; color: #1e293b;">
                ${name} ${isAuditor ? '<span style="color:#2563eb; font-size:0.7rem; background:#eff6ff; padding:2px 6px; border-radius:4px; margin-left:5px;">AUTHOR</span>' : ''}
            </div>
            <div style="font-size:0.75rem; color:#64748b;">${role}</div>
        </div>
        <div class="button-group" style="background:#f1f5f9; padding:4px; border-radius:8px; display:flex; gap:4px;">
            <button type="button" class="answer-btn ${val === 'YES' ? 'active-yes' : ''}" 
                style="padding:6px 16px; font-size:0.75rem; border:none; border-radius:6px; cursor:pointer;"
                onclick="setVal('${itemId}', '${name}', 'YES', this)">YES</button>
            <button type="button" class="answer-btn ${val === 'NO' ? 'active-no' : ''}" 
                style="padding:6px 16px; font-size:0.75rem; border:none; border-radius:6px; cursor:pointer;"
                onclick="setVal('${itemId}', '${name}', 'NO', this)">NO</button>
        </div>
    `;
    return div;
}

/**
 * Kontrol Modal
 */
function openModal(itemId, itemText) {
    const modal = document.getElementById('answerModal');
    document.getElementById('modalItemText').innerText = itemText;
    const list = document.getElementById('modalRespondersList');
    list.innerHTML = '';
    
    list.appendChild(createResponderRow(auditorName, 'Auditor Utama', itemId, true));
    
    if (typeof responders !== 'undefined') {
        responders.forEach(res => {
            list.appendChild(createResponderRow(res.responder_name, res.responder_department || 'Responden', itemId));
        });
    }
    modal.style.display = 'block';
}

function closeModal() {
    document.getElementById('answerModal').style.display = 'none';
}

/**
 * Validasi Sebelum Lanjut (Submit)
 */
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const allItems = document.querySelectorAll('.item-row');
            let firstMissingItem = null;

            allItems.forEach(item => {
                const itemId = item.id.replace('row_', '');
                // Cek apakah Auditor sudah mengisi jawaban untuk item ini
                if (!sessionAnswers[`${itemId}_${auditorName}`]) {
                    item.style.backgroundColor = '#fff1f2';
                    if (!firstMissingItem) firstMissingItem = item;
                } else {
                    item.style.backgroundColor = 'transparent';
                }
            });

            if (firstMissingItem) {
                e.preventDefault();
                alert('Mohon lengkapi semua jawaban Auditor sebelum melanjutkan!');
                firstMissingItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }
});

function updateInfoBox(itemId) {
    const infoBox = document.getElementById(`info_${itemId}`);
    const hiddenContainer = document.getElementById(`hidden_inputs_${itemId}`);
    if (!hiddenContainer) return;

    const inputs = hiddenContainer.querySelectorAll('input');
    let answers = [];
    
    inputs.forEach(input => {
        if (input.value) answers.push(input.value);
    });

    // Ambil jawaban unik (misal: ['YES', 'NO'])
    const uniqueAnswers = [...new Set(answers)];

    // LOGIKA UTAMA: Hanya muncul jika ada lebih dari 1 jenis jawaban (BEDA)
    if (uniqueAnswers.length > 1) {
        const yesCount = answers.filter(a => a === 'YES').length;
        const noCount = answers.filter(a => a === 'NO').length;
        const naCount = answers.filter(a => a === 'N/A').length;

        // Tampilkan box info HANYA saat ada perbedaan
        infoBox.innerHTML = `
            <div style="margin-top: 8px; padding: 10px; background: #fff1f2; border: 1px solid #fecaca; border-radius: 8px; font-size: 0.85rem; color: #b91c1c; display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 1.2rem;">⚠️</span>
                <div>
                    <strong>Perbedaan Jawaban Terdeteksi:</strong><br>
                    Detail: ${yesCount} YES, ${noCount} NO, ${naCount} N/A
                </div>
            </div>
        `;
        infoBox.style.display = 'block';
    } else {
        // HAPUS SEMUA ISI DAN SEMBUNYIKAN jika jawaban seragam/sinkron
        infoBox.innerHTML = '';
        infoBox.style.display = 'none';
    }
}
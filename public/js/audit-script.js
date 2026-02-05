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
 * Validasi Sebelum Lanjut (Submit) – Tanpa Alert, Pakai Navigasi Animasi
 */
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form');
    if (!form) return;

    // Fungsi highlight animasi
    function animateHighlight(element) {
        // Reset dulu
        element.classList.remove('unanswered-highlight');
        void element.offsetWidth; // Trigger reflow

        // Tambahkan kelas animasi
        element.classList.add('unanswered-highlight');

        // Hapus setelah animasi selesai
        setTimeout(() => {
            element.classList.remove('unanswered-highlight');
        }, 1500);
    }

    form.addEventListener('submit', function (e) {
        const allRows = document.querySelectorAll('.item-row'); // <-- pastikan class ini ada di HTML
        let firstUnansweredRow = null;

        allRows.forEach(row => {
            const itemId = row.id.replace('row_', '');
            const hasAuditorAnswer = sessionAnswers[`${itemId}_${auditorName}`];
            if (!hasAuditorAnswer) {
                if (!firstUnansweredRow) firstUnansweredRow = row;
            }
        });

        if (firstUnansweredRow) {
            e.preventDefault();

            // Scroll ke soal tersebut dengan smooth + center
            firstUnansweredRow.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            // Tunggu scroll selesai, lalu highlight
            setTimeout(() => {
                animateHighlight(firstUnansweredRow);
                // Fokus ke tombol pertama biar bisa langsung dijawab
                firstUnansweredRow.querySelector('.answer-btn')?.focus();
            }, 600);
        }
    });
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
        return;
    }

    // Helper warna & teks
    const getColor = (val) => {
        if (val === 'YES') return '#16a34a'; // Hijau Sukses
        if (val === 'NO') return '#dc2626';  // Merah Bahaya
        return '#64748b';                    // Abu-abu N/A
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
 * Membuat baris responder di dalam modal secara dinamis
 */
function createResponderRow(name, role, itemId, isAuditor = false) {
    const responderDiv = document.createElement('div');
    responderDiv.className = 'responder-item';
    
    // Ambil jawaban spesifik user ini untuk item ini dari state
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

/**
 * Fungsi pembantu khusus klik dari dalam modal agar UI modal langsung update
 */
function setValFromModal(itemId, userName, value, btnElement) {
    // Jalankan fungsi setVal utama untuk simpan data
    // Kita panggil setVal tanpa parameter btnElement auditor agar tidak bentrok
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
            
            // Cari tombol yang teksnya sesuai (Iya/Tidak/N/A)
            if (value === 'YES') mainButtons[0].classList.add('active-yes');
            if (value === 'NO') mainButtons[1].classList.add('active-no');
            if (value === 'N/A') mainButtons[2].classList.add('active-na');
        }
    }
}

/**
Inisialisasi Data: Memuat jawaban yang sudah ada di database ke dalam UI
*/
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('copy-token-btn');
    const tokenEl = document.getElementById('audit-token');

    if (!btn || !tokenEl || btn.disabled) return;

    btn.addEventListener('click', async () => {
        const token = tokenEl.textContent.trim();

        if (!token || token === 'TOKEN_TIDAK_TERSEDIA') return;

        try {
            await navigator.clipboard.writeText(token);

            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.style.background = '#10b981';
            btn.style.color = '#fff';

            const tip = document.createElement('span');
            tip.textContent = 'Tersalin!';
            tip.style.position = 'absolute';
            tip.style.top = '-28px';
            tip.style.right = '0';
            tip.style.background = '#10b981';
            tip.style.color = '#fff';
            tip.style.fontSize = '12px';
            tip.style.padding = '3px 8px';
            tip.style.borderRadius = '4px';

            btn.parentElement.appendChild(tip);

            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-copy"></i>';
                btn.style.background = '';
                btn.style.color = '';
                tip.remove();
            }, 1500);

        } catch (e) {
            console.error(e);
            alert('Browser menolak copy otomatis. Salin manual.');
        }
    });
});

    // Fungsi fallback untuk copy text
    function fallbackCopyTextToClipboard(text, btnElement) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        textArea.style.top = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            const successful = document.execCommand('copy');
            if (successful) {
                showCopySuccess(btnElement);
            } else {
                showCopyError(btnElement);
            }
        } catch (err) {
            showCopyError(btnElement);
        }
        
        document.body.removeChild(textArea);
    }
    
    // Tampilkan pesan sukses
    function showCopySuccess(btnElement) {
        const originalHTML = btnElement.innerHTML;
        btnElement.innerHTML = '<i class="fas fa-check"></i>';
        btnElement.style.background = '#10b981';
        btnElement.style.color = 'white';
        
        // Tooltip sukses
        const tooltip = document.createElement('span');
        tooltip.textContent = 'Tersalin!';
        tooltip.style.position = 'absolute';
        tooltip.style.top = '-30px';
        tooltip.style.right = '0';
        tooltip.style.background = '#10b981';
        tooltip.style.color = 'white';
        tooltip.style.padding = '4px 8px';
        tooltip.style.borderRadius = '4px';
        tooltip.style.fontSize = '12px';
        tooltip.style.zIndex = '1000';
        btnElement.parentElement.appendChild(tooltip);
        
        setTimeout(() => {
            btnElement.innerHTML = '<i class="fas fa-copy"></i>';
            btnElement.style.background = '';
            btnElement.style.color = '';
            tooltip.remove();
        }, 2000);
    }
    
    // Tampilkan pesan error
    function showCopyError(btnElement) {
        const originalHTML = btnElement.innerHTML;
        btnElement.innerHTML = '<i class="fas fa-times"></i>';
        btnElement.style.background = '#ef4444';
        btnElement.style.color = 'white';
        
        setTimeout(() => {
            btnElement.innerHTML = '<i class="fas fa-copy"></i>';
            btnElement.style.background = '';
            btnElement.style.color = '';
        }, 2000);
        
        alert('Gagal menyalin token. Silakan salin manual.');
    }
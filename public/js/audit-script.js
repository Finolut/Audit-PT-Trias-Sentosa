/**
 * ===============================
 * GLOBAL STATE
 * ===============================
 */
let sessionAnswers = {};

/**
 * ===============================
 * ENTRY POINT
 * ===============================
 */
document.addEventListener('DOMContentLoaded', initAudit);

function initAudit() {
    restoreFromDB();
    bindMainButtons();
    bindFormValidation();
}

/**
 * ===============================
 * MAIN BUTTON (YES / NO / N/A)
 * ===============================
 */
function bindMainButtons() {
    document.body.addEventListener('click', function (e) {
        const btn = e.target.closest('.answer-btn');
        if (!btn) return;

        const itemId = btn.dataset.itemId;
        const value  = btn.dataset.value;

        setVal(itemId, auditorName, value, btn);
    });
}


/**
 * ===============================
 * SET ANSWER
 * ===============================
 */
function setVal(itemId, userName, value, btnElement) {
    sessionAnswers[`${itemId}_${userName}`] = value;

    if (userName === auditorName && btnElement) {
        const group = document.getElementById(`btn_group_${itemId}`);
        if (group) {
            group.querySelectorAll('.answer-btn')
                .forEach(b => b.classList.remove('active-yes','active-no','active-na'));

            btnElement.classList.add(
                value === 'YES' ? 'active-yes' :
                value === 'NO'  ? 'active-no'  :
                                  'active-na'
            );
        }
    }

    // 🔥 PAKSA UPDATE
    updateHiddenInputs(itemId);
    updateInfoBox(itemId);
}


/**
 * ===============================
 * RESTORE FROM DATABASE
 * ===============================
 */
function restoreFromDB() {
    if (!window.dbAnswers) return;

    Object.entries(dbAnswers).forEach(([itemId, users]) => {
        Object.entries(users).forEach(([userName, data]) => {
            sessionAnswers[`${itemId}_${userName}`] = data.answer;

            if (userName === auditorName) {
                markMainButtons(itemId, data.answer);
            }

            updateHiddenInputs(itemId);
            updateInfoBox(itemId);
        });
    });
}

function markMainButtons(itemId, value) {
    const group = document.getElementById(`btn_group_${itemId}`);
    if (!group) return;

    const btns = group.querySelectorAll('.answer-btn');
    btns.forEach(b => b.classList.remove('active-yes','active-no','active-na'));

    if (value === 'YES') btns[0]?.classList.add('active-yes');
    if (value === 'NO')  btns[1]?.classList.add('active-no');
    if (value === 'N/A') btns[2]?.classList.add('active-na');
}

/**
 * ===============================
 * HIDDEN INPUT SYNC
 * ===============================
 */
function updateHiddenInputs(itemId) {
    const container = document.getElementById(`hidden_inputs_${itemId}`);
    if (!container) return;

    container.innerHTML = '';
    Object.entries(sessionAnswers).forEach(([key, val]) => {
        if (!key.startsWith(itemId + '_')) return;

        const user = key.replace(itemId + '_', '');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `answers[${itemId}][${user}][val]`;
        input.value = val;
        container.appendChild(input);
    });
}

/**
 * ===============================
 * INFO BOX
 * ===============================
 */
function updateInfoBox(itemId) {
    const infoBox = document.getElementById(`info_${itemId}`);
    if (!infoBox) return;

    const auditorAnswer = sessionAnswers[`${itemId}_${auditorName}`];
    if (!auditorAnswer) {
        infoBox.style.display = 'none';
        return;
    }

    let diff = [];
    Object.entries(sessionAnswers).forEach(([key, val]) => {
        if (key.startsWith(itemId + '_')) {
            const user = key.replace(itemId + '_', '');
            if (user !== auditorName && val !== auditorAnswer) {
                diff.push({ user, val });
            }
        }
    });

    if (!diff.length) {
        infoBox.style.display = 'none';
        return;
    }

    infoBox.innerHTML = diff.map(d =>
        `<div class="diff-item">
            <span>${d.user}</span>
            <strong>${d.val}</strong>
        </div>`
    ).join('');

    infoBox.style.display = 'block';
}

/**
 * ===============================
 * FORM VALIDATION
 * ===============================
 */
function bindFormValidation() {
    const form = document.getElementById('form');
    if (!form) return;

    form.addEventListener('submit', e => {
        const rows = document.querySelectorAll('.item-row');
        const firstEmpty = [...rows].find(r => {
            const id = r.id.replace('row_','');
            return !sessionAnswers[`${id}_${auditorName}`];
        });

        if (firstEmpty) {
            e.preventDefault();
            firstEmpty.scrollIntoView({ behavior:'smooth', block:'center' });
            firstEmpty.classList.add('unanswered-highlight');
        }
    });
}

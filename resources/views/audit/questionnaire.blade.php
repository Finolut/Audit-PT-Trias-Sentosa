<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internal Audit | PT Trias Sentosa Tbk</title>
    <link rel="stylesheet" href="{{ asset('css/audit-style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* ========== GLOBAL STYLES ========== */
        .active-yes {
            background-color: #16a34a !important;
            color: white !important;
            border: none !important;
        }
        .active-no {
            background-color: #dc2626 !important;
            color: white !important;
            border: none !important;
        }
        .active-na {
            background-color: #64748b !important;
            color: white !important;
            border: none !important;
        }

        .score-info-box {
            display: none;
            margin-top: 10px;
            padding: 8px;
            background: #f8fafc;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            font-size: 0.85rem;
        }

        .diff-item {
            display: flex;
            justify-content: space-between;
            margin: 4px 0;
        }

        .unanswered-highlight {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        /* ========== MODAL ========== */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 24px;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .modal-header h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0c2d5a;
        }
        .modal-header button {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #64748b;
            cursor: pointer;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
        }
        .modal-header button:hover {
            background: #f1f5f9;
            color: #0c2d5a;
        }

        .responder-item {
            background: #f8fafc;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
            transition: all 0.2s;
        }
        .responder-item:hover {
            background: #f1f5f9;
        }

        .responder-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 8px;
        }
        .responder-name {
            font-weight: 600;
            color: #1e293b;
        }
        .responder-role {
            font-size: 0.85rem;
            color: #64748b;
        }
        .author-badge {
            background: #10b981;
            color: white;
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 8px;
        }

        .modal-answer-btn {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            min-width: 60px;
            text-align: center;
            background-color: #f1f5f9;
            color: #334155;
        }
        .modal-answer-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .modal-answer-btn.yes { background-color: #f1f5f9; color: #334155; }
        .modal-answer-btn.no { background-color: #f1f5f9; color: #334155; }
        .modal-answer-btn.yes.active { background-color: #16a34a; color: white; }
        .modal-answer-btn.no.active { background-color: #dc2626; color: white; }

        /* ========== MAIN LAYOUT ========== */
        .audit-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(4px);
        }
        .back-link:hover {
            background: rgba(255, 255, 255, 0.25);
            text-decoration: none;
        }

        .page-header {
            margin: 2rem 0;
            text-align: center;
        }
        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0c2d5a;
            margin: 0;
        }

        .meta-info {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            font-size: 0.9rem;
            color: #475569;
            margin-top: 0.75rem;
        }
        .meta-info span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sub-clause-section {
            margin-bottom: 2rem;
        }
        .sub-clause-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #0c2d5a;
            margin: 0 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #cbd5e1;
        }

        .level-badge {
            display: inline-block;
            background: #e0e7ff;
            color: #1e293b;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .item-row {
            margin-bottom: 1.5rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px dashed #e2e8f0;
        }
        .item-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .item-content-col {
            margin-bottom: 0.75rem;
        }
        .item-text {
            font-size: 1rem;
            line-height: 1.6;
            color: #1e293b;
            margin: 0;
        }

        .item-action-col {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .button-group {
            display: flex;
            gap: 0.75rem;
        }
        .answer-btn {
            flex: 1;
            padding: 0.6rem 0.8rem;
            font-size: 0.9rem;
            font-weight: 500;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            background-color: #f1f5f9;
            color: #334155;
        }
        .answer-btn:focus {
            outline: none;
            box-shadow: none;
        }
        .answer-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .finding-container {
            background: #f8fafc;
            padding: 0.75rem;
            border-radius: 6px;
        }
        .finding-container label {
            display: block;
            margin-bottom: 0.3rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
        }
        .finding-container select,
        .finding-container textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        .finding-container textarea {
            resize: vertical;
        }

        .btn-more {
            background: transparent;
            color: #0c2d5a;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 0.4rem 0.75rem;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-more:hover {
            background: #f1f5f9;
        }
        .btn-more:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f1f5f9;
        }

        .submit-bar {
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
            text-align: right;
        }
        .submit-audit {
            background: #0c2d5a;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .submit-audit:hover {
            background: #0a2547;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(12, 45, 90, 0.3);
        }

        @media (max-width: 768px) {
            .meta-info {
                flex-direction: column;
                gap: 0.5rem;
            }
            .button-group {
                flex-wrap: wrap;
            }
            .answer-btn {
                flex: calc(33.333% - 0.5rem);
            }
            .back-link {
                position: static;
                margin-top: 1rem;
            }
        }

        .hero-section {
            background:
                linear-gradient(
                    rgba(12, 45, 90, 0.88),
                    rgba(12, 45, 90, 0.88)
                ),
                url('https://media.licdn.com/dms/image/v2/D563DAQEpYdKv0Os29A/image-scale_191_1128/image-scale_191_1128/0/1690510724603/pt_trias_sentosa_tbk_cover?e=2147483647&v=beta&t=dOGhpl6HrbRAla_mDVT5azyevrvu-cOGFxPcrlizZ6M');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 260px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            padding: 0 1rem;
        }
        .hero-content {
            max-width: 900px;
            margin: 0 auto;
            color: white;
            text-align: center;
            z-index: 2;
        }
        .hero-back {
            position: absolute;
            top: 1rem;
            left: 1rem;
        }
    </style>
</head>
<body class="bg-gray-50 audit-body">

<section class="hero-section">
    <div class="hero-back">
        <a href="{{ route('audit.menu', $auditId) }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Menu
        </a>
    </div>

    <div class="hero-content">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">
            Clause {{ $currentMain }}
        </h1>

        <div class="flex flex-wrap gap-4 text-sm md:text-base opacity-95">
            <span class="flex items-center gap-2">
                <i class="fas fa-building"></i>
                Dept: <strong>{{ $targetDept }}</strong>
            </span>

            <span class="flex items-center gap-2">
                <i class="fas fa-user-check"></i>
                Auditor: <strong>{{ $auditorName }}</strong>
            </span>
        </div>
    </div>
</section>

    <div class="audit-container">


        <form method="POST"
              action="{{ route('audit.store', ['id' => $auditId, 'clause' => $currentMain]) }}"
              id="form">
            @csrf

            @foreach ($subClauses as $subCode)
                <div class="sub-clause-section">
                    <h2 class="sub-clause-title">{{ $subCode }} – {{ $clauseTitles[$subCode] ?? 'Detail' }}</h2>

                    @foreach ($maturityLevels as $level)
                        <div class="level-badge">Maturity Level {{ $level->level_number }}</div>

                        @php
                            $items = $itemsGrouped[$subCode] ?? collect();
                        @endphp

                        @foreach ($items->where('maturity_level_id', $level->id) as $item)
                            <div class="item-row" id="row_{{ $item->id }}">
                                <div class="item-content-col">
                                    <p class="item-text">{{ $item->item_text }}</p>
                                    <div id="info_{{ $item->id }}" class="score-info-box"></div>
                                </div>

                                <div class="item-action-col">
                                    <div class="button-group" id="btn_group_{{ $item->id }}">
                                        <button type="button" class="answer-btn yes-btn" data-item-id="{{ $item->id }}" data-value="YES">Iya</button>
                                        <button type="button" class="answer-btn no-btn" data-item-id="{{ $item->id }}" data-value="NO">Tidak</button>
                                        <button type="button" class="answer-btn na-btn" data-item-id="{{ $item->id }}" data-value="N/A">N/A</button>
                                    </div>

                                    <div id="hidden_inputs_{{ $item->id }}"></div>

                                    @php
                                        $existingAnswer = $existingAnswers[$item->id][$auditorName] ?? null;
                                        $findingLevel = $existingAnswer['finding_level'] ?? '';
                                        $findingNote = $existingAnswer['finding_note'] ?? '';
                                        $answerId = $existingAnswer['id'] ?? \Illuminate\Support\Str::uuid();
                                        $isNA = ($existingAnswer['answer'] ?? '') === 'N/A';
                                    @endphp

                                    <input type="hidden"
                                           name="answer_id_map[{{ $item->id }}][{{ $auditorName }}]"
                                           value="{{ $answerId }}">

                                    @if(count($responders) > 1)
                                        <button type="button"
                                                class="btn-more mt-2 {{ $isNA ? 'disabled' : '' }}"
                                                onclick="openModal('{{ $item->id }}', '{{ addslashes($item->item_text) }}', {{ $isNA ? 'true' : 'false' }})"
                                                {{ $isNA ? 'disabled' : '' }}>
                                            Respon Lain...
                                        </button>
                                    @endif

                                    <div class="finding-container mt-3">
                                        <div class="flex flex-wrap gap-4 items-end">
                                            <div class="flex-1 min-w-[160px]">
                                                <label class="block text-[10px] font-bold text-gray-500 uppercase">
                                                    Finding Level
                                                </label>
                                                <select
                                                    name="finding_level[{{ $item->id }}][{{ $auditorName }}]"
                                                    id="finding_level_{{ $item->id }}_{{ $auditorName }}"
                                                    class="w-full text-sm border-gray-300 rounded focus:ring-blue-500"
                                                    onchange="toggleFindingNote('{{ $item->id }}', '{{ $auditorName }}', this.value)">
                                                    <option value="">-- No Finding --</option>
                                                    <option value="observed" {{ $findingLevel === 'observed' ? 'selected' : '' }}>
                                                        Observed (OFI)
                                                    </option>
                                                    <option value="minor" {{ $findingLevel === 'minor' ? 'selected' : '' }}>
                                                        Minor NC
                                                    </option>
                                                    <option value="major" {{ $findingLevel === 'major' ? 'selected' : '' }}>
                                                        Major NC
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mt-3" id="finding_note_wrapper_{{ $item->id }}_{{ $auditorName }}" style="{{ $findingLevel ? 'display: block;' : 'display: none;' }}">
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">
                                                Catatan Temuan
                                            </label>
                                            <textarea
                                                name="finding_note[{{ $item->id }}][{{ $auditorName }}]"
                                                rows="3"
                                                class="w-full text-sm border-gray-300 rounded focus:ring-blue-500 p-2"
                                                placeholder="Jelaskan temuan secara detail...">{{ $findingNote }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            @endforeach

            <div class="submit-bar">
                <button type="button" onclick="confirmSubmit()" class="submit-audit">
                    <i class="fas fa-save"></i> Simpan Klausul ini
                </button>
            </div>
        </form>
    </div>

    <!-- MODAL -->
    <div id="answerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Jawaban Auditor Lain</h3>
                <button type="button" onclick="closeModal()">×</button>
            </div>
            <p id="modalItemText" style="margin-bottom: 16px; font-weight: 500; color: #1e293b;"></p>
            <div id="modalRespondersList"></div>
            <div class="mt-4" style="text-align: right;">
                <button type="button" class="submit-audit" style="padding: 0.5rem 1.25rem;" onclick="closeModal()">Selesai</button>
            </div>
        </div>
    </div>

    <script>
        const auditorName = @json($auditorName);
        const responders = @json($responders);
        const dbAnswers = @json($existingAnswers ?? []);
        let sessionAnswers = {};

        document.addEventListener('DOMContentLoaded', function () {
            restoreFromDB();
            bindButtons();
            bindFormValidation();
            bindModalButtons();
        });

        function bindButtons() {
            document.body.addEventListener('click', function(e) {
                const btn = e.target.closest('.answer-btn');
                if (!btn) return;
                const itemId = btn.dataset.itemId;
                const value = btn.dataset.value;
                setVal(itemId, auditorName, value, btn);

                const moreBtn = document.querySelector(`#row_${itemId} .btn-more`);
                if (moreBtn) {
                    if (value === 'N/A') {
                        moreBtn.disabled = true;
                        moreBtn.classList.add('disabled');
                    } else {
                        moreBtn.disabled = false;
                        moreBtn.classList.remove('disabled');
                    }
                }
            });
        }

        function bindModalButtons() {
            document.body.addEventListener('click', function(e) {
                const btn = e.target.closest('#answerModal .modal-answer-btn');
                if (btn) {
                    const itemId = btn.dataset.itemId;
                    const userName = btn.dataset.user;
                    const value = btn.dataset.value;
                    setVal(itemId, userName, value, btn);
                }
            });
        }

        function setVal(itemId, userName, value, btnElement) {
            sessionAnswers[`${itemId}_${userName}`] = value;

            if (userName === auditorName && btnElement) {
                const group = document.getElementById(`btn_group_${itemId}`);
                if (group) {
                    group.querySelectorAll('.answer-btn').forEach(b => {
                        b.classList.remove('active-yes', 'active-no', 'active-na');
                    });
                    if (value === 'YES') btnElement.classList.add('active-yes');
                    else if (value === 'NO') btnElement.classList.add('active-no');
                    else if (value === 'N/A') btnElement.classList.add('active-na');
                }
            }

            if (document.getElementById('answerModal').style.display === 'flex') {
                updateModalButtonStates(itemId);
            }

            updateHiddenInputs(itemId);
            updateInfoBox(itemId);
        }

        function updateModalButtonStates(itemId) {
            const buttons = document.querySelectorAll(`#answerModal [data-item-id="${itemId}"]`);
            buttons.forEach(btn => {
                const user = btn.dataset.user;
                const val = btn.dataset.value;
                const current = sessionAnswers[`${itemId}_${user}`] || '';
                
                btn.className = 'modal-answer-btn';
                btn.classList.add(val.toLowerCase());
                if (current === val) {
                    btn.classList.add('active');
                }
            });
        }

        function restoreFromDB() {
            if (!dbAnswers) return;
            for (const [itemId, users] of Object.entries(dbAnswers)) {
                for (const [userName, data] of Object.entries(users)) {
                    sessionAnswers[`${itemId}_${userName}`] = data.answer;
                    if (data.finding_level) {
                        const select = document.querySelector(`select[name="finding_level[${itemId}][${userName}]"]`);
                        if (select) select.value = data.finding_level;
                    }
                    if (data.finding_note) {
                        const textarea = document.querySelector(`textarea[name="finding_note[${itemId}][${userName}]"]`);
                        if (textarea) textarea.value = data.finding_note;
                    }
                }
                updateHiddenInputs(itemId);
                updateInfoBox(itemId);
            }
            for (const [itemId, users] of Object.entries(dbAnswers)) {
                if (users[auditorName]) {
                    const ans = users[auditorName].answer;
                    const group = document.getElementById(`btn_group_${itemId}`);
                    if (group) {
                        group.querySelectorAll('.answer-btn').forEach(b => b.classList.remove('active-yes','active-no','active-na'));
                        if (ans === 'YES') group.children[0]?.classList.add('active-yes');
                        else if (ans === 'NO') group.children[1]?.classList.add('active-no');
                        else if (ans === 'N/A') group.children[2]?.classList.add('active-na');
                    }
                    if (ans === 'N/A') {
                        const moreBtn = document.querySelector(`#row_${itemId} .btn-more`);
                        if (moreBtn) {
                            moreBtn.disabled = true;
                            moreBtn.classList.add('disabled');
                        }
                    }
                }
            }
        }

        function updateHiddenInputs(itemId) {
            const container = document.getElementById(`hidden_inputs_${itemId}`);
            if (!container) return;
            container.innerHTML = '';
            for (const [key, val] of Object.entries(sessionAnswers)) {
                if (key.startsWith(`${itemId}_`)) {
                    const user = key.replace(`${itemId}_`, '');
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `answers[${itemId}][${user}][val]`;
                    input.value = val;
                    container.appendChild(input);
                }
            }
        }

        function updateInfoBox(itemId) {
            const infoBox = document.getElementById(`info_${itemId}`);
            if (!infoBox) return;
            const auditorAns = sessionAnswers[`${itemId}_${auditorName}`];
            if (!auditorAns) {
                infoBox.style.display = 'none';
                return;
            }
            let diff = [];
            for (const [key, val] of Object.entries(sessionAnswers)) {
                if (key.startsWith(`${itemId}_`)) {
                    const user = key.replace(`${itemId}_`, '');
                    if (user !== auditorName && val !== auditorAns) {
                        diff.push({ user, val });
                    }
                }
            }
            if (diff.length === 0) {
                infoBox.style.display = 'none';
                return;
            }
            const getColor = (v) => v === 'YES' ? '#16a34a' : v === 'NO' ? '#dc2626' : '#64748b';
            const getText = (v) => v === 'YES' ? 'Iya' : v === 'NO' ? 'Tidak' : 'N/A';
            infoBox.innerHTML = `
                <div class="diff-item">
                    <span><strong>${auditorName} (Anda)</strong></span>
                    <span style="color:${getColor(auditorAns)}"><strong>${getText(auditorAns)}</strong></span>
                </div>
                ${diff.map(d => `
                    <div class="diff-item">
                        <span>${d.user}</span>
                        <span style="color:${getColor(d.val)}">${getText(d.val)}</span>
                    </div>
                `).join('')}
            `;
            infoBox.style.display = 'block';
        }

        function bindFormValidation() {
            const form = document.getElementById('form');
            if (!form) return;
            form.addEventListener('submit', function(e) {
                const rows = document.querySelectorAll('.item-row');
                let firstEmpty = null;
                rows.forEach(row => {
                    const id = row.id.replace('row_', '');
                    if (!sessionAnswers[`${id}_${auditorName}`]) {
                        if (!firstEmpty) firstEmpty = row;
                    }
                });
                if (firstEmpty) {
                    e.preventDefault();
                    firstEmpty.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstEmpty.classList.add('unanswered-highlight');
                    setTimeout(() => firstEmpty.classList.remove('unanswered-highlight'), 1500);
                    Swal.fire('Perhatian!', 'Silakan jawab semua pertanyaan sebelum menyimpan.', 'warning');
                }
            });
        }

        function confirmSubmit() {
            document.getElementById('form').dispatchEvent(new Event('submit', { cancelable: true }));
        }

        function openModal(itemId, text, isNA) {
            if (isNA || sessionAnswers[`${itemId}_${auditorName}`] === 'N/A') {
                Swal.fire('Info', 'Tidak dapat melihat respon lain untuk item yang dijawab "N/A".', 'info');
                return;
            }
            document.getElementById('modalItemText').innerText = text;
            const list = document.getElementById('modalRespondersList');
            list.innerHTML = '';

            responders.forEach(res => {
                const name = res.responder_name || res.name;
                const role = res.responder_department || res.dept || '–';
                const isAuditor = (name === auditorName);

                const div = document.createElement('div');
                div.className = 'responder-item';
                div.innerHTML = `
                    <div class="responder-header">
                        <div>
                            <span class="responder-name">${name}</span>
                            ${isAuditor ? '<span class="author-badge">AUTHOR</span>' : ''}
                            <div class="responder-role">${role}</div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" class="modal-answer-btn" data-item-id="${itemId}" data-user="${name}" data-value="YES">Iya</button>
                        <button type="button" class="modal-answer-btn" data-item-id="${itemId}" data-user="${name}" data-value="NO">Tidak</button>
                    </div>
                `;
                list.appendChild(div);
            });

            updateModalButtonStates(itemId);
            document.getElementById('answerModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('answerModal').style.display = 'none';
        }

        function toggleFindingNote(itemId, auditor, value) {
            const wrapper = document.getElementById(`finding_note_wrapper_${itemId}_${auditor}`);
            if (wrapper) wrapper.style.display = value ? 'block' : 'none';
        }
    </script>
</body>
</html>
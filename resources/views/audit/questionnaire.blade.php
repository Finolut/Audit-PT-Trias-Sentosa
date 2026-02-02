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
        :root {
            --primary: #0C2D5A;
            --primary-dark: #0A2547;
            --success: #16a34a;
            --danger: #dc2626;
            --gray-100: #f8fafc;
            --gray-200: #e2e8f0;
            --gray-700: #334155;
            --gray-800: #1e293b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #ffffff;
            color: var(--gray-800);
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary), #1e3a8a);
            color: white;
            padding: 2.5rem 1rem;
            text-align: center;
            border-radius: 0 0 16px 16px;
            margin-bottom: 2rem;
        }
        .hero h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .hero p {
            font-size: 1rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Back Link */
        .back-link {
            display: inline-flex;
            align-items: center;
            color: white;
            text-decoration: none;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            padding: 0.5rem 0;
        }
        .back-link i {
            margin-right: 0.5rem;
        }

        /* Question Block */
        .question-block {
            margin-bottom: 2rem;
        }
        .question-text {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
            color: var(--gray-800);
            line-height: 1.6;
        }

        /* Answer Buttons */
        .answer-buttons {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }
        .btn-answer {
            flex: 1;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            font-weight: 600;
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            background-color: #ffffff;
            color: var(--gray-700);
        }
        .btn-answer:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .btn-answer.active {
            border-color: var(--primary) !important;
            background-color: var(--primary) !important;
            color: white !important;
        }
        .btn-answer.yes { background-color: #ffffff; }
        .btn-answer.no { background-color: #ffffff; }
        .btn-answer.na { background-color: #ffffff; }
        .btn-answer.yes.active { background-color: var(--success); border-color: var(--success); }
        .btn-answer.no.active { background-color: var(--danger); border-color: var(--danger); }
        .btn-answer.na.active { background-color: #64748b; border-color: #64748b; }

        /* Finding Section */
        .finding-section {
            margin-top: 1rem;
        }
        .finding-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            display: block;
        }
        .finding-select,
        .finding-note {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }
        .finding-note {
            min-height: 100px;
            resize: vertical;
        }

        /* Submit Area */
        .submit-area {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid var(--gray-200);
        }
        .btn-submit {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.875rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(12, 45, 90, 0.3);
        }
        .submit-warning {
            font-size: 0.875rem;
            color: #64748b;
            max-width: 500px;
            margin: 0 auto;
            line-height: 1.5;
        }

        @media (max-width: 768px) {
            .answer-buttons {
                flex-wrap: wrap;
            }
            .btn-answer {
                flex: calc(50% - 0.375rem);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Hero -->
        <div class="hero">
            <h1>INTERNAL AUDIT</h1>
            <p>Official charter defining the objectives, scope, and criteria of internal audits in accordance with ISO 14001.</p>
        </div>

        <!-- Back Link -->
        <a href="{{ route('audit.menu', $auditId) }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Menu
        </a>

        <!-- Form -->
        <form id="form">
            @csrf

            @foreach ($subClauses as $subCode)
                <h2 style="font-size: 1.3rem; font-weight: 600; color: var(--primary); margin: 2rem 0 1rem 0; padding-bottom: 0.5rem; border-bottom: 1px solid var(--gray-200);">
                    {{ $subCode }} – {{ $clauseTitles[$subCode] ?? 'Detail' }}
                </h2>

                @foreach ($maturityLevels as $level)
                    <div style="background: var(--gray-100); padding: 0.5rem 1rem; border-radius: 6px; display: inline-block; margin: 1rem 0;">
                        Maturity Level {{ $level->level_number }}
                    </div>

                    @php
                        $items = $itemsGrouped[$subCode] ?? collect();
                    @endphp

                    @foreach ($items->where('maturity_level_id', $level->id) as $item)
                        <div class="question-block" id="row_{{ $item->id }}">
                            <div class="question-text">{{ $item->item_text }}</div>

                            <!-- Answer Buttons -->
                            <div class="answer-buttons" id="btn_group_{{ $item->id }}">
                                <button type="button" class="btn-answer yes-btn" data-item-id="{{ $item->id }}" data-value="YES">Iya</button>
                                <button type="button" class="btn-answer no-btn" data-item-id="{{ $item->id }}" data-value="NO">Tidak</button>
                                <button type="button" class="btn-answer na-btn" data-item-id="{{ $item->id }}" data-value="N/A">N/A</button>
                            </div>

                            <!-- Hidden inputs -->
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

                            <!-- Respon Lain (jika ada multiple responder) -->
                            @if(count($responders) > 1)
                                <button type="button"
                                        class="btn-more mt-2"
                                        style="background: transparent; color: var(--primary); border: 1px solid var(--gray-200); border-radius: 4px; padding: 0.4rem 0.75rem; font-size: 0.85rem; cursor: pointer; margin-bottom: 1rem;"
                                        onclick="openModal('{{ $item->id }}', '{{ addslashes($item->item_text) }}', {{ $isNA ? 'true' : 'false' }})"
                                        {{ $isNA ? 'disabled' : '' }}>
                                    Respon Lain...
                                </button>
                            @endif

                            <!-- Finding Section -->
                            <div class="finding-section">
                                <label class="finding-label">Finding Level</label>
                                <select
                                    name="finding_level[{{ $item->id }}][{{ $auditorName }}]"
                                    class="finding-select"
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

                                <label class="finding-label">Catatan Temuan</label>
                                <textarea
                                    name="finding_note[{{ $item->id }}][{{ $auditorName }}]"
                                    class="finding-note"
                                    placeholder="Jelaskan temuan secara detail...">{{ $findingNote }}</textarea>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            @endforeach

            <!-- Submit Area -->
            <div class="submit-area">
                <button type="button" class="btn-submit" onclick="submitAuditMock()">
                    Kirim
                </button>
                <div class="submit-warning">
                    Jangan pernah mengirimkan sandi melalui Google Formulir.
                </div>
            </div>
        </form>
    </div>

    <!-- MODAL -->
    <div id="answerModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
        <div style="background:white; padding:24px; border-radius:12px; max-width:500px; width:90%; max-height:80vh; overflow-y:auto; box-shadow:0 10px 30px rgba(0,0,0,0.15);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; padding-bottom:12px; border-bottom:1px solid #e2e8f0;">
                <h3 style="font-size:1.25rem; font-weight:700; color:var(--primary);">Jawaban Auditor Lain</h3>
                <button type="button" onclick="closeModal()" style="background:none; border:none; font-size:1.5rem; color:#64748b; cursor:pointer; width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:50%;">×</button>
            </div>
            <p id="modalItemText" style="margin-bottom:16px; font-weight:500; color:var(--gray-800);"></p>
            <div id="modalRespondersList"></div>
            <div style="text-align:right; margin-top:16px;">
                <button type="button" style="background:var(--primary); color:white; border:none; padding:0.5rem 1.25rem; border-radius:6px; cursor:pointer;" onclick="closeModal()">Selesai</button>
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
                const btn = e.target.closest('.btn-answer');
                if (!btn) return;
                const itemId = btn.dataset.itemId;
                const value = btn.dataset.value;
                setVal(itemId, auditorName, value, btn);

                const moreBtn = document.querySelector(`#row_${itemId} .btn-more`);
                if (moreBtn) {
                    if (value === 'N/A') {
                        moreBtn.disabled = true;
                        moreBtn.style.opacity = '0.5';
                        moreBtn.style.cursor = 'not-allowed';
                    } else {
                        moreBtn.disabled = false;
                        moreBtn.style.opacity = '1';
                        moreBtn.style.cursor = 'pointer';
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
                    group.querySelectorAll('.btn-answer').forEach(b => {
                        b.classList.remove('active');
                    });
                    btnElement.classList.add('active');
                }
            }

            if (document.getElementById('answerModal').style.display === 'flex') {
                updateModalButtonStates(itemId);
            }

            updateHiddenInputs(itemId);
        }

        function updateModalButtonStates(itemId) {
            const buttons = document.querySelectorAll(`#answerModal [data-item-id="${itemId}"]`);
            buttons.forEach(btn => {
                const user = btn.dataset.user;
                const val = btn.dataset.value;
                const current = sessionAnswers[`${itemId}_${user}`] || '';
                
                btn.className = 'modal-answer-btn';
                btn.style.padding = '0.5rem 0.75rem';
                btn.style.fontSize = '0.875rem';
                btn.style.fontWeight = '500';
                btn.style.border = 'none';
                btn.style.borderRadius = '6px';
                btn.style.cursor = 'pointer';
                btn.style.transition = 'all 0.2s';
                btn.style.minWidth = '60px';
                btn.style.textAlign = 'center';
                btn.style.backgroundColor = '#f1f5f9';
                btn.style.color = '#334155';

                if (val === 'YES') btn.style.backgroundColor = current === 'YES' ? '#16a34a' : '#f1f5f9';
                if (val === 'NO') btn.style.backgroundColor = current === 'NO' ? '#dc2626' : '#f1f5f9';
                if (current === val) {
                    btn.style.color = 'white';
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
            }
            for (const [itemId, users] of Object.entries(dbAnswers)) {
                if (users[auditorName]) {
                    const ans = users[auditorName].answer;
                    const group = document.getElementById(`btn_group_${itemId}`);
                    if (group) {
                        group.querySelectorAll('.btn-answer').forEach(b => b.classList.remove('active'));
                        const targetBtn = Array.from(group.children).find(b => b.dataset.value === ans);
                        if (targetBtn) targetBtn.classList.add('active');
                    }
                    if (ans === 'N/A') {
                        const moreBtn = document.querySelector(`#row_${itemId} .btn-more`);
                        if (moreBtn) {
                            moreBtn.disabled = true;
                            moreBtn.style.opacity = '0.5';
                            moreBtn.style.cursor = 'not-allowed';
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

        function bindFormValidation() {
            const form = document.getElementById('form');
            if (!form) return;
            form.addEventListener('submit', function(e) {
                const rows = document.querySelectorAll('.question-block');
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
                    firstEmpty.style.boxShadow = '0 0 0 0 rgba(239, 68, 68, 0.4)';
                    setTimeout(() => {
                        firstEmpty.style.animation = 'pulse 1.5s infinite';
                    }, 100);
                    setTimeout(() => {
                        firstEmpty.style.animation = 'none';
                    }, 1600);
                    Swal.fire('Perhatian!', 'Silakan jawab semua pertanyaan sebelum mengirim.', 'warning');
                }
            });
        }

        function submitAuditMock() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data audit telah dikirim.',
                icon: 'success',
                confirmButtonText: 'Selesai',
                allowOutsideClick: false
            }).then(() => {
                window.location.href = "{{ route('audit.menu', $auditId) }}";
            });
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
                const currentVal = sessionAnswers[`${itemId}_${name}`] || '';

                const div = document.createElement('div');
                div.style.background = '#f8fafc';
                div.style.borderRadius = '8px';
                div.style.padding = '16px';
                div.style.marginBottom = '12px';
                div.innerHTML = `
                    <div style="font-weight:600; color:#1e293b; margin-bottom:4px;">
                        ${name} ${isAuditor ? '<span style="background:#10b981;color:white;font-size:0.75rem;padding:2px 6px;border-radius:4px;margin-left:8px;">AUTHOR</span>' : ''}
                        <div style="font-size:0.85rem; color:#64748b;">${role}</div>
                    </div>
                    <div style="display:flex; gap:8px; margin-top:8px;">
                        <button type="button" class="modal-answer-btn" data-item-id="${itemId}" data-user="${name}" data-value="YES" style="padding:0.5rem 0.75rem; font-size:0.875rem; font-weight:500; border:none; border-radius:6px; cursor:pointer; min-width:60px; text-align:center; background-color:${currentVal === 'YES' ? '#16a34a' : '#f1f5f9'}; color:${currentVal === 'YES' ? 'white' : '#334155'};">Iya</button>
                        <button type="button" class="modal-answer-btn" data-item-id="${itemId}" data-user="${name}" data-value="NO" style="padding:0.5rem 0.75rem; font-size:0.875rem; font-weight:500; border:none; border-radius:6px; cursor:pointer; min-width:60px; text-align:center; background-color:${currentVal === 'NO' ? '#dc2626' : '#f1f5f9'}; color:${currentVal === 'NO' ? 'white' : '#334155'};">Tidak</button>
                    </div>
                `;
                list.appendChild(div);
            });

            document.getElementById('answerModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('answerModal').style.display = 'none';
        }

        function toggleFindingNote(itemId, auditor, value) {
            const textarea = document.querySelector(`textarea[name="finding_note[${itemId}][${auditor}]"]`);
            if (textarea) {
                textarea.style.display = value ? 'block' : 'none';
            }
        }
    </script>
</body>
</html>
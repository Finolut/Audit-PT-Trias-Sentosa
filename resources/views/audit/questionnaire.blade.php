<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Klausul {{ $currentMain }}</title>
    <link rel="stylesheet" href="{{ asset('css/audit-style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .active-yes { background-color: #16a34a !important; color: white !important; border-color: #16a34a !important; }
        .active-no  { background-color: #dc2626 !important; color: white !important; border-color: #dc2626 !important; }
        .active-na  { background-color: #64748b !important; color: white !important; border-color: #64748b !important; }
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
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
    </style>
</head>
<body class="audit-body">
    <div class="audit-container">
        <a href="{{ route('audit.menu', $auditId) }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Menu
        </a>

        <header class="page-header">
            <h1>Clause {{ $currentMain }}</h1>
            <div class="meta-info">
                <span><i class="fas fa-building"></i> Dept: <strong>{{ $targetDept }}</strong></span>
                <span><i class="fas fa-user-check"></i> Auditor: <strong>{{ $auditorName }}</strong></span>
            </div>
        </header>

        <form method="POST"
              action="{{ route('audit.store', ['id' => $auditId, 'clause' => $currentMain]) }}"
              id="form">
            @csrf

            @foreach ($subClauses as $subCode)
                <div class="sub-clause-card">
                    <div class="clause-header">
                        <h2>{{ $subCode }} – {{ $clauseTitles[$subCode] ?? 'Detail' }}</h2>
                    </div>

                    @foreach ($maturityLevels as $level)
                        <div class="level-section">
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

                                        {{-- 🔥 HARUS DI SINI --}}
                                        <div id="hidden_inputs_{{ $item->id }}"></div>

                                        @if(count($responders) > 1)
                                            <button type="button" class="btn-more mt-2" onclick="openModal('{{ $item->id }}', '{{ addslashes($item->item_text) }}')">
                                                Respon Lain...
                                            </button>
                                        @endif

                                        @php
                                            $existingAnswer = $existingAnswers[$item->id][$auditorName] ?? null;
                                            $findingLevel = $existingAnswer['finding_level'] ?? '';
                                            $findingNote = $existingAnswer['finding_note'] ?? '';
                                            $answerId = $existingAnswer['id'] ?? \Illuminate\Support\Str::uuid();
                                        @endphp

                                        <input type="hidden"
                                               name="answer_id_map[{{ $item->id }}][{{ $auditorName }}]"
                                               value="{{ $answerId }}">

                                        <div class="finding-container mt-3 p-2 bg-gray-50 rounded border border-dashed border-gray-300">
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
                        </div>
                    @endforeach
                </div>
            @endforeach

            <div class="submit-bar">
                <div class="submit-wrapper">
                    <button type="button" onclick="confirmSubmit()" class="submit-audit">
                        <i class="fas fa-save"></i> Simpan Klausul ini
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- MODAL --}}
    <div id="answerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Respon Lain</h3>
                <button type="button" onclick="closeModal()" style="float:right; background:none; border:none; font-size:1.2em;">×</button>
            </div>
            <p id="modalItemText"></p>
            <div id="modalRespondersList" class="mt-3"></div>
            <div class="mt-4">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Selesai</button>
            </div>
        </div>
    </div>

    {{-- INTEGRATED JAVASCRIPT --}}
    <script>
        // ✅ GLOBAL STATE
        const auditorName = @json($auditorName);
        const responders = @json($responders);
        const dbAnswers = @json($existingAnswers ?? []);
        let sessionAnswers = {};

        // ✅ INIT
        document.addEventListener('DOMContentLoaded', function () {
            restoreFromDB();
            bindButtons();
            bindFormValidation();
        });

        function bindButtons() {
            document.body.addEventListener('click', function(e) {
                const btn = e.target.closest('.answer-btn');
                if (!btn) return;

                const itemId = btn.dataset.itemId;
                const value = btn.dataset.value;
                setVal(itemId, auditorName, value, btn);
            });
        }

        function setVal(itemId, userName, value, btnElement) {
            sessionAnswers[`${itemId}_${userName}`] = value;

            // Update UI utama (jika auditor)
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

            updateHiddenInputs(itemId);
            updateInfoBox(itemId);
        }

        function restoreFromDB() {
            if (!dbAnswers) return;

            for (const [itemId, users] of Object.entries(dbAnswers)) {
                for (const [userName, data] of Object.entries(users)) {
                    sessionAnswers[`${itemId}_${userName}`] = data.answer;

                    if (userName === auditorName) {
                        const group = document.getElementById(`btn_group_${itemId}`);
                        if (group) {
                            const buttons = group.querySelectorAll('.answer-btn');
                            buttons.forEach(b => b.classList.remove('active-yes','active-no','active-na'));
                            if (data.answer === 'YES') buttons[0]?.classList.add('active-yes');
                            else if (data.answer === 'NO') buttons[1]?.classList.add('active-no');
                            else if (data.answer === 'N/A') buttons[2]?.classList.add('active-na');
                        }
                    }

                    // Isi finding level & note
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

        function openModal(itemId, text) {
            document.getElementById('modalItemText').innerText = text;
            const list = document.getElementById('modalRespondersList');
            list.innerHTML = '';

            responders.forEach(res => {
                const name = res.responder_name || res.name;
                const role = res.responder_department || res.dept || '–';
                const isAuditor = (name === auditorName);
                const currentVal = sessionAnswers[`${itemId}_${name}`] || '';

                const div = document.createElement('div');
                div.className = 'mb-2 p-2 border rounded';
                div.innerHTML = `
                    <div><strong>${name}</strong> ${isAuditor ? '<span style="color:#16a34a">(AUTHOR)</span>' : ''}<br><small>${role}</small></div>
                    <div class="mt-1">
                        <button type="button" class="btn btn-sm ${currentVal === 'YES' ? 'btn-success' : 'btn-outline-success'}" 
                            onclick="setVal('${itemId}', '${name}', 'YES', null)">Iya</button>
                        <button type="button" class="btn btn-sm ${currentVal === 'NO' ? 'btn-danger' : 'btn-outline-danger'}" 
                            onclick="setVal('${itemId}', '${name}', 'NO', null)">Tidak</button>
                        <button type="button" class="btn btn-sm ${currentVal === 'N/A' ? 'btn-secondary' : 'btn-outline-secondary'}" 
                            onclick="setVal('${itemId}', '${name}', 'N/A', null)">N/A</button>
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
            const wrapper = document.getElementById(`finding_note_wrapper_${itemId}_${auditor}`);
            if (wrapper) wrapper.style.display = value ? 'block' : 'none';
        }
    </script>
</body>
</html>
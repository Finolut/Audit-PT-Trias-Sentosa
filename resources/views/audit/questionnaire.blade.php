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
            margin-top: 8px;
            padding: 6px;
            background: #f1f5f9;
            border-radius: 4px;
            font-size: 12px;
        }
        .diff-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        .unanswered-highlight {
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
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

                                        {{-- 🔥 HARUS DI DALAM .item-row --}}
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

    {{-- MODAL UNTUK RESPON LAIN --}}
    <div id="answerModal" class="modal" style="display:none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Respon Lain</h3>
                <button type="button" class="close-modal" onclick="closeModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="item-preview-modal">
                <p id="modalItemText"></p>
            </div>
            <div id="modalRespondersList" class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="modal-close-btn" onclick="closeModal()">Selesai</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        window.dbAnswers   = @json($existingAnswers ?? []);
        window.auditorName = @json($auditorName);
        window.responders  = @json($responders);
    </script>

    <script>
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
            if (!container) {
                console.warn(`Container hidden_inputs_${itemId} tidak ditemukan!`);
                return;
            }

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
                    setTimeout(() => firstEmpty.classList.remove('unanswered-highlight'), 2000);
                    Swal.fire('Perhatian!', 'Silakan jawab semua pertanyaan sebelum menyimpan.', 'warning');
                    return;
                }
            });
        }

        function confirmSubmit() {
            const form = document.getElementById('form');
            if (form) {
                // Trigger validation via submit event
                const event = new Event('submit', { cancelable: true });
                const prevented = !form.dispatchEvent(event);
                if (!prevented) {
                    form.submit();
                }
            }
        }

        // Dummy functions for modal (optional)
        function openModal(itemId, text) {
            document.getElementById('modalItemText').innerText = text;
            document.getElementById('answerModal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('answerModal').style.display = 'none';
        }
        function toggleFindingNote(itemId, auditor, value) {
            const wrapper = document.getElementById(`finding_note_wrapper_${itemId}_${auditor}`);
            if (wrapper) {
                wrapper.style.display = value ? 'block' : 'none';
            }
        }
    </script>
    @endpush
</body>
</html>
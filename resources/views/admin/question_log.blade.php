<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Audit Klausul {{ $currentMain }}</title>
    <link rel="stylesheet" href="{{ asset('css/audit-style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-600: #4b5563;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .active-yes { background-color: var(--success) !important; color: white !important; }
        .active-no  { background-color: var(--danger) !important; color: white !important; }
        .active-na  { background-color: var(--gray-600) !important; color: white !important; }
        .score-info-box { display: none; margin-top: 1rem; }
    </style>
</head>
<body class="audit-body">
    <div class="audit-container">
        <header class="audit-header">
            <div class="back-container">
                <a href="{{ route('audit.menu', $auditId) }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Menu</span>
                </a>
            </div>
            
            <div class="header-content">
                <h1 class="main-title">Klausul {{ $currentMain }}</h1>
                <div class="header-meta">
                    <span><i class="fas fa-building"></i> Dept: <strong>{{ $targetDept }}</strong></span>
                    <span><i class="fas fa-user-tie"></i> Auditor: <strong>{{ $auditorName }}</strong></span>
                </div>
            </div>
        </header>

        <form method="POST" action="/audit/{{ $auditId }}/{{ $currentMain }}" id="form">
            @csrf
            @foreach ($subClauses as $subCode)
                <div class="sub-clause-card">
                    <div class="clause-header">
                        <h2 class="sub-clause-title">{{ $subCode }} – {{ $clauseTitles[$subCode] ?? 'Detail' }}</h2>
                        <div class="maturity-badge">Level {{ $maturityLevels->max('level_number') }}</div>
                    </div>

                    @foreach ($maturityLevels as $level)
                        <div class="level-section">
                            <div class="level-badge">Level {{ $level->level_number }}</div>
                            @php $items = $itemsGrouped[$subCode] ?? collect(); @endphp
                            
                            @foreach ($items->where('maturity_level_id', $level->id) as $item)
                                <div class="item-card" id="row_{{ $item->id }}">
                                    <div class="item-content">
                                        <p class="item-text">{{ $item->item_text }}</p>
                                        <div id="info_{{ $item->id }}" class="score-info-box"></div>
                                    </div>
                                    
                                    <div class="response-section">
                                        <div class="button-group" id="btn_group_{{ $item->id }}">
                                            <button type="button" class="answer-btn" onclick="setVal('{{ $item->id }}', '{{ $auditorName }}', 'YES', this)">
                                                <i class="fas fa-check"></i> Ya
                                            </button>
                                            <button type="button" class="answer-btn" onclick="setVal('{{ $item->id }}', '{{ $auditorName }}', 'NO', this)">
                                                <i class="fas fa-times"></i> Tidak
                                            </button>
                                            <button type="button" class="answer-btn" onclick="setVal('{{ $item->id }}', '{{ $auditorName }}', 'N/A', this)">
                                                <i class="fas fa-ban"></i> N/A
                                            </button>
                                        </div>
                                        <button type="button" class="btn-more" onclick="openModal('{{ $item->id }}', '{{ addslashes($item->item_text) }}')">
                                            <i class="fas fa-comments"></i> Respon Lain...
                                        </button>
                                    </div>
                                    <div id="hidden_inputs_{{ $item->id }}"></div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="notes-section">
                        <label class="notes-label">Catatan Temuan ({{ $subCode }})</label>
                        <textarea name="audit_notes[{{ $subCode }}]" rows="3" class="notes-textarea">{{ $existingNotes[$subCode] ?? '' }}</textarea>
                    </div>
                </div>
            @endforeach

            <div class="submit-container">
                <button type="submit" class="submit-button">
                    <i class="fas fa-save"></i> Simpan & Lanjut
                </button>
            </div>
        </form>
    </div>

    <div id="answerModal" class="modal">
        <div class="modal-overlay" onclick="closeModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Respon untuk:</h3>
                <p id="modalItemText" class="modal-item-text"></p>
                <button class="close-modal" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="modalRespondersList" class="responders-list"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-close-btn" onclick="closeModal()">
                    <i class="fas fa-check"></i> Selesai
                </button>
            </div>
        </div>
    </div>

    <script>
        const auditorName = "{{ $auditorName }}";
        const responders = @json($responders);
    </script>
    <script src="{{ asset('js/audit-script.js') }}"></script>
</body>
</html>
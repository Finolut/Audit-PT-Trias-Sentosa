<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Audit Klausul {{ $currentMain }}</title>
    <link rel="stylesheet" href="{{ asset('css/audit-style.css') }}">
    <style>
        .active-yes { background-color: #16a34a !important; color: white !important; }
        .active-no  { background-color: #dc2626 !important; color: white !important; }
        .active-na  { background-color: #64748b !important; color: white !important; }
        .score-info-box { display: none; }
    </style>
</head>
<body class="audit-body">
    <div class="audit-container">
        <a href="{{ route('audit.menu', $auditId) }}" class="back-link">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Menu
        </a>
        
        <header style="margin: 2rem 0;">
            <h1 style="font-size: 1.8rem; font-weight: 800; color: #1e293b;">Clause {{ $currentMain }}</h1>
            <div style="display: flex; gap: 1rem; color: #64748b; font-size: 0.9rem;">
                <span>Dept: <strong>{{ $targetDept }}</strong></span>
                <span>•</span>
                <span>Auditor: <strong>{{ $auditorName }}</strong></span>
            </div>
        </header>

        <form method="POST" action="/audit/{{ $auditId }}/{{ $currentMain }}" id="form">
            @csrf
            @foreach ($subClauses as $subCode)
                <div class="sub-clause-container">
                    <div class="clause-header">
                        <h2>{{ $subCode }} – {{ $clauseTitles[$subCode] ?? 'Detail' }}</h2>
                    </div>

                    @foreach ($maturityLevels as $level)
                        <div class="level-section">
                            <div class="level-title">Maturity Level {{ $level->level_number }}</div>
                            @php $items = $itemsGrouped[$subCode] ?? collect(); @endphp
                            
                            @foreach ($items->where('maturity_level_id', $level->id) as $item)
                                <div class="item-row" id="row_{{ $item->id }}">
                                    <div class="item-text">
                                        {{ $item->item_text }}
                                        <div id="info_{{ $item->id }}" class="score-info-box"></div>
                                    </div>
                                    
                                    <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
                                        <div class="button-group" id="btn_group_{{ $item->id }}">
                                            <button type="button" class="answer-btn q-btn" onclick="setVal('{{ $item->id }}', '{{ $auditorName }}', 'YES', this)">YES</button>
                                            <button type="button" class="answer-btn q-btn" onclick="setVal('{{ $item->id }}', '{{ $auditorName }}', 'NO', this)">NO</button>
                                            <button type="button" class="answer-btn q-btn" onclick="setVal('{{ $item->id }}', '{{ $auditorName }}', 'N/A', this)">N/A</button>
                                        </div>
                                        <button type="button" class="btn-more" onclick="openModal('{{ $item->id }}', '{{ addslashes($item->item_text) }}')">
                                            Respon Lain...
                                        </button>
                                    </div>
                                    <div id="hidden_inputs_{{ $item->id }}"></div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="question-box">
                        <label>Catatan Temuan ({{ $subCode }})</label>
                        <textarea name="audit_notes[{{ $subCode }}]" rows="3" class="question-textarea">{{ $existingNotes[$subCode] ?? '' }}</textarea>
                    </div>
                </div>
            @endforeach

            <div class="submit-container">
                <button type="submit" class="submit-audit">Simpan & Lanjut</button>
            </div>
        </form>
    </div>

    <div id="answerModal" class="modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);">
        <div class="modal-content" style="background:white; margin:5% auto; width:90%; max-width:500px; border-radius:16px; overflow:hidden;">
            <div style="padding: 1.5rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <h3 style="margin:0;">Respon Multi-Pihak</h3>
                <p id="modalItemText" style="font-size: 0.85rem; color: #64748b;"></p>
            </div>
            <div id="modalRespondersList" style="padding: 0 1.5rem; max-height: 400px; overflow-y: auto;"></div>
            <div style="padding: 1.5rem; text-align: right;">
                <button type="button" onclick="closeModal()" style="padding: 10px 24px; background:#2563eb; color:white; border:none; border-radius:8px;">Tutup</button>
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
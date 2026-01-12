<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Audit Klausul {{ $currentMain }}</title>
    <link rel="stylesheet" href="{{ asset('css/audit-style.css') }}">
</head>
<body class="audit-body">
    <div class="audit-container">
        <a href="{{ route('audit.menu', $auditId) }}" class="back-link">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Menu
        </a>
        
        <header style="margin: 2rem 0;">
            <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--slate-800); margin-bottom: 0.5rem;">Clause {{ $currentMain }}</h1>
            <div style="display: flex; gap: 1rem; color: var(--slate-600); font-size: 0.9rem;">
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
                        <h2>{{ $subCode }} – {{ $clauseTitles[$subCode] ?? 'Detail Klausul' }}</h2>
                    </div>

                    @foreach ($maturityLevels as $level)
                        <div class="level-section">
                            <div class="level-title">Maturity Level {{ $level->level_number }}</div>

                            @php $items = $itemsGrouped[$subCode] ?? collect(); @endphp
                            
                  @foreach ($items->where('maturity_level_id', $level->id) as $item)
    <div class="item-row" id="row_{{ $item->id }}">
        <div class="item-text">
            {{ $item->item_text }}
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
<div id="answerModal" class="modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);">
        <div class="modal-content" style="background:white; margin:5% auto; padding:0; width:90%; max-width:500px; border-radius:16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow:hidden;">
            
            <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
                <h3 style="margin:0; font-size: 1.1rem; color: #1e293b;">Respon Multi-Pihak</h3>
                <p id="modalItemText" style="margin: 0.5rem 0 0; font-size: 0.85rem; color: #64748b; line-height: 1.4;"></p>
            </div>

            <div id="modalRespondersList" style="padding: 0 1.5rem; max-height: 400px; overflow-y: auto;">
                </div>

            <div style="padding: 1.5rem; background: #f8fafc; text-align: right; border-top: 1px solid #e2e8f0;">
                <button type="button" onclick="closeModal()" style="padding: 10px 24px; cursor:pointer; background:#2563eb; color:white; border:none; border-radius:8px; font-weight:600; font-size:0.9rem;">
                    Simpan Respon
                </button>
            </div>
        </div>
    </div>
        <div id="hidden_inputs_{{ $item->id }}"></div>
    </div>
@endforeach
                        </div>
                    @endforeach

                    <div class="question-box">
                        <label style="font-weight: 700; color: #92400e; font-size: 0.85rem; text-transform: uppercase;">Catatan Temuan ({{ $subCode }})</label>
                        <textarea name="audit_notes[{{ $subCode }}]" rows="3" class="question-textarea" placeholder="Tulis bukti audit atau temuan di sini...">{{ $existingNotes[$subCode] ?? '' }}</textarea>
                    </div>
                </div>
            @endforeach

            <div class="submit-container">
                <button type="submit" class="submit-audit">
                    {{ $nextMainClause ? 'Simpan & Lanjut ke Clause ' . $nextMainClause : 'Simpan & Selesaikan Audit ✓' }}
                </button>
            </div>
        </form>
    </div>
<script>
    const auditorName = "{{ $auditorName }}";
    const responders = @json($responders);
</script>
<script src="{{ asset('js/audit-script.js') }}"></script>
</body>
</body>
</html>
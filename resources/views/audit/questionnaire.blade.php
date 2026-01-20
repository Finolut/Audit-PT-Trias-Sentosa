<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Klausul {{ $currentMain }}</title>
    <link rel="stylesheet" href="{{ asset('css/audit-style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Override untuk status aktif (Wajib ada) */
        .active-yes { background-color: #16a34a !important; color: white !important; border-color: #16a34a !important; }
        .active-no  { background-color: #dc2626 !important; color: white !important; border-color: #dc2626 !important; }
        .active-na  { background-color: #64748b !important; color: white !important; border-color: #64748b !important; }
        .score-info-box { display: none; }
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

        <form method="POST" action="/audit/{{ $auditId }}/{{ $currentMain }}" id="form">
            @csrf
            @foreach ($subClauses as $subCode)
                <div class="sub-clause-card">
                    <div class="clause-header">
                        <h2>{{ $subCode }} – {{ $clauseTitles[$subCode] ?? 'Detail' }}</h2>
                    </div>

                    @foreach ($maturityLevels as $level)
                        <div class="level-section">
                            <div class="level-badge">Maturity Level {{ $level->level_number }}</div>
                            @php $items = $itemsGrouped[$subCode] ?? collect(); @endphp
                            
                            @foreach ($items->where('maturity_level_id', $level->id) as $item)
                                <div class="item-row" id="row_{{ $item->id }}">
                                    <div class="item-content-col">
                                        <p class="item-text">{{ $item->item_text }}</p>
                                        <div id="info_{{ $item->id }}" class="score-info-box"></div>
                                    </div>
                                    
                                    <div class="item-action-col">
                                        <div class="button-group" id="btn_group_{{ $item->id }}">
                                            <button type="button" class="answer-btn" onclick="setVal('{{ $item->id }}', '{{ $auditorName }}', 'YES', this)">Iya</button>
                                            <button type="button" class="answer-btn" onclick="setVal('{{ $item->id }}', '{{ $auditorName }}', 'NO', this)">Tidak</button>
                                            <button type="button" class="answer-btn" onclick="setVal('{{ $item->id }}', '{{ $auditorName }}', 'N/A', this)">N/A</button>
                                        </div>
                                    {{-- LOGIKA BARU: Hanya muncul jika tim audit > 1 orang --}}
        @if(count($responders) > 1)
            <button type="button" class="btn-more" onclick="openModal('{{ $item->id }}', '{{ addslashes($item->item_text) }}')">
                Respon Lain...
            </button>
        @endif
                                        <div id="hidden_inputs_{{ $item->id }}"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="notes-container">
                        <label class="notes-label"><i class="fas fa-edit"></i> Catatan Temuan ({{ $subCode }})</label>
                        <textarea name="audit_notes[{{ $subCode }}]" rows="3" class="notes-textarea" placeholder="Tuliskan temuan audit, bukti objektif, atau peluang peningkatan di sini...">{{ $existingNotes[$subCode] ?? '' }}</textarea>
                    </div>
                </div>
            @endforeach

            <div style="height: 100px;"></div>

            <div class="submit-bar">
                <div class="submit-wrapper">
                    <button type="submit" class="submit-audit">
                        <i class="fas fa-save"></i> Simpan & Lanjut
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div id="answerModal" class="modal">
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

    <script>
        const auditorName = "{{ $auditorName }}";
        const responders = @json($responders);
    </script>
    <script src="{{ asset('js/audit-script.js') }}"></script>
</body>
</html>
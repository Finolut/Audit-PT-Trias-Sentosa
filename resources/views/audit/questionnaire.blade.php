<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Audit Klausul {{ $currentMain }}</title>
    <link rel="stylesheet" href="{{ asset('css/audit-style.css') }}">
    <style>
        .answer-btn { transition: all 0.2s; font-weight: bold; cursor: pointer; padding: 8px 16px; border: 1px solid #ccc; border-radius: 4px; background: white; }
        .active-yes { background-color: #16a34a !important; color: white !important; border-color: #15803d !important; }
        .active-no { background-color: #dc2626 !important; color: white !important; border-color: #b91c1c !important; }
        .active-na { background-color: #6b7280 !important; color: white !important; border-color: #4b5563 !important; }
        .button-group { display: flex; gap: 8px; align-items: center; }
        .q-btn { min-width: 60px; }
        
        .sub-clause-container {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 50px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .clause-header {
            position: sticky;
            top: 0;
            background: #f8fafc;
            padding: 15px;
            border-bottom: 2px solid #2563eb;
            margin: -25px -25px 20px -25px;
            border-radius: 12px 12px 0 0;
            z-index: 10;
        }

        .question-box {
            margin-top: 25px;
            background-color: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 8px;
            padding: 20px;
        }
        .question-textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            margin-top: 10px;
            font-family: inherit;
            resize: vertical;
        }
        .submit-container {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 20px;
            box-shadow: 0 -4px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: center;
            z-index: 100;
        }
    </style>
</head>
<body style="background-color: #f1f5f9; padding: 40px 20px 120px 20px; font-family: sans-serif;">

    <div style="max-width: 900px; margin: 0 auto;">
        <a href="{{ route('audit.menu', $auditId) }}" style="text-decoration: none; color: #2563eb; font-weight: bold;">← Kembali ke Menu</a>
        
        <h1 style="margin-top: 20px; color: #1e293b;">Main Clause {{ $currentMain }}</h1>
        <p style="color: #64748b; margin-bottom: 40px;">Departemen: <strong>{{ $targetDept }}</strong> | Auditor: <strong>{{ $auditorName }}</strong></p>

        <form method="POST" action="/audit/{{ $auditId }}/{{ $currentMain }}" id="form">
            @csrf

            @foreach ($subClauses as $subCode)
                <div class="sub-clause-container">
                    <div class="clause-header">
                        <h2 style="margin: 0; font-size: 1.25rem; color: #1e293b;">
                            {{ $subCode }} – {{ $clauseTitles[$subCode] ?? 'Detail Klausul' }}
                        </h2>
                    </div>

                    @foreach ($maturityLevels as $level)
                        <div class="level-section" style="margin-top: 25px;">
                            <h4 style="color: #475569; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">
                                Maturity Level {{ $level->level_number }}
                            </h4>

                            @php $items = $itemsGrouped[$subCode] ?? collect(); @endphp
                            
                            @foreach ($items->where('maturity_level_id', $level->id) as $item)
                                <div class="item" style="padding: 15px 0; border-bottom: 1px dashed #e2e8f0;">
                                    <p style="margin-bottom: 12px; font-size: 15px; color: #334155;">{{ $item->item_text }}</p>
                                    
                                    <div class="button-group" id="btn_group_{{ $item->id }}">
                                        @if($responders->isEmpty())
                                            <button type="button" class="answer-btn q-btn q-btn-yes" onclick="submitQuickAnswer('{{ $item->id }}', 'YES')">YES</button>
                                            <button type="button" class="answer-btn q-btn q-btn-no" onclick="submitQuickAnswer('{{ $item->id }}', 'NO')">NO</button>
                                            <button type="button" class="answer-btn q-btn q-btn-na" onclick="submitQuickAnswer('{{ $item->id }}', 'N/A')">N/A</button>
                                        @else
                                            <button type="button" class="answer-btn" onclick="openModal('{{ $item->id }}')">Isi Jawaban</button>
                                            <button type="button" class="answer-btn na-btn" style="background: #64748b; color: white;" onclick="setAbsoluteNA('{{ $item->id }}')">N/A Mutlak</button>
                                        @endif
                                    </div>

                                    <div class="answer-info" id="info_{{ $item->id }}" style="margin-top:8px; font-size:12px; color: #94a3b8;">
                                        <em>Belum ada jawaban</em>
                                    </div>
                                    <div id="hidden_inputs_{{ $item->id }}"></div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    {{-- Catatan per SUB-KLAUSUL --}}
                    <div class="question-box">
                        <label style="font-weight: bold; color: #92400e;">Catatan Temuan / Pertanyaan Auditor ({{ $subCode }})</label>
                        <textarea 
                            name="audit_notes[{{ $subCode }}]" 
                            rows="3" 
                            class="question-textarea" 
                            placeholder="Tulis bukti audit atau temuan di sini..."
                        >{{ $existingNotes[$subCode] ?? '' }}</textarea>
                    </div>
                </div>
            @endforeach

            <div class="submit-container">
                <button type="submit" class="submit-audit" 
                        style="background: #2563eb; color: white; padding: 15px 40px; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);">
                    @if($nextMainClause)
                        Simpan & Lanjut ke Clause {{ $nextMainClause }} →
                    @else
                        Simpan & Selesaikan Audit ✓
                    @endif
                </button>
            </div>
        </form>
    </div>

    {{-- MODAL (Tetap sama namun pastikan JS mendukung) --}}
    <script>
        const auditorName = "{{ $auditorName }}";

        function submitQuickAnswer(itemId, val) {
            const container = document.getElementById(`hidden_inputs_${itemId}`);
            container.innerHTML = `
                <input type="hidden" name="answers[${itemId}][auditor_name]" value="${auditorName}">
                <input type="hidden" name="answers[${itemId}][answer]" value="${val}">
            `;

            const infoBox = document.getElementById(`info_${itemId}`);
            let colorClass = val === 'YES' ? 'active-yes' : (val === 'NO' ? 'active-no' : 'active-na');
            infoBox.innerHTML = `<span class="${colorClass}" style="padding: 2px 8px; border-radius: 4px;">Terpilih: ${val}</span>`;

            const btnGroup = document.getElementById(`btn_group_${itemId}`);
            btnGroup.querySelectorAll('.answer-btn').forEach(btn => btn.classList.remove('active-yes', 'active-no', 'active-na'));
            
            if(val === 'YES') btnGroup.querySelector('.q-btn-yes').classList.add('active-yes');
            if(val === 'NO') btnGroup.querySelector('.q-btn-no').classList.add('active-no');
            if(val === 'N/A') btnGroup.querySelector('.q-btn-na').classList.add('active-na');
        }
    </script>
</body>
</html>
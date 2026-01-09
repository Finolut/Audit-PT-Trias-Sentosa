<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Audit 4.1</title>
    <link rel="stylesheet" href="{{ asset('css/audit-style.css') }}">
    <style>
        /* Style tambahan untuk menandai tombol aktif */
        .answer-btn { transition: all 0.2s; font-weight: bold; }
        .active-yes { background-color: #16a34a !important; color: white !important; border-color: #15803d !important; }
        .active-no { background-color: #dc2626 !important; color: white !important; border-color: #b91c1c !important; }
        .active-na { background-color: #6b7280 !important; color: white !important; border-color: #4b5563 !important; }
        .button-group { display: flex; gap: 8px; align-items: center; }
        .q-btn { min-width: 60px; }
        
        /* Style untuk section pertanyaan */
        .question-box {
            margin-top: 40px;
            background-color: #fffbeb; /* Warna kuning muda agar terlihat beda */
            border: 1px solid #fcd34d;
            border-radius: 8px;
            padding: 20px;
        }
        .question-box h4 { margin-top: 0; color: #92400e; }
        .question-textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            margin-top: 10px;
            font-family: inherit;
            resize: vertical;
        }
    </style>
</head>
<body>
    <h2>Audit 4.1 – Context of the organization</h2>
    
    <form method="POST" action="/audit/{{ $auditId }}/4-1" id="form">
        @csrf

        @foreach ($maturityLevels as $level)
            <div class="level-section" style="margin-top: 30px;">
                <h3 style="background: #f3f4f6; padding: 8px; border-left: 4px solid #2563eb;">
                    Maturity Level {{ $level->level_number }}
                </h3>

                @foreach ($items[$level->id] ?? [] as $item)
                    <div class="item" style="border-bottom: 1px solid #eee; padding: 20px 0;">
                        <p style="margin-bottom: 12px; font-weight: 500;">{{ $item->item_text }}</p>
                        
                        <div class="button-group" id="btn_group_{{ $item->id }}">
                            @if($responders->isEmpty())
                                {{-- TAMPILAN SINGLE AUDITOR (LANGSUNG) --}}
                                <button type="button" class="answer-btn q-btn q-btn-yes" 
                                        onclick="submitQuickAnswer('{{ $item->id }}', 'YES')">YES</button>
                                <button type="button" class="answer-btn q-btn q-btn-no" 
                                        onclick="submitQuickAnswer('{{ $item->id }}', 'NO')">NO</button>
                                <button type="button" class="answer-btn q-btn q-btn-na" 
                                        onclick="submitQuickAnswer('{{ $item->id }}', 'N/A')">N/A</button>
                            @else
                                {{-- TAMPILAN MULTIPLE (MODAL) --}}
                                <button type="button" class="answer-btn" onclick="openModal('{{ $item->id }}')">
                                    Isi Jawaban
                                </button>

                                <button type="button" class="answer-btn na-btn" 
                                        style="background-color: #6c757d; color: white;"
                                        onclick="setAbsoluteNA('{{ $item->id }}')">
                                    N/A Mutlak
                                </button>
                            @endif
                        </div>

                        <div class="answer-info" id="info_{{ $item->id }}" style="margin-top:8px; font-size:13px; color: #666;">
                            <em>Belum ada jawaban</em>
                        </div>

                        {{-- Wadah Input Hidden Otomatis --}}
                        <div id="hidden_inputs_{{ $item->id }}"></div>
                    </div>
                @endforeach
            </div>
        @endforeach

        {{-- SECTION PERTANYAAN TAMBAHAN (BARU) --}}
        <div class="question-box">
            <h4>Catatan / Pertanyaan Auditor (Klausul 4.1)</h4>
            <p style="font-size: 13px; color: #666; margin-bottom: 10px;">
                Jika ada temuan, pertanyaan, atau catatan khusus untuk departemen terkait klausul ini, silakan tulis di bawah:
            </p>
            <textarea 
                name="audit_question" 
                rows="4" 
                class="question-textarea" 
                placeholder="Contoh: Apakah ada bukti tertulis mengenai isu internal yang disebutkan di soal no 2?"
            >{{ $existingQuestion ?? '' }}</textarea>
        </div>

        <div style="margin-top: 40px; padding-bottom: 100px;">
            <button type="submit" class="submit-audit" 
                    style="background: #2563eb; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px;">
                Simpan & Lanjut ke 4.2
            </button>
        </div>
    </form>

    {{-- MODAL (Hanya Aktif Jika Ada Responder) --}}
    <div class="modal" id="modal">
        <div class="modal-box">
            <div class="modal-header">
                <h4>Jawaban Audit</h4>
                <span class="modal-close" onclick="closeModal()">✕</span>
            </div>

            <div class="member auditor">
                <strong>{{ $auditorName }} (Auditor)</strong><br>
                <label><input type="radio" name="modal_auditor_answer" value="YES"> YES</label>
                <label><input type="radio" name="modal_auditor_answer" value="NO"> NO</label>
            </div>

            <hr>

            @foreach ($responders as $r)
                <div class="member responder">
                    <strong>{{ $r->responder_name }}</strong><br>
                    <label><input type="radio" name="modal_responder[{{ $r->responder_name }}]" value="YES"> YES</label>
                    <label><input type="radio" name="modal_responder[{{ $r->responder_name }}]" value="NO"> NO</label>
                </div>
            @endforeach

            <br>
            <button type="button" onclick="confirmAnswer()" class="confirm-btn" 
                    style="width: 100%; padding: 10px; background: #2563eb; color: white; border: none; border-radius: 4px;">
                OK
            </button>
        </div>
    </div>

    <script>
        const respondersList = @json($responders);
        const auditorName = "{{ $auditorName }}";

        // Fungsi khusus Auditor Tunggal (Sekali Klik)
        function submitQuickAnswer(itemId, val) {
            const container = document.getElementById(`hidden_inputs_${itemId}`);
            container.innerHTML = '';

            // Buat Input Hidden
            container.innerHTML += `<input type="hidden" name="answers[${itemId}][auditor_name]" value="${auditorName}">`;
            container.innerHTML += `<input type="hidden" name="answers[${itemId}][answer]" value="${val}">`;

            // Update Info & Warna Tombol
            const infoBox = document.getElementById(`info_${itemId}`);
            let colorClass = '';
            if(val === 'YES') colorClass = 'active-yes';
            else if(val === 'NO') colorClass = 'active-no';
            else colorClass = 'active-na';

            infoBox.innerHTML = `<span class="${colorClass}" style="padding: 2px 8px; border-radius: 4px;">Terpilih: ${val}</span>`;

            // Toggle Class Tombol
            const btnGroup = document.getElementById(`btn_group_${itemId}`);
            btnGroup.querySelectorAll('.q-btn').forEach(btn => {
                btn.classList.remove('active-yes', 'active-no', 'active-na');
            });
            
            if(val === 'YES') btnGroup.querySelector('.q-btn-yes').classList.add('active-yes');
            if(val === 'NO') btnGroup.querySelector('.q-btn-no').classList.add('active-no');
            if(val === 'N/A') btnGroup.querySelector('.q-btn-na').classList.add('active-na');
        }
    </script>
    <script src="{{ asset('js/audit-script.js') }}"></script>
</body>
</html>
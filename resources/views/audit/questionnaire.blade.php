<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Audit Klausul {{ $currentMain }}</title>
    <link rel="stylesheet" href="{{ asset('css/audit-style.css') }}">
</head>
<body class="audit-body">

    <div class="audit-container">
        <a href="{{ route('audit.menu', $auditId) }}" 
           onclick="return validateBeforeLeave(event)" 
           style="text-decoration: none; color: #2563eb; font-weight: bold;">← Kembali ke Menu</a>
        
        <h1 style="margin-top: 20px; color: #1e293b;">Main Clause {{ $currentMain }}</h1>
        <p style="color: #64748b; margin-bottom: 40px;">Departemen: <strong>{{ $targetDept }}</strong> | Auditor: <strong>{{ $auditorName }}</strong></p>

        <form method="POST" action="/audit/{{ $auditId }}/{{ $currentMain }}" id="form">
            @csrf

            @foreach ($subClauses as $subCode)
                <div class="sub-clause-container" id="container_{{ $subCode }}">
                    <div class="clause-header">
                        <h2 style="margin: 0; font-size: 1.25rem; color: #1e293b;">
                            {{ $subCode }} – {{ $clauseTitles[$subCode] ?? 'Detail Klausul' }}
                            <span id="status_{{ $subCode }}" style="font-size: 0.9rem; margin-left: 10px;"></span>
                        </h2>
                    </div>

                    @foreach ($maturityLevels as $level)
                        <div class="level-section" style="margin-top: 25px;">
                            <h4 style="color: #475569; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">
                                Maturity Level {{ $level->level_number }}
                            </h4>

                            @php 
                                $items = $itemsGrouped[$subCode] ?? collect();
                                $currentLevelItems = $items->where('maturity_level_id', $level->id);
                                // Hitung total item dalam satu sub-klausul untuk trigger pengiriman otomatis
                                $totalInSub = $items->count(); 
                            @endphp
                            
                            @foreach ($currentLevelItems as $item)
                                <div class="item" style="padding: 15px 0; border-bottom: 1px dashed #e2e8f0;">
                                    <p style="margin-bottom: 12px; font-size: 15px; color: #334155;">{{ $item->item_text }}</p>
                                    
                                    <div class="button-group" id="btn_group_{{ $item->id }}">
                                        <button type="button" class="answer-btn q-btn q-btn-yes" 
                                            onclick="submitQuickAnswer(event, '{{ $item->id }}', 'YES', '{{ $subCode }}', {{ $totalInSub }})">YES</button>
                                        
                                        <button type="button" class="answer-btn q-btn q-btn-no" 
                                            onclick="submitQuickAnswer(event, '{{ $item->id }}', 'NO', '{{ $subCode }}', {{ $totalInSub }})">NO</button>
                                        
                                        <button type="button" class="answer-btn q-btn q-btn-na" 
                                            onclick="submitQuickAnswer(event, '{{ $item->id }}', 'N/A', '{{ $subCode }}', {{ $totalInSub }})">N/A</button>

                                        <span style="margin: 0 10px; color: #cbd5e1;">|</span>

                                        <button type="button" class="answer-btn" style="background: #f8fafc; border-style: dashed;" 
                                            onclick="openModal('{{ $item->id }}', '{{ addslashes($item->item_text) }}')">
                                            Jawaban Berbeda...
                                        </button>
                                    </div>

                                    <div class="answer-info" id="info_{{ $item->id }}" style="margin-top:8px; font-size:12px; color: #94a3b8;">
                                        <em>Belum ada jawaban</em>
                                    </div>
                                    
                                    <div id="hidden_inputs_{{ $item->id }}"></div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

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
                <button type="submit" class="submit-audit" onclick="return validateBeforeLeave(event)">
                    @if($nextMainClause)
                        Simpan & Lanjut ke Clause {{ $nextMainClause }} →
                    @else
                        Simpan & Selesaikan Audit ✓
                    @endif
                </button>
            </div>
        </form>
    </div>

    <div id="answerModal" class="modal">
        <div class="modal-content">
            <h3 id="modalItemText" style="margin-top:0; font-size: 1rem; color: #1e293b;"></h3>
            <hr>
            <div id="modalRespondersList"></div>
            <div style="margin-top: 20px; text-align: right;">
                <button type="button" onclick="closeModal()" style="padding: 8px 20px; cursor:pointer; background: #2563eb; color: white; border: none; border-radius: 4px;">Tutup & Simpan</button>
            </div>
        </div>
    </div>

    <script>
        // Data global untuk diakses audit-script.js
        const auditorName = "{{ $auditorName }}";
        const responders = @json($responders);
        let answerQueue = []; // Inisialisasi agar tidak error di validateBeforeLeave

        function validateBeforeLeave(e) {
            // Cek jika ada pengiriman sub-klausul yang masih berjalan (isProcessing ada di audit-script.js)
            if (typeof isProcessing !== 'undefined' && isProcessing) {
                e.preventDefault();
                alert("Mohon tunggu sebentar, sistem sedang menyinkronkan data sub-klausul terakhir Anda.");
                return false;
            }
            return true;
        }

        // Peringatan jika tab browser ditutup paksa saat proses
        window.onbeforeunload = function() {
            if (typeof isProcessing !== 'undefined' && isProcessing) {
                return "Data sedang dikirim ke server. Tetap tinggalkan halaman?";
            }
        };
    </script>
    
    <script src="{{ asset('js/audit-script.js') }}"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Klausul {{ $currentMain }}</title>
    <link rel="stylesheet" href="{{ asset('css/audit-style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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

            @if(count($responders) > 1)
                <button type="button" class="btn-more mt-2" onclick="openModal('{{ $item->id }}', '{{ addslashes($item->item_text) }}')">
                    Respon Lain...
                </button>
            @endif

            {{-- ✅ Hidden input untuk jawaban dan answer ID mapping --}}
            @php
                // ✅ PERBAIKAN TYPO: hapus spasi di 'answer ' dan 'existingAn swer'
                $existingAnswer = $existingAnswers[$item->id][$auditorName] ?? null;
                $answerValue = $existingAnswer['answer'] ?? '';
                $findingLevel = $existingAnswer['finding_level'] ?? '';
                $findingNote = $existingAnswer['finding_note'] ?? '';
                $answerId = $existingAnswer['id'] ?? \Illuminate\Support\Str::uuid();
            @endphp

            {{-- ✅ HIDDEN INPUT UNTUK ANSWER ID - DI LUAR FINDING CONTAINER --}}
            <input type="hidden" 
                   name="answer_id_map[{{ $item->id }}][{{ $auditorName }}]" 
                   value="{{ $answerId }}">

            {{-- === INPUT FINDING + CATATAN TEMUAN PER AUDITOR === --}}
            <div class="finding-container mt-3 p-2 bg-gray-50 rounded border border-dashed border-gray-300">
                <div class="flex flex-wrap gap-4 items-end">
                    {{-- FINDING LEVEL --}}
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

                {{-- TEXTAREA CATATAN TEMUAN --}}
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
            {{-- === AKHIR FINDING + CATATAN === --}}
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
    // Toggle visibility catatan temuan
    function toggleFindingNote(itemId, auditorName, value) {
        const wrapper = document.getElementById(`finding_note_wrapper_${itemId}_${auditorName}`);
        if (wrapper) {
            wrapper.style.display = (value === '' || value === null) ? 'none' : 'block';
        }
    }

    // Event delegation untuk button YES/NO/N/A
    document.addEventListener('DOMContentLoaded', function() {
        // Handle klik button jawaban
        document.querySelectorAll('.answer-btn').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                const value = this.getAttribute('data-value');
                const auditorName = "{{ $auditorName }}";
                
                // Reset semua button di group ini
                document.querySelectorAll(`#btn_group_${itemId} .answer-btn`).forEach(btn => {
                    btn.classList.remove('active-yes', 'active-no', 'active-na');
                });
                
                // Set active class
                if (value === 'YES') {
                    this.classList.add('active-yes');
                } else if (value === 'NO') {
                    this.classList.add('active-no');
                } else if (value === 'N/A') {
                    this.classList.add('active-na');
                }
                
                // Update hidden input
                const answerInput = document.getElementById(`answer_input_${itemId}_${auditorName}`);
                if (answerInput) {
                    answerInput.value = value;
                }
                
                // Optional: Auto-hide finding note jika jawaban YES
                if (value === 'YES') {
                    const findingSelect = document.getElementById(`finding_level_${itemId}_${auditorName}`);
                    if (findingSelect) {
                        findingSelect.value = '';
                        toggleFindingNote(itemId, auditorName, '');
                    }
                }
            });
        });
    });

    function confirmSubmit() {
        Swal.fire({
            title: 'Simpan Jawaban?',
            text: "Apakah Anda sudah yakin dengan semua respon di klausul ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Cek Lagi'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                document.getElementById('form').submit();
            }
        });
    }

    @if(session('all_complete'))
        Swal.fire({
            title: 'Audit Selesai!',
            text: 'Semua klausul telah terisi 100%. Mengalihkan ke halaman laporan...',
            icon: 'success',
            timer: 3000,
            showConfirmButton: false
        }).then(() => {
            window.location.href = "{{ route('audit.thanks') }}";
        });
    @endif
</script>

<!-- Pastikan script eksternal dimuat setelah inline script -->
<script src="{{ asset('js/audit-script.js') }}"></script>
</body>
</html>
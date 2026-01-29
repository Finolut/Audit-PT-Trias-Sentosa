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
              id="form" 
              enctype="multipart/form-data">
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
                                            <button type="button" class="answer-btn" onclick="setVal('{{ $item->id }}', '{{ $auditorName }}', 'YES', this)">Iya</button>
                                            <button type="button" class="answer-btn" onclick="setVal('{{ $item->id }}', '{{ $auditorName }}', 'NO', this)">Tidak</button>
                                            <button type="button" class="answer-btn" onclick="setVal('{{ $item->id }}', '{{ $auditorName }}', 'N/A', this)">N/A</button>
                                        </div>

                                        @if(count($responders) > 1)
                                            <button type="button" class="btn-more mt-2" onclick="openModal('{{ $item->id }}', '{{ addslashes($item->item_text) }}')">
                                                Respon Lain...
                                            </button>
                                        @endif

                                        <div id="hidden_inputs_{{ $item->id }}"></div>

                                        {{-- === INPUT FINDING + EVIDENCE PER AUDITOR === --}}
                                        <div class="finding-container mt-3 p-2 bg-gray-50 rounded border border-dashed border-gray-300">
                                            <div class="flex flex-wrap gap-4 items-end">
                                                {{-- FINDING LEVEL --}}
                                                <div class="flex-1 min-w-[160px]">
                                                    <label class="block text-[10px] font-bold text-gray-500 uppercase">
                                                        Finding Level
                                                    </label>
                                                    <select 
                                                        name="finding_level[{{ $item->id }}][{{ $auditorName }}]" 
                                                        class="w-full text-sm border-gray-300 rounded focus:ring-blue-500">
                                                        <option value="">-- No Finding --</option>
                                                        <option value="observed"
                                                            {{ ($existingAnswers[$item->id][$auditorName] ?? null)?->finding_level === 'observed' ? 'selected' : '' }}>
                                                            Observed (OFI)
                                                        </option>
                                                        <option value="minor"
                                                            {{ ($existingAnswers[$item->id][$auditorName] ?? null)?->finding_level === 'minor' ? 'selected' : '' }}>
                                                            Minor NC
                                                        </option>
                                                        <option value="major"
                                                            {{ ($existingAnswers[$item->id][$auditorName] ?? null)?->finding_level === 'major' ? 'selected' : '' }}>
                                                            Major NC
                                                        </option>
                                                    </select>
                                                </div>

                                                {{-- HIDDEN ANSWER ID --}}
                                                <input type="hidden"
                                                       name="answer_id_map[{{ $item->id }}][{{ $auditorName }}]"
                                                       id="answer_id_{{ $item->id }}_{{ $auditorName }}">

                                                {{-- EVIDENCE FILE INPUT (DINAMIS) --}}
                                                <div class="flex-1 min-w-[200px]">
                                                    <label class="block text-[10px] font-bold text-gray-500 uppercase">
                                                        Evidence Photo
                                                    </label>
                                                    <input
                                                        type="file"
                                                        data-item="{{ $item->id }}"
                                                        data-auditor="{{ $auditorName }}"
                                                        accept="image/*"
                                                        multiple
                                                        class="block w-full text-xs text-gray-400
        file:mr-2 file:py-1 file:px-2
        file:rounded file:border-0
        file:bg-gray-200 file:text-gray-500
        cursor-not-allowed
        pointer-events-none">
                                                </div>
                                            </div>
                                        </div>
                                        {{-- === AKHIR FINDING + EVIDENCE === --}}
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
    const auditorName = "{{ $auditorName }}";
    const responders = @json($responders);
    const dbAnswers = @json($existingAnswers ?? []);

    function setVal(itemId, auditor, val, btn) {
        // Reset active classes
        document.querySelectorAll(`#btn_group_${itemId} .answer-btn`).forEach(el => {
            el.classList.remove('active-yes', 'active-no', 'active-na');
        });

        // Set active class
        if (val === 'YES') btn.classList.add('active-yes');
        else if (val === 'NO') btn.classList.add('active-no');
        else if (val === 'N/A') btn.classList.add('active-na');

        // Generate answer ID
        const answerId = crypto.randomUUID();

        // Simpan ke hidden input
        const hiddenInput = document.getElementById(`answer_id_${itemId}_${auditor}`);
        if (hiddenInput) {
            hiddenInput.value = answerId;
        }

        // Update nama input file dan aktifkan
        const fileInput = document.querySelector(
            `input[data-item="${itemId}"][data-auditor="${auditor}"]`
        );
        if (fileInput) {
            fileInput.name = `evidence[${answerId}][]`;
            // Hapus kelas yang menonaktifkan
            fileInput.classList.remove(
                'pointer-events-none',
                'cursor-not-allowed',
                'text-gray-400'
            );
            fileInput.classList.add('text-gray-700');
            fileInput.disabled = false; // pastikan benar-benar enabled
        }
    } // <-- INI YANG KURANG: TUTUP FUNGSI setVal()

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
    <script src="{{ asset('js/audit-script.js') }}"></script>
</body>
</html>
@extends('layouts.menu')

@section('content')
<div class="max-w-7xl mx-auto">

<div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">

<!-- HEADER / INSTRUCTION - DIPERKAYA DENGAN INFORMASI LENGKAP -->

<div class="p-6 text-center">
    <div class="inline-flex items-center justify-center rounded-full p-4 mb-4" 
         style="background-color: #edea18fd; color: white;">
        <i class="fas fa-book-open text-3xl"></i>
    </div>
</div>

<h1 class="text-3xl font-bold text-gray-900 mb-4 text-center">
    Persiapan & Panduan Lengkap
</h1>

<div class="max-w-4xl mx-auto mb-8">
    <p class="text-lg text-gray-700 mb-4">
        Selamat datang di sistem audit internal. Berikut langkah-langkah untuk memastikan proses audit berjalan efektif:
    </p>
    <div class="bg-white border rounded-2xl p-6 text-left shadow-sm" style="border-color: rgba(26, 54, 93, 0.2);">
        <ol class="grid grid-cols-1 md:grid-cols-3 gap-4 text-left">
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 text-white rounded-full flex items-center justify-center text-sm mr-3 mt-1" 
                      style="background-color: #1a365d;">1</span>
                <div>
                    <h4 class="font-bold text-gray-800">Pilih Klausul</h4>
                    <p class="text-sm text-gray-600 mt-1">Gunakan sidebar kiri untuk memilih klausul yang akan diarahkan ke pertanyaan sesuai dengan standar ISO 14001</p>
                </div>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 text-white rounded-full flex items-center justify-center text-sm mr-3 mt-1" 
                      style="background-color: #1a365d;">2</span>
                <div>
                    <h4 class="font-bold text-gray-800">Jawab Pertanyaan</h4>
                    <p class="text-sm text-gray-600 mt-1">Isi semua pertanyaan berdasarkan kondisi aktual departemen. Jawaban dibagi 3 jenis: <span class="font-semibold text-green-600">YES</span>, <span class="font-semibold text-red-600">NO</span>, dan <span class="font-semibold text-gray-500">N/A</span>. Lampirkan catatan temuan jika diperlukan.</p>
                </div>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 text-white rounded-full flex items-center justify-center text-sm mr-3 mt-1" 
                      style="background-color: #1a365d;">3</span>
                <div>
                    <h4 class="font-bold text-gray-800">Simpan Klausul</h4>
                    <p class="text-sm text-gray-600 mt-1">Sistem akan menyimpan otomatis semua jawaban yang user input. Semua jawaban user akan terekam dan pastikan semua pertanyaan terisi penuh.</p>
                </div>
            </li>
        </ol>
    </div>
</div>

<!-- CRITICAL TOKEN SECTION - VERSI POLos & CLEAN -->
<div class="max-w-4xl mx-auto mb-10">
    <div class="bg-white border rounded-2xl p-6 shadow-sm" style="border-color: rgba(26, 54, 93, 0.2);">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-start">
                    <div class="shrink-0 mt-0.5">
                        <i class="fas fa-key text-xl" style="color: #1a365d;"></i>
                    </div>
                    <div class="ml-3">
                        <h2 class="text-lg font-bold text-gray-800 mb-1">Token Audit</h2>
                        <p class="text-sm text-gray-600 mb-2">
                            <span class="font-semibold text-gray-800">Penting:</span> Simpan kode ini untuk melanjutkan audit di kemudian hari. 
                            Dengan kode ini, progress audit <span class="font-medium" style="color: #1a365d;">dapat dipulihkan</span>.
                            Jika ada kendala dengan token audit bisa menghubungi Admin 
                            <span class="font-semibold text-gray-700">Brahmanto Anggoro Laksono - SSSE</span>
                        </p>
                        <p class="text-xs text-gray-500 bg-gray-50 border border-gray-200 rounded px-3 py-2">
                            <span class="font-medium">Tips:</span> Catat di buku, screenshot, atau simpan token di tempat yang kita ingat
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="w-full sm:w-auto">
                <div class="flex flex-col gap-2">
                    <div class="flex items-stretch">
                        <div id="audit-token" class="flex-1 bg-gray-50 border border-gray-300 text-gray-800 font-mono font-medium text-sm px-4 py-3 rounded-l-lg break-all min-w-[250px]">
                            {{ $resumeToken ?? 'TOKEN_TIDAK_TERSEDIA' }}
                        </div>
                        <button id="copy-token-btn" 
                                class="text-white font-medium px-4 py-3 rounded-r-lg flex items-center gap-2 transition-opacity duration-200 whitespace-nowrap {{ !$resumeToken ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-90' }}"
                                style="background-color: #1a365d;"
                                {{ !$resumeToken ? 'disabled' : '' }}
                                aria-label="Salin Kode Audit">
                            <i class="fas fa-copy"></i>
                            <span class="hidden sm:inline">Salin</span>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 text-right">
                        Klik tombol Salin untuk menyalin ke clipboard
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
    
</div>

<!-- AUDIT SWITCHER - DENGAN INTERAKTIF PROGRESS -->
@if(isset($relatedAudits) && count($relatedAudits) > 1)
<div class="border-t border-gray-200 bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                <i class="fas fa-building text-indigo-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">
                Departemen Yang Di Audit Oleh <span class="text-indigo-600">{{ $auditorName }}</span>
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($relatedAudits as $auditInfo)
                @php
                    $deptNameTab = DB::table('departments')->where('id', $auditInfo['dept_id'])->value('name');
                    $isCurrent = $auditInfo['id'] === $currentAuditId;
                    $auditStatus = DB::table('audits')->where('id', $auditInfo['id'])->value('status');
                    $isCompleted = in_array($auditStatus, ['COMPLETE', 'COMPLETED']);
                @endphp

                <div class="audit-department-card" 
                     data-audit-id="{{ $auditInfo['id'] }}"
                     data-dept-name="{{ $deptNameTab }}"
                     data-is-current="{{ $isCurrent ? 'true' : 'false' }}">
                    <div class="block p-4 rounded-xl border-2 transition-all cursor-pointer
                               {{ $isCurrent ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-200 scale-[1.02]' : 'border-gray-300 bg-white hover:border-blue-400 hover:bg-blue-50' }}
                               hover:shadow-md hover:-translate-y-0.5 department-card"
                         title="{{ $isCompleted ? 'Audit selesai' : ($isCurrent ? 'Audit sedang dikerjakan' : 'Klik untuk lihat progress audit departemen ini') }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-building text-indigo-600"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $deptNameTab }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        @if($isCompleted)
                                            <span class="text-green-600 flex items-center gap-1"><i class="fas fa-check-circle"></i> Selesai</span>
                                        @elseif($isCurrent)
                                            <span class="text-blue-600 flex items-center gap-1"><i class="fas fa-spinner fa-spin"></i> Sedang Dikerjakan</span>
                                        @else
                                            <span class="text-gray-500 flex items-center gap-1"><i class="far fa-clock"></i> Belum Dimulai</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if($isCurrent)
                                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full">
                                    AKTIF
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <p class="mt-4 text-center text-sm text-gray-500">
            <i class="fas fa-info-circle mr-1"></i> Klik pada departemen untuk melihat progress audit terkait
        </p>
    </div>
</div>
@endif

<!-- QUICK ACCESS GRID - DENGAN LOADING STATE -->
<div class="mt-6">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <i class="fas fa-chart-line text-blue-600 text-xl"></i>
            <h3 class="text-lg font-bold text-gray-900">Progress Audit</h3>
        </div>
        <div id="current-dept-display" class="text-sm text-gray-600">
            <i class="fas fa-building mr-1"></i>
            <span id="dept-name">{{ $currentDeptName ?? 'Departemen' }}</span>
        </div>
    </div>
    
    <div id="progress-loading" class="hidden">
        <div class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mr-3"></div>
            <span class="text-gray-600">Memuat progress audit...</span>
        </div>
    </div>

    <div id="progress-grid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
        @php
            $clauses = [4,5,6,7,8,9,10];
        @endphp
        @foreach($clauses as $clauseNum)
            @php
                $progress = $clauseProgress[$clauseNum] ?? ['percentage' => 0, 'count' => 0, 'total' => 5];
                $isCompleted = $progress['percentage'] >= 100;
                $badgeClass = $isCompleted ? 'completed' : ($progress['count'] > 0 ? 'in-progress' : '');
            @endphp
            <a href="{{ route('audit.show', ['id' => $auditId, 'clause' => $clauseNum]) }}"
               class="block p-4 bg-white border rounded-lg hover:shadow-md transition-all hover:-translate-y-1 {{ $isCompleted ? 'border-green-400' : 'border-blue-200' }}">
                <div class="text-center">
                    <div class="text-2xl font-bold mb-1 {{ $isCompleted ? 'text-green-600' : 'text-blue-600' }}">
                        @if($isCompleted) ✅ @else {{ $clauseNum }} @endif
                    </div>
                    <div class="text-xs font-medium text-gray-600 mb-1">Klausul {{ $clauseNum }}</div>
                    <div class="clause-badge {{ $badgeClass }} inline-block text-[10px]">
                        {{ $progress['count'] }}/{{ $progress['total'] }}
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
    <!-- FINISH BANNER -->
    @if(isset($allFinished) && $allFinished)
        <div class="finish-banner mt-6">
            <div class="finish-icon">🎉</div>
            <h2 class="finish-message">Audit {{ $deptName }} Selesai!</h2>
            <p class="finish-subtext">Semua klausul telah diisi dengan lengkap dan siap direview. Silakan selesaikan proses audit untuk menghasilkan laporan final.</p>
            
            <div class="banner-actions">
                <a href="{{ route('audit.finish') }}" class="btn btn-success">
                    <i class="fas fa-check-circle"></i> Selesaikan Audit
                </a>
                @if(isset($relatedAudits) && count($relatedAudits) > 1)
                    <a href="{{ route('audit.menu', ['id' => $relatedAudits[0]['id'] ?? $auditId]) }}" 
                       class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Audit Lainnya
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

<style>
    /* === HEADER === */
    .header-card {
        background: white;
        padding: 2.5rem;
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .header-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 6px;
        background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
    }

    .header-title {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
    }

    .header-subtitle {
        color: #475569;
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
    }

    /* === INFO GRID === */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #2563eb;
        color: white;
        font-size: 1rem;
    }

    .info-text {
        font-weight: 600;
        color: #0f172a;
    }

    .info-label {
        font-size: 0.85rem;
        color: #475569;
    }

    /* === PROGRESS SECTION === */
    .progress-section {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        padding: 1.5rem;
        border-radius: 12px;
        border: 2px solid #2563eb;
        margin-bottom: 1.5rem;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .progress-title {
        font-weight: 600;
        color: #0f172a;
        font-size: 1.1rem;
    }

    .progress-value {
        font-weight: 700;
        color: #2563eb;
        font-size: 1.2rem;
    }

    .progress-bar-wrapper {
        height: 12px;
        background: white;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
        transition: width 0.5s ease;
        border-radius: 6px;
    }

    /* === TOKEN BANNER === */
    .token-banner {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        padding: 1.25rem;
        border-radius: 12px;
        border: 2px solid #f59e0b;
        margin-top: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .token-label {
        font-weight: 600;
        color: #0f172a;
        font-size: 0.95rem;
    }

    .token-value {
        background: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 700;
        color: #0f172a;
        font-family: 'Courier New', monospace;
        font-size: 1.1rem;
        border: 2px solid #e2e8f0;
    }

    /* === AUDIT SWITCHER === */
    .audit-switcher {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
        border: 1px solid #e2e8f0;
    }

    .switcher-title {
        font-weight: 700;
        color: #0f172a;
        font-size: 1.25rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .audit-tabs {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .audit-tab {
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border: 2px solid transparent;
        color: #334155;
    }

    .audit-tab:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .audit-tab.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .audit-tab.completed {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }

    /* === BUTTONS === */
    .btn {
        padding: 0.875rem 1.75rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.3s;
        border: 2px solid;
    }

    .btn-primary {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }

    .btn-primary:hover {
        background: white;
        color: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-outline {
        background: transparent;
        color: #334155;
        border-color: #cbd5e1;
    }

    .btn-outline:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }

    .btn-success {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }

    .btn-success:hover {
        background: white;
        color: #10b981;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* === STATUS BADGE === */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .status-in-progress {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #2563eb;
        border: 1px solid #2563eb;
    }

    .status-completed {
        background: linear-gradient(135deg, #f0fdf4 0%, #e6fffa 100%);
        color: #10b981;
        border: 1px solid #10b981;
    }

    /* === CLAUSE BADGE === */
    .clause-badge {
        min-width: 20px;
        height: 20px;
        border-radius: 50%;
        font-size: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .clause-badge.in-progress { 
        background: #dbeafe; 
        color: #1d4ed8; 
    }

    .clause-badge.completed { 
        background: #dcfce7; 
        color: #15803d; 
    }

    /* === FINISH BANNER === */
    .finish-banner {
        background: linear-gradient(135deg, #f0fdf4 0%, #e6fffa 100%);
        border-radius: 16px;
        padding: 2.5rem;
        text-align: center;
        border: 2px solid #10b981;
        animation: fadeInUp 0.7s ease-out;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .finish-icon {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        display: block;
        color: #10b981;
    }

    .finish-message {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 1rem;
    }

    .finish-subtext {
        color: #475569;
        font-size: 1.05rem;
        max-width: 600px;
        margin: 0 auto 1.75rem;
        line-height: 1.7;
    }

    .banner-actions {
        display: flex;
        justify-content: center;
        gap: 1.25rem;
        flex-wrap: wrap;
    }

    /* === RESPONSIVE === */
    @media (max-width: 768px) {
        .header-card {
            padding: 1.5rem;
        }

        .header-title {
            font-size: 1.5rem;
        }

        .header-subtitle {
            font-size: 0.95rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .btn {
            width: 100%;
            max-width: 320px;
        }

        .audit-tabs {
            flex-direction: column;
        }

        .audit-tab {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- COPY FUNCTIONALITY SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const copyBtn = document.getElementById('copy-token-btn');
    const tokenElement = document.getElementById('audit-token');
    const originalBtnHTML = copyBtn.innerHTML;
    
    if (copyBtn && tokenElement && !copyBtn.disabled) {
        copyBtn.addEventListener('click', async () => {
            try {
                // Clean token value (remove extra spaces)
                const tokenValue = tokenElement.textContent.trim();
                
                // Copy to clipboard
                await navigator.clipboard.writeText(tokenValue);
                
                // Visual feedback
                copyBtn.innerHTML = '<i class="fas fa-check mr-1"></i><span>Tersalin!</span>';
                copyBtn.classList.replace('bg-amber-500', 'bg-green-500');
                copyBtn.classList.replace('hover:bg-amber-600', 'hover:bg-green-600');
                
                // Reset after 2 seconds
                setTimeout(() => {
                    copyBtn.innerHTML = originalBtnHTML;
                    copyBtn.classList.replace('bg-green-500', 'bg-amber-500');
                    copyBtn.classList.replace('hover:bg-green-600', 'hover:bg-amber-600');
                }, 2000);
                
                // Optional: Show toast notification
                if (typeof showToast !== 'undefined') {
                    showToast('Kode Audit berhasil disalin ke clipboard!', 'success');
                }
            } catch (err) {
                console.error('Gagal menyalin token:', err);
                if (typeof showToast !== 'undefined') {
                    showToast('Gagal menyalin kode. Silakan salin manual.', 'error');
                } else {
                    alert('Berhasil disalin! Pastikan untuk menyimpan kode ini di tempat aman.');
                }
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const departmentCards = document.querySelectorAll('.audit-department-card');
    const progressGrid = document.getElementById('progress-grid');
    const progressLoading = document.getElementById('progress-loading');
    const deptNameDisplay = document.getElementById('dept-name');
    const currentDeptName = '{{ $currentDeptName ?? '' }}';
    const currentAuditId = '{{ $currentAuditId }}';

    // Set initial department name
    if (currentDeptName && deptNameDisplay) {
        deptNameDisplay.textContent = currentDeptName;
    }

    // Add click event to each department card
    departmentCards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Don't trigger if clicking on actual link
            if (e.target.tagName === 'A' || e.target.closest('a')) {
                return;
            }

            const auditId = this.dataset.auditId;
            const deptName = this.dataset.deptName;
            const isCurrent = this.dataset.isCurrent === 'true';

            // If already active, don't reload
            if (isCurrent) {
                return;
            }

            // Update UI immediately for better UX
            updateActiveDepartment(this);
            
            // Load progress data
            loadAuditProgress(auditId, deptName);
        });
    });

    function updateActiveDepartment(clickedCard) {
        // Remove active state from all cards
        document.querySelectorAll('.department-card').forEach(el => {
            el.classList.remove('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200', 'scale-[1.02]');
            el.classList.add('border-gray-300', 'bg-white');
            
            // Remove AKTIF badge from all
            const badge = el.querySelector('.bg-blue-100');
            if (badge) badge.remove();
        });

        // Add active state to clicked card
        const card = clickedCard.querySelector('.department-card');
        card.classList.remove('border-gray-300', 'bg-white');
        card.classList.add('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200', 'scale-[1.02]');

        // Add AKTIF badge
        const badgeContainer = clickedCard.querySelector('.flex.items-center.justify-between');
        if (badgeContainer) {
            const badgeHTML = '<span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full">AKTIF</span>';
            badgeContainer.insertAdjacentHTML('beforeend', badgeHTML);
        }
    }

    function loadAuditProgress(auditId, deptName) {
        // Show loading state
        progressGrid.classList.add('hidden');
        progressLoading.classList.remove('hidden');

        // Update department name display
        if (deptNameDisplay) {
            deptNameDisplay.textContent = deptName;
        }

        // Fetch progress data from server
        fetch(`/api/audit/${auditId}/progress`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateProgressGrid(data.progress, auditId);
                } else {
                    showError('Gagal memuat progress audit');
                }
            })
            .catch(error => {
                console.error('Error loading progress:', error);
                showError('Terjadi kesalahan saat memuat data');
            })
            .finally(() => {
                // Hide loading state
                progressLoading.classList.add('hidden');
                progressGrid.classList.remove('hidden');
            });
    }

    function updateProgressGrid(progressData, auditId) {
        let gridHTML = '';
        const clauses = [4, 5, 6, 7, 8, 9, 10];

        clauses.forEach(clauseNum => {
            const progress = progressData[clauseNum] || { percentage: 0, count: 0, total: 5 };
            const isCompleted = progress.percentage >= 100;
            const badgeClass = isCompleted ? 'completed' : (progress.count > 0 ? 'in-progress' : '');

            gridHTML += `
                <a href="/audit/${auditId}/clause/${clauseNum}"
                   class="block p-4 bg-white border rounded-lg hover:shadow-md transition-all hover:-translate-y-1 ${isCompleted ? 'border-green-400' : 'border-blue-200'}">
                    <div class="text-center">
                        <div class="text-2xl font-bold mb-1 ${isCompleted ? 'text-green-600' : 'text-blue-600'}">
                            ${isCompleted ? '✅' : clauseNum}
                        </div>
                        <div class="text-xs font-medium text-gray-600 mb-1">Klausul ${clauseNum}</div>
                        <div class="clause-badge ${badgeClass} inline-block text-[10px]">
                            ${progress.count}/${progress.total}
                        </div>
                    </div>
                </a>
            `;
        });

        progressGrid.innerHTML = gridHTML;
    }

    function showError(message) {
        // Create error notification
        const errorDiv = document.createElement('div');
        errorDiv.className = 'bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg mb-4';
        errorDiv.innerHTML = `
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-red-400 mt-0.5 mr-3"></i>
                <div>
                    <p class="text-sm text-red-700 font-medium">${message}</p>
                </div>
            </div>
        `;
        
        // Insert before progress grid
        progressGrid.parentNode.insertBefore(errorDiv, progressGrid);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }

    // Optional: Add keyboard navigation support
    departmentCards.forEach((card, index) => {
        const deptCard = card.querySelector('.department-card');
        if (deptCard) {
            deptCard.tabIndex = 0;
            deptCard.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    card.click();
                }
            });
        }
    });
});
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
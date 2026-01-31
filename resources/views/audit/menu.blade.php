@extends('layouts.menu')

@section('content')
<!-- JARAK ATAS DITAMBAHKAN DI SINI -->
<div class="max-w-7xl mx-auto mt-10">

<!-- BORDER UTAMA: TEBA L 4PX, WARNA BIRU TUUA, SUDUT SIKU-SIKU (SAMPAI POJOK) -->
<div class="bg-white shadow-lg overflow-hidden" style="border: 4px solid #1a365d; border-radius: 0;">

<!-- HEADER / INSTRUCTION - DIPERKAYA DENGAN INFORMASI LENGKAP -->
<h1 class="text-3xl font-bold text-gray-900 mb-4 text-center">
    Mulai Audit Internal
</h1>

<div class="max-w-4xl mx-auto mb-8">
    <p class="text-lg text-gray-700 mb-4">
       Halaman ini digunakan untuk mengisi audit internal berdasarkan kondisi aktual departemen.
    </p>
    <div class="bg-white border rounded-2xl p-6 text-left shadow-sm" style="border-color: rgba(26, 54, 93, 0.2);">
        <ol class="grid grid-cols-1 md:grid-cols-3 gap-4 text-left">
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 text-white rounded-full flex items-center justify-center text-sm mr-3 mt-1" 
                      style="background-color: #1a365d;">1</span>
                <div>
                    <h4 class="font-bold text-gray-800">Pilih Klausul Audit</h4>
                    <p class="text-sm text-gray-600 mt-1">Setiap klausul berisi pertanyaan yang wajib dijawab sesuai kondisi aktual departemen.</p>
                </div>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 text-white rounded-full flex items-center justify-center text-sm mr-3 mt-1" 
                      style="background-color: #1a365d;">2</span>
                <div>
                    <h4 class="font-bold text-gray-800">Jawab Pertanyaan Audit</h4>
                    <p class="text-sm text-gray-600 mt-1">Jawaban harus mencerminkan kondisi aktual departemen.
YES: Klausul telah diterapkan
NO: Klausul tidak diterapkan (wajib isi catatan)
N/A: Tidak relevan dengan departemen</p>
                </div>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 text-white rounded-full flex items-center justify-center text-sm mr-3 mt-1" 
                      style="background-color: #1a365d;">3</span>
                <div>
                    <h4 class="font-bold text-gray-800">Penyimpanan Otomatis</h4>
                    <p class="text-sm text-gray-600 mt-1">Jawaban disimpan otomatis. Pastikan seluruh pertanyaan dalam satu klausul telah terisi sebelum berpindah.</p>
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
                        <h2 class="text-lg font-bold text-gray-800 mb-1">Token Audit (WAJIB DISIMPAN)</h2>
                        <p class="text-sm text-gray-600 mb-2">
                            <span class="font-semibold text-gray-800">Penting:</span> Simpan kode ini untuk melanjutkan audit di kemudian hari. 
                            Dengan kode ini, progress audit <span class="font-medium" style="color: #1a365d;">dapat dipulihkan</span>.
                            Jika ada kendala dengan token audit bisa menghubungi Admin 
                            <span class="font-semibold text-gray-700">Brahmanto Anggoro Laksono - SSSE</span>
                        </p>
                        <p class="text-xs text-gray-500 bg-gray-50 border border-gray-200 rounded px-3 py-2">
                            <span class="font-medium">Tips:</span>Simpan token di tempat aman (catatan kerja, dokumen internal, atau screenshot).
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


<!-- PROGRESS PER DEPARTEMEN -->
<div class="mt-6">
    @if($relatedAudits)
        <div class="space-y-6">
            @foreach($relatedAudits as $dept)
                <div class="border border-gray-200 rounded-xl p-5 bg-white shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-bold text-gray-800 text-xl flex items-center gap-2">
                            <i class="fas fa-building text-blue-600"></i>
                            {{ $dept['dept_name'] }}
                        </h4>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-7 gap-3">
                        @php $clauses = [4,5,6,7,8,9,10]; @endphp
                        @foreach($clauses as $clauseNum)
                            @php
                                $p = $dept['clauses'][$clauseNum] ?? ['percentage' => 0, 'count' => 0, 'total' => 0];
                                $isCompleted = $p['percentage'] >= 100;
                                $badgeClass = $isCompleted 
                                    ? 'bg-green-100 text-green-800' 
                                    : ($p['count'] > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600');
                            @endphp
                            <a href="{{ route('audit.show', ['id' => $dept['id'], 'clause' => $clauseNum]) }}"
                               class="block p-4 bg-white border rounded-lg hover:shadow-md transition-all text-center group">
                                <div class="text-base font-bold mb-1 {{ $isCompleted ? 'text-green-600' : 'text-blue-600' }}">
                                    @if($isCompleted) ✅ @else {{ $clauseNum }} @endif
                                </div>
                                <div class="text-xs text-gray-500 mt-1">Klausul {{ $clauseNum }}</div>
                                <div class="mt-1 inline-block px-2 py-1 rounded text-xs font-medium {{ $badgeClass }}">
                                    {{ $p['count'] }}/{{ $p['total'] }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-6 text-gray-500">
            Tidak ada departemen yang tersedia.
        </div>
    @endif
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
</div> <!-- Penutup card utama dengan border tebal -->

</div> <!-- Penutup container max-w-7xl dengan mt-10 -->

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
@push('scripts')
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

    departmentCards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('a')) return;
            
            const auditId = this.dataset.auditId;
            const deptName = this.dataset.deptName;
            const isCurrent = this.dataset.isCurrent === 'true';
            if (isCurrent) return;

            activateDepartmentCard(this, auditId, deptName);
            fetchAuditProgress(auditId, deptName);
        });
    });

    function activateDepartmentCard(cardElement, auditId, deptName) {
        // Reset semua kartu
        document.querySelectorAll('.department-card').forEach(el => {
            el.className = el.className.replace(/(border-blue-500|bg-blue-50|ring-2|ring-blue-200|scale-\[1\.02\]|border-gray-300|bg-white)/g, '').trim();
            el.classList.add('border-gray-300', 'bg-white');
            
            const badge = el.querySelector('.bg-blue-100.text-blue-800');
            if (badge) badge.remove();
        });

        // Aktifkan kartu terpilih
        const card = cardElement.querySelector('.department-card');
        card.classList.remove('border-gray-300', 'bg-white');
        card.classList.add('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200', 'scale-[1.02]');

        const container = cardElement.querySelector('.flex.items-center.justify-between');
        if (container) {
            container.insertAdjacentHTML('beforeend', 
                '<span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full ml-2">AKTIF</span>'
            );
        }

        document.querySelectorAll('.audit-department-card').forEach(el => {
            el.dataset.isCurrent = 'false';
        });
        cardElement.dataset.isCurrent = 'true';

        // Update URL tanpa reload
        if (history.pushState) {
            history.pushState({ auditId }, '', `/audit/menu/${auditId}`);
        }
    }

    function fetchAuditProgress(auditId, deptName) {
        progressGrid.classList.add('opacity-50', 'pointer-events-none');
        progressLoading.classList.remove('hidden');
        if (deptNameDisplay) deptNameDisplay.textContent = deptName;

        fetch(`/api/audit/${auditId}/progress`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.ok ? response.json() : Promise.reject('Response not ok'))
        .then(data => {
            if (data.success && data.progress) {
                renderProgressGrid(data.progress, auditId);
            } else {
                throw new Error('Invalid progress data');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showNotification('Gagal memuat progress. Periksa koneksi internet Anda.', 'error');
        })
        .finally(() => {
            progressLoading.classList.add('hidden');
            progressGrid.classList.remove('opacity-50', 'pointer-events-none');
        });
    }

    function renderProgressGrid(progressData, auditId) {
        const clauses = [4, 5, 6, 7, 8, 9, 10];
        let gridHTML = '';

        clauses.forEach(clauseNum => {
            const p = progressData[clauseNum] || { percentage: 0, count: 0, total: 5 };
            const isCompleted = p.percentage >= 100;
            const badgeClass = isCompleted 
                ? 'bg-green-100 text-green-800' 
                : (p.count > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600');

            gridHTML += `
            <a href="/audit/${auditId}/clause/${clauseNum}"
               class="block p-4 bg-white border rounded-lg hover:shadow-md transition-all hover:-translate-y-1 ${isCompleted ? 'border-green-400' : 'border-blue-200'}">
                <div class="text-center">
                    <div class="text-2xl font-bold mb-1 ${isCompleted ? 'text-green-600' : 'text-blue-600'}">
                        ${isCompleted ? '✅' : clauseNum}
                    </div>
                    <div class="text-xs font-medium text-gray-600 mb-1">Klausul ${clauseNum}</div>
                    <div class="inline-block px-1.5 py-0.5 rounded text-[10px] font-medium ${badgeClass}">
                        ${p.count}/${p.total}
                    </div>
                </div>
            </a>`;
        });

        progressGrid.innerHTML = gridHTML;
    }

    function showNotification(message, type = 'info') {
        let container = document.getElementById('notification-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-container';
            container.className = 'fixed top-4 right-4 z-50 max-w-md';
            document.body.appendChild(container);
        }

        const color = type === 'error' ? 'red' : 'blue';
        const icon = type === 'error' ? 'fa-exclamation-triangle' : 'fa-info-circle';
        
        const notification = document.createElement('div');
        notification.className = `flex items-start bg-${color}-50 border-l-4 border-${color}-500 p-4 rounded-r-lg mb-3 shadow-lg animate-fade-in`;
        notification.innerHTML = `
            <i class="fas ${icon} text-${color}-400 mt-0.5 mr-3"></i>
            <div class="text-sm text-${color}-700">${message}</div>
        `;

        container.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('animate-fade-out');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    // Animasi CSS untuk notifikasi
    if (!document.getElementById('notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            @keyframes fadeIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
            @keyframes fadeOut { from { opacity: 1; transform: translateX(0); } to { opacity: 0; transform: translateX(20px); } }
            .animate-fade-in { animation: fadeIn 0.3s ease-out; }
            .animate-fade-out { animation: fadeOut 0.3s ease-in; }
        `;
        document.head.appendChild(style);
    }
});
</script>
@endpush
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
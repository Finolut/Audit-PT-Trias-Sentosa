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

<!-- DEPARTMENT PROGRESS CARDS - MENAMPILKAN SEMUA DEPARTEMEN -->
@if(isset($relatedAudits) && count($relatedAudits) > 0)
    <div class="mt-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="fas fa-tasks text-blue-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">
                Progress Audit Semua Departemen
            </h3>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @php
                $clauses = [4, 5, 6, 7, 8, 9, 10];
            @endphp

            @foreach($relatedAudits as $auditInfo)
                @php
                    // Ambil nama departemen
                    $dept = \DB::table('departments')->where('id', $auditInfo['dept_id'])->first();
                    $deptNameCard = $dept->name ?? 'Departemen #' . $auditInfo['dept_id'];
                    
                    $isCurrent = $auditInfo['id'] == ($currentAuditId ?? null);
                    $audit = \DB::table('audits')->where('id', $auditInfo['id'])->first();
                    $auditStatus = $audit->status ?? 'IN_PROGRESS';
                    $isCompleted = in_array($auditStatus, ['COMPLETE', 'COMPLETED']);
                    
                    // Hitung progress total per departemen
                    $totalClauses = count($clauses);
                    $completedClauses = 0;
                    $totalProgress = 0;
                    
                    foreach($clauses as $clauseNum) {
                        $progress = \DB::table('answers')
                            ->join('items', 'answers.item_id', '=', 'items.id')
                            ->join('clauses', 'items.clause_id', '=', 'clauses.id')
                            ->where('answers.audit_id', $auditInfo['id'])
                            ->where('answers.department_id', $auditInfo['dept_id'])
                            ->whereIn('clauses.clause_code', function($query) use ($clauseNum) {
                                $subCodes = [];
                                switch($clauseNum) {
                                    case 4: $subCodes = ['4.1', '4.2', '4.3', '4.4']; break;
                                    case 5: $subCodes = ['5.1', '5.2', '5.3']; break;
                                    case 6: $subCodes = ['6.1.1', '6.1.2', '6.1.3', '6.1.4', '6.2.1', '6.2.2']; break;
                                    case 7: $subCodes = ['7.1', '7.2', '7.3', '7.4', '7.5.1', '7.5.2', '7.5.3']; break;
                                    case 8: $subCodes = ['8.1', '8.2']; break;
                                    case 9: $subCodes = ['9.1.1', '9.1.2', '9.2.1 & 9.2.2', '9.3']; break;
                                    case 10: $subCodes = ['10.1', '10.2', '10.3']; break;
                                }
                                $query->whereIn('clause_code', $subCodes);
                            })
                            ->count();
                        
                        $totalItems = \DB::table('items')
                            ->join('clauses', 'items.clause_id', '=', 'clauses.id')
                            ->whereIn('clauses.clause_code', function($query) use ($clauseNum) {
                                $subCodes = [];
                                switch($clauseNum) {
                                    case 4: $subCodes = ['4.1', '4.2', '4.3', '4.4']; break;
                                    case 5: $subCodes = ['5.1', '5.2', '5.3']; break;
                                    case 6: $subCodes = ['6.1.1', '6.1.2', '6.1.3', '6.1.4', '6.2.1', '6.2.2']; break;
                                    case 7: $subCodes = ['7.1', '7.2', '7.3', '7.4', '7.5.1', '7.5.2', '7.5.3']; break;
                                    case 8: $subCodes = ['8.1', '8.2']; break;
                                    case 9: $subCodes = ['9.1.1', '9.1.2', '9.2.1 & 9.2.2', '9.3']; break;
                                    case 10: $subCodes = ['10.1', '10.2', '10.3']; break;
                                }
                                $query->whereIn('clause_code', $subCodes);
                            })
                            ->count();
                        
                        $clausePercentage = ($totalItems > 0) ? round(($progress / $totalItems) * 100) : 0;
                        $totalProgress += $clausePercentage;
                        
                        if ($clausePercentage >= 100) {
                            $completedClauses++;
                        }
                    }
                    
                    $overallPercentage = ($totalClauses > 0) ? round($totalProgress / $totalClauses) : 0;
                    $overallStatus = $overallPercentage == 100 ? 'completed' : ($overallPercentage > 0 ? 'active' : 'pending');
                    
                    $statusColors = [
                        'completed' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200', 'icon' => 'fa-check-circle', 'label' => 'SELESAI'],
                        'active' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'icon' => 'fa-spinner', 'label' => 'SEDANG DIKERJAKAN'],
                        'pending' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'border' => 'border-gray-200', 'icon' => 'fa-clock', 'label' => 'BELUM DIMULAI']
                    ];
                    
                    $status = $statusColors[$overallStatus];
                @endphp

                <div class="bg-white rounded-xl border-2 {{ $status['border'] }} overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <!-- Header Card -->
                    <div class="p-5 border-b {{ $status['border'] }} bg-gradient-to-r from-white to-blue-50">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-building text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-800">{{ $deptNameCard }}</h4>
                                        <p class="text-sm {{ $status['text'] }} flex items-center gap-1">
                                            <i class="fas {{ $status['icon'] }}"></i>
                                            {{ $status['label'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @if($isCurrent)
                                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1.5 rounded-full">
                                    AKTIF
                                </span>
                            @endif
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-600">Progress Audit</span>
                                <span class="text-sm font-bold {{ $status['text'] }}">{{ $overallPercentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                <div class="h-full {{ $overallPercentage == 100 ? 'bg-green-500' : 'bg-blue-500' }} transition-all duration-500" 
                                     style="width: {{ $overallPercentage }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $completedClauses }} dari {{ $totalClauses }} klausul selesai
                            </p>
                        </div>
                    </div>

                    <!-- Klausul Grid -->
                    <div class="p-4">
                        <div class="grid grid-cols-3 md:grid-cols-7 gap-2">
                            @foreach($clauses as $clauseNum)
                                @php
                                    $progress = \DB::table('answers')
                                        ->join('items', 'answers.item_id', '=', 'items.id')
                                        ->join('clauses', 'items.clause_id', '=', 'clauses.id')
                                        ->where('answers.audit_id', $auditInfo['id'])
                                        ->where('answers.department_id', $auditInfo['dept_id'])
                                        ->whereIn('clauses.clause_code', function($query) use ($clauseNum) {
                                            $subCodes = [];
                                            switch($clauseNum) {
                                                case 4: $subCodes = ['4.1', '4.2', '4.3', '4.4']; break;
                                                case 5: $subCodes = ['5.1', '5.2', '5.3']; break;
                                                case 6: $subCodes = ['6.1.1', '6.1.2', '6.1.3', '6.1.4', '6.2.1', '6.2.2']; break;
                                                case 7: $subCodes = ['7.1', '7.2', '7.3', '7.4', '7.5.1', '7.5.2', '7.5.3']; break;
                                                case 8: $subCodes = ['8.1', '8.2']; break;
                                                case 9: $subCodes = ['9.1.1', '9.1.2', '9.2.1 & 9.2.2', '9.3']; break;
                                                case 10: $subCodes = ['10.1', '10.2', '10.3']; break;
                                            }
                                            $query->whereIn('clause_code', $subCodes);
                                        })
                                        ->count();
                                    
                                    $totalItems = \DB::table('items')
                                        ->join('clauses', 'items.clause_id', '=', 'clauses.id')
                                        ->whereIn('clauses.clause_code', function($query) use ($clauseNum) {
                                            $subCodes = [];
                                            switch($clauseNum) {
                                                case 4: $subCodes = ['4.1', '4.2', '4.3', '4.4']; break;
                                                case 5: $subCodes = ['5.1', '5.2', '5.3']; break;
                                                case 6: $subCodes = ['6.1.1', '6.1.2', '6.1.3', '6.1.4', '6.2.1', '6.2.2']; break;
                                                case 7: $subCodes = ['7.1', '7.2', '7.3', '7.4', '7.5.1', '7.5.2', '7.5.3']; break;
                                                case 8: $subCodes = ['8.1', '8.2']; break;
                                                case 9: $subCodes = ['9.1.1', '9.1.2', '9.2.1 & 9.2.2', '9.3']; break;
                                                case 10: $subCodes = ['10.1', '10.2', '10.3']; break;
                                            }
                                            $query->whereIn('clause_code', $subCodes);
                                        })
                                        ->count();
                                    
                                    $clausePercentage = ($totalItems > 0) ? round(($progress / $totalItems) * 100) : 0;
                                    $isClauseCompleted = $clausePercentage >= 100;
                                    $badgeClass = $isClauseCompleted 
                                        ? 'bg-green-100 text-green-700' 
                                        : ($progress > 0 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500');
                                @endphp
                                
                                <a href="{{ route('audit.show', ['id' => $auditInfo['id'], 'clause' => $clauseNum]) }}"
                                   class="block p-2 rounded-lg text-center hover:bg-gray-50 transition-colors {{ $isClauseCompleted ? 'bg-green-50' : ($progress > 0 ? 'bg-blue-50' : 'bg-gray-50') }}">
                                    <div class="text-sm font-bold mb-0.5 {{ $isClauseCompleted ? 'text-green-600' : 'text-blue-600' }}">
                                        @if($isClauseCompleted) ✅ @else {{ $clauseNum }} @endif
                                    </div>
                                    <div class="text-[10px] {{ $badgeClass }} px-1.5 py-0.5 rounded">
                                        {{ $progress }}/{{ $totalItems }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Footer Card -->
                    <div class="p-4 bg-gray-50 border-t {{ $status['border'] }}">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Audit ID: #{{ $auditInfo['id'] }}
                                </p>
                            </div>
                            <a href="{{ route('audit.show', ['id' => $auditInfo['id'], 'clause' => 4]) }}"
                               class="text-sm font-semibold {{ $status['text'] }} hover:underline flex items-center gap-1">
                                Lihat Detail
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

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
    /* === DEPARTMENT PROGRESS CARD === */
    .progress-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .progress-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    }

    .card-header {
        padding: 1.5rem;
        border-bottom: 2px solid #e2e8f0;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .card-subtitle {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0.25rem 0 0 0;
    }

    .progress-bar-container {
        height: 8px;
        background: #e2e8f0;
        border-radius: 4px;
        overflow: hidden;
        margin: 1rem 0;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
        transition: width 0.5s ease;
        border-radius: 4px;
    }

    .progress-bar.completed {
        background: linear-gradient(90deg, #10b981 0%, #059669 100%);
    }

    .clause-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
        padding: 1rem;
    }

    .clause-item {
        padding: 0.75rem;
        text-align: center;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .clause-item:hover {
        background: #f1f5f9;
        transform: scale(1.05);
    }

    .clause-number {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }

    .clause-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        display: inline-block;
    }

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
</script>
@endpush
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
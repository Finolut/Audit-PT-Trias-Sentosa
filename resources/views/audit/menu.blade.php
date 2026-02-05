<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mulai Audit Internal</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1a365d',
                        secondary: '#2563eb'
                    }
                }
            }
        }
    </script>
    <style>
        /* Header */
        .header-section {
            background: linear-gradient(135deg, #1a365d 0%, #2563eb 100%);
            color: white;
            padding: 2.5rem 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .header-title {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        .header-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Section title */
        .section-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a365d;
            margin: 1.5rem 0 1rem;
            padding-bottom: 0.25rem;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Instruction item */
        .instruction-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .instruction-number {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            background: #1a365d;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .instruction-content h3 {
            font-weight: 600;
            color: #1a365d;
            margin: 0 0 0.25rem 0;
        }

        .instruction-text {
            color: #475569;
            font-size: 0.95rem;
            line-height: 1.5;
            margin: 0;
        }

        /* Token section */
        .token-container {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
            padding: 0.75rem 0;
            border-top: 1px solid #e2e8f0;
        }

        .token-label {
            font-weight: 600;
            color: #1a365d;
            font-size: 0.95rem;
            min-width: 120px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .token-value {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-family: 'Courier New', monospace;
            font-size: 0.95rem;
            color: #0f172a;
            word-break: break-all;
        }

        .token-btn {
            background: #1a365d;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            cursor: pointer;
        }

        /* Department progress */
        .dept-list {
            margin-top: 1.5rem;
        }

        .dept-header {
            font-weight: 600;
            color: #1a365d;
            font-size: 1.1rem;
            margin: 1rem 0 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .clause-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 0.75rem;
            margin-top: 0.5rem;
        }

        .clause-card {
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 6px;
            text-align: center;
            transition: all 0.2s;
        }

        .clause-card:hover {
            background: #eef6ff;
        }

        .clause-number {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a365d;
            margin-bottom: 0.25rem;
        }

        .clause-status {
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 0.25rem;
        }

        .clause-progress {
            display: inline-block;
            padding: 0.15rem 0.5rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .progress-completed {
            background: #dcfce7;
            color: #15803d;
        }

        .progress-in-progress {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .progress-not-started {
            background: #e2e8f0;
            color: #64748b;
        }

        /* Finish banner */
        .finish-banner {
            background: linear-gradient(135deg, #f0fdf4 0%, #e6fffa 100%);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid #10b981;
            margin-top: 2rem;
        }

        .finish-message {
            font-size: 1.4rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0.5rem 0;
        }

        .finish-subtitle {
            font-size: 1.1rem;
            color: #065f46;
            margin: 0.5rem 0 1.5rem;
            font-weight: 600;
        }

        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #0ca678;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
        }

        .btn-outline {
            background: transparent;
            color: #2563eb;
            border: 1px solid #2563eb;
        }

        .btn-outline:hover {
            background: #2563eb;
            color: white;
        }

        .btn-next-dept {
            background: #0c2d5a;
            color: white;
        }

        .btn-next-dept:hover {
            background: #0a254d;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(12, 45, 90, 0.3);
        }

        /* Start Audit Button - SOLID COLOR (NO GRADIENT) */
        .start-audit-container {
            background: #0c2d5a;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            margin: 2rem 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-start-audit {
            background: white;
            color: #0c2d5a;
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 8px;
            border: 2px solid white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-start-audit:hover {
            background: #0a2547;
            color: white;
            border-color: #0a2547;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(12, 45, 90, 0.4);
        }

        .btn-start-audit:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
        }

        .start-audit-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }

        .start-audit-subtitle {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        .hero-section {
            background:
                linear-gradient(
                    rgba(12, 45, 90, 0.88),
                    rgba(12, 45, 90, 0.88)
                ),
                url('https://media.licdn.com/dms/image/v2/D563DAQEpYdKv0Os29A/image-scale_191_1128/image-scale_191_1128/0/1690510724603/pt_trias_sentosa_tbk_cover?e=2147483647&v=beta&t=dOGhpl6HrbRAla_mDVT5azyevrvu-cOGFxPcrlizZ6M');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 260px;
            display: flex;
            align-items: center;
        }
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--slate-dark);
            margin: 2.5rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .section-description {
            color: var(--slate);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .dept-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">

<!-- Mini Hero Section -->
<section class="hero-section text-white">
    <div class="max-w-7xl mx-auto px-4 lg:px-6">
        <h1 class="text-3xl md:text-4xl font-bold mb-3">
            MULAI AUDIT INTERNAL
        </h1>
        <p class="text-base md:text-lg opacity-90 max-w-3xl">
            Halaman ini digunakan untuk mengisi audit internal berdasarkan kondisi aktual departemen.
        </p>
    </div>
</section>

    <div class="max-w-7xl mx-auto px-4 pb-8">
    

<!-- Instruksi & Token -->
<div class="mt-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Instruksi -->
        <div>
            <h2 class="section-title">Instruksi Pengisian</h2>

            <div class="instruction-item mt-4">
                <div class="instruction-number">1</div>
                <div class="instruction-content">
                    <h3>Pilih Klausul Audit</h3>
                    <p class="instruction-text">Setiap klausul berisi pertanyaan yang wajib dijawab sesuai kondisi aktual departemen.</p>
                </div>
            </div>

            <div class="instruction-item mt-4">
                <div class="instruction-number">2</div>
                <div class="instruction-content">
                    <h3>Jawab Pertanyaan Audit</h3>
                    <p class="instruction-text">
                        Jawaban harus mencerminkan kondisi aktual departemen.<br>
                        <span class="font-medium text-blue-600">YES:</span> Klausul telah diterapkan<br>
                        <span class="font-medium text-red-600">NO:</span> Klausul tidak diterapkan (wajib isi catatan)<br>
                        <span class="font-medium text-gray-600">N/A:</span> Tidak relevan dengan departemen
                    </p>
                </div>
            </div>

            <div class="instruction-item mt-4">
                <div class="instruction-number">3</div>
                <div class="instruction-content">
                    <h3>Penyimpanan Otomatis</h3>
                    <p class="instruction-text">Jawaban disimpan otomatis. Pastikan seluruh pertanyaan dalam satu klausul telah terisi sebelum berpindah.</p>
                </div>
            </div>
        </div>

        <!-- Token -->
        <div>
            <h2 class="section-title">Token Audit (WAJIB DISIMPAN)</h2>

            <!-- TEXT TAMBAHAN (INFORMATIF, TANPA UBAH DESAIN) -->
            <p class="text-xs text-gray-600 mt-2">
                <strong>Penting:</strong> Simpan token ini untuk melanjutkan audit di kemudian hari.
                Dengan token ini, progress audit dapat dipulihkan.
                Jika terjadi kendala, hubungi Admin
                <strong>Brahmanto Anggoro Laksono - SSSE</strong>.
            </p>

            <p class="text-xs text-gray-500 mt-2">
                Tips: Simpan token di catatan kerja, dokumen internal, atau screenshot.
            </p>

   <!-- GANTI BAGIAN TOKEN JADI INI (SALIN & TEMPATKAN LANGSUNG) -->
<div class="flex items-center gap-2 mt-3">
    <div id="audit-token" 
         class="token-value bg-blue-50 border border-blue-200 rounded px-3 py-2 font-mono text-sm select-all"
         onclick="navigator.clipboard.writeText(this.textContent.trim()).then(()=>{alert('✓ Token disalin!\n\n' + this.textContent.trim())}).catch(()=>{alert('Salin manual:\n' + this.textContent.trim())})">
        {{ $resumeToken ?? 'TOKEN_TIDAK_TERSEDIA' }}
    </div>
    <button type="button"
            class="token-btn bg-blue-700 hover:bg-blue-800 transition-colors p-2 rounded"
            onclick="navigator.clipboard.writeText(document.getElementById('audit-token').textContent.trim()).then(()=>{this.innerHTML='<i class=\'fas fa-check text-green-400\'></i>';setTimeout(()=>{this.innerHTML='<i class=\'fas fa-copy\'></i>'},2000)}).catch(()=>alert('Gagal salin. Salin manual dari kotak token'))">
        <i class="fas fa-copy"></i>
    </button>
</div>
        </div>
    </div>
</div>

        <!-- Start Audit Button Section - SOLID COLOR -->
        <div class="start-audit-container">
            <h2 class="start-audit-title">
                <i class="fas fa-play-circle mr-2"></i> Mulai Audit Sekarang
            </h2>
            <p class="start-audit-subtitle">
                Klik tombol di bawah untuk memulai audit dari Klausul 4
            </p>
            <a href="{{ route('audit.show', ['id' => $auditId, 'clause' => '4']) }}" 
               class="btn-start-audit">
                <i class="fas fa-arrow-right"></i>
                Mulai Audit (Klausul 4)
            </a>
        </div>

        <!-- Progress Per Departemen -->
        <div class="mt-8">
            <h2 class="section-title">Progress Per Departemen</h2>
            
            @if($relatedAudits)
                <div class="dept-list">
                    @foreach($relatedAudits as $dept)
                    <div>
                        <div class="dept-header">
                            <i class="fas fa-building text-blue-600"></i>
                            {{ $dept['dept_name'] }}
                        </div>
                        <div class="clause-grid">
                            @php $clauses = [4,5,6,7,8,9,10]; @endphp
                            @foreach($clauses as $clauseNum)
                                @php
                                    $p = $dept['clauses'][$clauseNum] ?? ['percentage' => 0, 'count' => 0, 'total' => 0];
                                    $isCompleted = $p['percentage'] >= 100;
                                    $badgeClass = $isCompleted 
                                        ? 'progress-completed' 
                                        : ($p['count'] > 0 ? 'progress-in-progress' : 'progress-not-started');
                                @endphp
                                <a href="{{ route('audit.show', ['id' => $dept['id'], 'clause' => $clauseNum]) }}"
                                   class="clause-card block">
                                    <div class="clause-number">{{ $clauseNum }}</div>
                                    <div class="clause-status">Klausul {{ $clauseNum }}</div>
                                    <div class="clause-progress {{ $badgeClass }}">
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

        <!-- Finish Banner - DIPERBAIKI -->
        @if(isset($allFinished) && $allFinished)
        <div class="finish-banner">
            <div class="finish-message">Audit {{ $deptName }} Selesai!</div>
            <p class="finish-subtitle">
                <i class="fas fa-check-circle mr-2"></i> Selamat! Semua klausul telah diisi dengan lengkap.
            </p>
            
            @php
                // Cari departemen berikutnya yang belum selesai
                $nextDept = null;
                if(isset($relatedAudits) && is_array($relatedAudits)) {
                    foreach($relatedAudits as $dept) {
                        // Cek apakah departemen ini bukan yang sedang selesai
                        if($dept['dept_name'] !== $deptName) {
                            // Cek apakah ada klausul yang belum selesai (percentage < 100)
                            $hasIncomplete = false;
                            foreach([4,5,6,7,8,9,10] as $clauseNum) {
                                $p = $dept['clauses'][$clauseNum] ?? ['percentage' => 0];
                                if($p['percentage'] < 100) {
                                    $hasIncomplete = true;
                                    break;
                                }
                            }
                            
                            // Jika ada klausul yang belum selesai, ini adalah next dept
                            if($hasIncomplete) {
                                $nextDept = $dept;
                                break;
                            }
                        }
                    }
                }
            @endphp

            @if($nextDept)
                <!-- Tombol Lanjutkan ke Departemen Berikutnya -->
                <div class="mb-4">
                    <p class="text-gray-700 font-medium mb-2">
                        <i class="fas fa-arrow-right mr-2"></i> 
                        Lanjutkan ke departemen berikutnya:
                    </p>
                    <a href="{{ route('audit.show', ['id' => $nextDept['id'], 'clause' => '4']) }}" 
                       class="btn btn-next-dept">
                        <i class="fas fa-building mr-2"></i>
                        Lanjutkan ke {{ $nextDept['dept_name'] }}
                    </a>
                </div>
                
                <p class="text-sm text-gray-600 mb-3">
                    Atau pilih opsi lainnya di bawah ini:
                </p>
            @endif

            <div class="dept-buttons">
                <a href="{{ route('audit.finish') }}" class="btn btn-success">
                    <i class="fas fa-check-circle mr-1"></i> Selesaikan Audit
                </a>
                
            </div>
        </div>
        @endif
    </div>

    <!-- Modal Popup untuk Copy Token -->
<div id="copySuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full mx-4 animate-fade-in">
        <div class="text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-green-500 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Token Berhasil Disalin!</h3>
            <p class="text-gray-600 mb-4">
                Token audit telah disalin ke clipboard Anda.
            </p>
            <div class="bg-gray-50 rounded-lg p-3 mb-4">
                <p class="text-sm font-mono text-gray-800 break-all" id="modalTokenDisplay">
                    {{ $resumeToken ?? 'TOKEN_TIDAK_TERSEDIA' }}
                </p>
            </div>
            <p class="text-xs text-gray-500 mb-6">
                <i class="fas fa-info-circle mr-1"></i>
                Simpan token ini untuk melanjutkan audit di kemudian hari
            </p>
            <button onclick="closeCopyModal()" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-check mr-2"></i>OK, Saya Mengerti
            </button>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}
</style>

@push('scripts')
<script src="{{ asset('js/audit-script.js') }}"></script>
<script>
    // Set global variables for audit-script.js
    window.auditorName = @json($auditorName);
    window.responders  = @json($responders);

    // PERBAIKAN: Fungsi copy token yang lebih sederhana dan langsung
    document.addEventListener('DOMContentLoaded', function() {
        const copyBtn = document.getElementById('copy-token-btn');
        const tokenEl = document.getElementById('audit-token');
        
        // Debug: Cek apakah elemen ditemukan
        console.log('Copy Button:', copyBtn);
        console.log('Token Element:', tokenEl);
        console.log('Token Text:', tokenEl ? tokenEl.textContent.trim() : 'N/A');
        
        if (!copyBtn || !tokenEl) {
            console.error('Elemen tidak ditemukan!');
            return;
        }
        
        const tokenText = tokenEl.textContent.trim();
        
        // Hanya disable jika benar-benar tidak ada token
        if (!tokenText || tokenText === 'TOKEN_TIDAK_TERSEDIA') {
            copyBtn.disabled = true;
            copyBtn.title = "Token tidak tersedia";
            console.log('Token tidak tersedia, tombol disabled');
            return;
        }
        
        // Hapus disabled jika token ada
        copyBtn.disabled = false;
        copyBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        copyBtn.classList.add('cursor-pointer', 'hover:bg-blue-700');
        
        console.log('Token tersedia, tombol aktif');
        
        // Event listener yang lebih sederhana
        copyBtn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Tombol copy diklik!');
            
            copyToken(tokenText, copyBtn);
        };
        
        // Juga tambahkan event listener untuk touch devices
        copyBtn.addEventListener('touchstart', function(e) {
            e.preventDefault();
            copyToken(tokenText, copyBtn);
        });
    });

    // Fungsi copy yang lebih reliable
    function copyToken(text, btn) {
        // Metode 1: Clipboard API (modern browsers)
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                showCopySuccess(btn);
            }).catch(err => {
                console.warn('Clipboard API gagal:', err);
                fallbackCopy(text, btn);
            });
        } 
        // Metode 2: Fallback untuk browser lama
        else {
            fallbackCopy(text, btn);
        }
    }

    // Fallback method yang lebih aman
    function fallbackCopy(text, btn) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        
        // Style untuk menyembunyikan textarea
        textarea.style.position = 'fixed';
        textarea.style.left = '-9999px';
        textarea.style.top = '-9999px';
        textarea.style.opacity = '0';
        textarea.style.width = '1px';
        textarea.style.height = '1px';
        
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();
        
        try {
            const successful = document.execCommand('copy');
            document.body.removeChild(textarea);
            
            if (successful) {
                showCopySuccess(btn);
            } else {
                showCopyError(btn);
            }
        } catch (err) {
            document.body.removeChild(textarea);
            showCopyError(btn);
        }
    }

    // Tampilkan feedback sukses
    function showCopySuccess(btn) {
        const originalHTML = btn.innerHTML;
        const originalBg = btn.style.backgroundColor;
        
        // Update tombol
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.style.backgroundColor = '#10b981';
        btn.style.color = 'white';
        
        // Tooltip sukses
        const tooltip = document.createElement('div');
        tooltip.textContent = '✓ Token berhasil disalin!';
        tooltip.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #10b981;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
            font-weight: 600;
        `;
        
        document.body.appendChild(tooltip);
        
        // Reset tombol setelah 2 detik
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-copy"></i>';
            btn.style.backgroundColor = originalBg;
            btn.style.color = '';
        }, 2000);
        
        // Hapus tooltip setelah 3 detik
        setTimeout(() => {
            tooltip.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => {
                if (tooltip.parentNode) {
                    tooltip.parentNode.removeChild(tooltip);
                }
            }, 300);
        }, 3000);
    }

    // Tampilkan feedback error
    function showCopyError(btn) {
        const originalHTML = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-times"></i>';
        btn.style.backgroundColor = '#ef4444';
        btn.style.color = 'white';
        
        alert('⚠️ Gagal menyalin token!\n\nSilakan salin manual:\n' + 
              document.getElementById('audit-token').textContent.trim());
        
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-copy"></i>';
            btn.style.backgroundColor = '';
            btn.style.color = '';
        }, 2000);
    }
</script>

<style>
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    /* Pastikan tombol bisa diklik */
    #copy-token-btn {
        cursor: pointer !important;
        pointer-events: auto !important;
        user-select: none;
        transition: all 0.2s ease;
    }
    
    #copy-token-btn:hover:not(:disabled) {
        background-color: #1e40af !important;
        transform: scale(1.05);
    }
    
    #copy-token-btn:active:not(:disabled) {
        transform: scale(0.95);
    }
    
    #copy-token-btn:disabled {
        cursor: not-allowed !important;
        opacity: 0.5 !important;
    }
</style>
@endpush
</body>
</html>
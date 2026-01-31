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

        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-outline {
            background: transparent;
            color: #2563eb;
            border: 1px solid #2563eb;
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

            <div class="flex items-center gap-1 mt-3">
                <div id="audit-token" class="token-value">
                    {{ $resumeToken ?? 'TOKEN_TIDAK_TERSEDIA' }}
                </div>
                <button id="copy-token-btn"
                        class="token-btn {{ !$resumeToken ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ !$resumeToken ? 'disabled' : '' }}
                        aria-label="Salin Kode Audit">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>
    </div>
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

        <!-- Finish Banner -->
        @if(isset($allFinished) && $allFinished)
        <div class="finish-banner">
            <div class="finish-message">Audit {{ $deptName }} Selesai!</div>
            <p class="text-gray-600 mb-3">Semua klausul telah diisi dengan lengkap dan siap direview.</p>
            <div class="flex justify-center gap-3">
                <a href="{{ route('audit.finish') }}" class="btn btn-success">
                    <i class="fas fa-check-circle mr-1"></i> Selesaikan Audit
                </a>
                @if(isset($relatedAudits) && count($relatedAudits) > 1)
                    <a href="{{ route('audit.menu', ['id' => $relatedAudits[0]['id'] ?? $auditId]) }}" 
                       class="btn btn-outline">
                        <i class="fas fa-arrow-left mr-1"></i> Audit Lainnya
                    </a>
                @endif
            </div>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyBtn = document.getElementById('copy-token-btn');
            const tokenElement = document.getElementById('audit-token');
            
            if (copyBtn && tokenElement && !copyBtn.disabled) {
                copyBtn.addEventListener('click', async () => {
                    try {
                        const tokenValue = tokenElement.textContent.trim();
                        await navigator.clipboard.writeText(tokenValue);
                        
                        copyBtn.innerHTML = '<i class="fas fa-check"></i>';
                        copyBtn.style.backgroundColor = '#10b981';
                        
                        setTimeout(() => {
                            copyBtn.innerHTML = '<i class="fas fa-copy"></i>';
                            copyBtn.style.backgroundColor = '#1a365d';
                        }, 2000);
                    } catch (err) {
                        console.error('Gagal menyalin token:', err);
                        alert('Gagal menyalin. Silakan salin manual.');
                    }
                });
            }
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mulai Audit Internal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1a365d',
                        secondary: '#2563eb',
                        accent: '#10b981'
                    }
                }
            }
        }
    </script>
    <style>
        /* Header styling */
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
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-subtitle {
            font-size: 1.1rem;
            font-weight: 400;
            max-width: 800px;
            line-height: 1.6;
            opacity: 0.9;
        }

        /* Section cards */
        .section-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a365d;
            margin-bottom: 1.2rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-description {
            color: #475569;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        /* Token section */
        .token-card {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 12px;
            padding: 1.25rem;
            margin-top: 1.5rem;
        }

        .token-label {
            font-weight: 600;
            color: #0f172a;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
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
            word-break: break-all;
        }

        /* Department cards - TANPA BORDER */
        .department-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }

        .department-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
        }

        .department-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .department-title {
            font-weight: 700;
            font-size: 1.25rem;
            color: #1a365d;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .clause-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
        }

        .clause-card {
            padding: 1rem;
            text-align: center;
            transition: all 0.3s;
            background: #f8fafc;
            border-radius: 8px;
        }

        .clause-card:hover {
            background: #eef6ff;
            transform: scale(1.02);
        }

        .clause-number {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 0.5rem;
        }

        .clause-status {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.25rem;
        }

        .clause-progress {
            display: inline-block;
            padding: 0.25rem 0.75rem;
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
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            border: 2px solid #10b981;
            margin-top: 1.5rem;
            animation: fadeInUp 0.7s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .finish-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #0b981;
        }

        .finish-message {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .finish-subtext {
            color: #475569;
            font-size: 1.05rem;
            max-width: 600px;
            margin: 0 auto 1.5rem;
            line-height: 1.7;
        }

        .banner-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-outline {
            background: transparent;
            color: #2563eb;
            border: 1px solid #2563eb;
        }

        .btn-outline:hover {
            background: #dbeafe;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #0d9488;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .header-title {
                font-size: 1.8rem;
            }
            
            .header-subtitle {
                font-size: 1rem;
            }
            
            .section-title {
                font-size: 1.2rem;
            }
            
            .clause-grid {
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            }
        }

        /* Instruksi vertikal dengan token di samping */
        .instructions-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .instruction-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .instruction-number {
            flex-shrink: 0;
            width: 28px;
            height: 28px;
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

        .instruction-content {
            flex: 1;
        }

        .instruction-title {
            font-weight: 700;
            color: #1a365d;
            margin-bottom: 0.5rem;
        }

        .instruction-text {
            color: #475569;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Token di samping instruksi */
        .token-side {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 12px;
            padding: 1.25rem;
            margin-top: 1.5rem;
            flex-shrink: 0;
            width: 100%;
            max-width: 320px;
        }

        @media (min-width: 768px) {
            .instructions-container {
                flex-direction: row;
                align-items: flex-start;
            }
            
            .token-side {
                margin-top: 0;
                margin-left: 1.5rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">

    <!-- Header Section -->
    <header class="header-section">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="header-title">MULAI AUDIT INTERNAL</h1>
            <p class="header-subtitle">Halaman ini digunakan untuk mengisi audit internal berdasarkan kondisi aktual departemen.</p>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <!-- Instructions Section with Token on the side -->
        <div class="section-card">
            <h2 class="section-title">Instruksi Pengisian</h2>
            <p class="section-description">Setiap klausul berisi pertanyaan yang wajib dijawab sesuai kondisi aktual departemen.</p>
            
            <div class="instructions-container">
                <div class="flex-1">
                    <div class="instruction-item">
                        <div class="instruction-number">1</div>
                        <div class="instruction-content">
                            <h3 class="instruction-title">Pilih Klausul Audit</h3>
                            <p class="instruction-text">Setiap klausul berisi pertanyaan yang wajib dijawab sesuai kondisi aktual departemen.</p>
                        </div>
                    </div>
                    
                    <div class="instruction-item">
                        <div class="instruction-number">2</div>
                        <div class="instruction-content">
                            <h3 class="instruction-title">Jawab Pertanyaan Audit</h3>
                            <p class="instruction-text">Jawaban harus mencerminkan kondisi aktual departemen.<br>
                                <span class="font-medium text-blue-600">YES:</span> Klausul telah diterapkan<br>
                                <span class="font-medium text-red-600">NO:</span> Klausul tidak diterapkan (wajib isi catatan)<br>
                                <span class="font-medium text-gray-600">N/A:</span> Tidak relevan dengan departemen</p>
                        </div>
                    </div>
                    
                    <div class="instruction-item">
                        <div class="instruction-number">3</div>
                        <div class="instruction-content">
                            <h3 class="instruction-title">Penyimpanan Otomatis</h3>
                            <p class="instruction-text">Jawaban disimpan otomatis. Pastikan seluruh pertanyaan dalam satu klausul telah terisi sebelum berpindah.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Token section on the side -->
                <div class="token-side">
                    <div class="flex items-start mb-3">
                        <i class="fas fa-key text-xl mr-3" style="color: #1a365d;"></i>
                        <h3 class="font-bold text-lg text-gray-800">Token Audit (WAJIB DISIMPAN)</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">
                        <span class="font-semibold text-gray-800">Penting:</span> Simpan kode ini untuk melanjutkan audit di kemudian hari. 
                        Dengan kode ini, progress audit <span class="font-medium" style="color: #1a365d;">dapat dipulihkan</span>.
                        Jika ada kendala dengan token audit bisa menghubungi Admin 
                        <span class="font-semibold text-gray-700">Brahmanto Anggoro Laksono - SSSE</span>
                    </p>
                    <div class="flex items-center gap-2 mb-2">
                        <div id="audit-token" class="flex-1 bg-gray-50 border border-gray-300 text-gray-800 font-mono font-medium text-sm px-4 py-3 rounded-lg break-all min-w-[250px]">
                            {{ $resumeToken ?? 'TOKEN_TIDAK_TERSEDIA' }}
                        </div>
                        <button id="copy-token-btn" 
                                class="text-white font-medium px-3 py-3 rounded-lg flex items-center gap-2 transition-opacity duration-200 whitespace-nowrap"
                                style="background-color: #1a365d;"
                                aria-label="Salin Kode Audit">
                            <i class="fas fa-copy"></i>
                            <span class="hidden sm:inline">Salin</span>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 text-right">
                        Klik tombol Salin untuk menyalin ke clipboard
                    </p>
                    <p class="text-xs text-gray-500 bg-gray-50 border border-gray-200 rounded px-3 py-2 mt-3">
                        <span class="font-medium">Tips:</span> Simpan token di tempat aman (catatan kerja, dokumen internal, atau screenshot).
                    </p>
                </div>
            </div>
        </div>

        <!-- Department Progress Section -->
        <div class="section-card">
            <h2 class="section-title">Progress Per Departemen</h2>
            <p class="section-description">Pilih departemen dan klausul audit yang ingin Anda kerjakan</p>
            
            <div class="space-y-4">
                <!-- Dynamically generated departments based on audit data -->
                @foreach($relatedAudits as $dept)
                <div class="department-card">
                    <div class="department-header">
                        <h3 class="department-title">
                            <i class="fas fa-building text-blue-600"></i>
                            {{ $dept['dept_name'] }}
                        </h3>
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
                            <div class="clause-card">
                                <div class="clause-number">{{ $clauseNum }}</div>
                                <div class="clause-status">Klausul {{ $clauseNum }}</div>
                                <div class="clause-progress {{ $badgeClass }}">
                                    {{ $p['count'] }}/{{ $p['total'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Finish Banner -->
        @if(isset($allFinished) && $allFinished)
        <div class="finish-banner">
            <div class="finish-icon">🎉</div>
            <h2 class="finish-message">Audit Selesai!</h2>
            <p class="finish-subtext">Semua klausul telah diisi dengan lengkap dan siap direview. Silakan selesaikan proses audit untuk menghasilkan laporan final.</p>
            
            <div class="banner-actions">
                <button class="btn btn-success">
                    <i class="fas fa-check-circle"></i> Selesaikan Audit
                </button>
                <button class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Audit Lainnya
                </button>
            </div>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyBtn = document.getElementById('copy-token-btn');
            const tokenElement = document.getElementById('audit-token');
            
            if (copyBtn && tokenElement) {
                copyBtn.addEventListener('click', async () => {
                    try {
                        const tokenValue = tokenElement.textContent.trim();
                        await navigator.clipboard.writeText(tokenValue);
                        
                        copyBtn.innerHTML = '<i class="fas fa-check mr-1"></i><span>Tersalin!</span>';
                        copyBtn.style.backgroundColor = '#10b981';
                        
                        setTimeout(() => {
                            copyBtn.innerHTML = '<i class="fas fa-copy"></i><span class="hidden sm:inline">Salin</span>';
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
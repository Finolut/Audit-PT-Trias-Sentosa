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
            border: 1px solid #e2e8f0;
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

        /* Department cards */
        .department-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }

        .department-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
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
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1rem;
        }

        .clause-card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            transition: all 0.3s;
        }

        .clause-card:hover {
            border-color: #2563eb;
            background: #f0f9ff;
        }

        .clause-number {
            font-size: 1.5rem;
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
            color: #10b981;
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
    </style>
</head>
<body class="bg-gray-50 font-sans">

    <!-- Header Section -->
    <header class="header-section">
        <div class="max-w-7xl mx-auto">
            <h1 class="header-title">MULAI AUDIT INTERNAL</h1>
            <p class="header-subtitle">Halaman ini digunakan untuk mengisi audit internal berdasarkan kondisi aktual departemen.</p>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <!-- Instructions Section -->
        <div class="section-card">
            <h2 class="section-title">Instruksi Pengisian</h2>
            <p class="section-description">Setiap klausul berisi pertanyaan yang wajib dijawab sesuai kondisi aktual departemen.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 text-white rounded-full flex items-center justify-center text-sm mr-3 mt-1" 
                          style="background-color: #1a365d;">1</span>
                    <div>
                        <h4 class="font-bold text-gray-800">Pilih Klausul Audit</h4>
                        <p class="text-sm text-gray-600 mt-1">Setiap klausul berisi pertanyaan yang wajib dijawab sesuai kondisi aktual departemen.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 text-white rounded-full flex items-center justify-center text-sm mr-3 mt-1" 
                          style="background-color: #1a365d;">2</span>
                    <div>
                        <h4 class="font-bold text-gray-800">Jawab Pertanyaan Audit</h4>
                        <p class="text-sm text-gray-600 mt-1">Jawaban harus mencerminkan kondisi aktual departemen.<br>
                            YES: Klausul telah diterapkan<br>
                            NO: Klausul tidak diterapkan (wajib isi catatan)<br>
                            N/A: Tidak relevan dengan departemen</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 text-white rounded-full flex items-center justify-center text-sm mr-3 mt-1" 
                          style="background-color: #1a365d;">3</span>
                    <div>
                        <h4 class="font-bold text-gray-800">Penyimpanan Otomatis</h4>
                        <p class="text-sm text-gray-600 mt-1">Jawaban disimpan otomatis. Pastikan seluruh pertanyaan dalam satu klausul telah terisi sebelum berpindah.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Token Section -->
        <div class="section-card">
            <h2 class="section-title">Token Audit (WAJIB DISIMPAN)</h2>
            <p class="section-description">Simpan kode ini untuk melanjutkan audit di kemudian hari. Dengan kode ini, progress audit dapat dipulihkan.</p>
            
            <div class="token-card">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-start">
                            <div class="shrink-0 mt-0.5">
                                <i class="fas fa-key text-xl" style="color: #1a365d;"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-bold text-gray-800 mb-1">Penting:</h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    Simpan kode ini untuk melanjutkan audit di kemudian hari. 
                                    Dengan kode ini, progress audit <span class="font-medium" style="color: #1a365d;">dapat dipulihkan</span>.
                                    Jika ada kendala dengan token audit bisa menghubungi Admin 
                                    <span class="font-semibold text-gray-700">Brahmanto Anggoro Laksono - SSSE</span>
                                </p>
                                <p class="text-xs text-gray-500 bg-gray-50 border border-gray-200 rounded px-3 py-2">
                                    <span class="font-medium">Tips:</span> Simpan token di tempat aman (catatan kerja, dokumen internal, atau screenshot).
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="w-full sm:w-auto">
                        <div class="flex flex-col gap-2">
                            <div class="flex items-stretch">
                                <div id="audit-token" class="flex-1 bg-gray-50 border border-gray-300 text-gray-800 font-mono font-medium text-sm px-4 py-3 rounded-l-lg break-all min-w-[250px]">
                                    A7B9C2D3E4F5
                                </div>
                                <button id="copy-token-btn" 
                                        class="text-white font-medium px-4 py-3 rounded-r-lg flex items-center gap-2 transition-opacity duration-200 whitespace-nowrap"
                                        style="background-color: #1a365d;"
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

        <!-- Department Progress Section -->
        <div class="section-card">
            <h2 class="section-title">Progress Per Departemen</h2>
            <p class="section-description">Pilih departemen dan klausul audit yang ingin Anda kerjakan</p>
            
            <div class="space-y-4">
                <!-- Department 1 -->
                <div class="department-card">
                    <div class="department-header">
                        <h3 class="department-title">
                            <i class="fas fa-building text-blue-600"></i>
                            Produksi
                        </h3>
                    </div>
                    
                    <div class="clause-grid">
                        <div class="clause-card">
                            <div class="clause-number">4</div>
                            <div class="clause-status">Klausul 4</div>
                            <div class="clause-progress progress-in-progress">3/5</div>
                        </div>
                        <div class="clause-card">
                            <div class="clause-number">5</div>
                            <div class="clause-status">Klausul 5</div>
                            <div class="clause-progress progress-not-started">0/5</div>
                        </div>
                        <div class="clause-card">
                            <div class="clause-number">6</div>
                            <div class="clause-status">Klausul 6</div>
                            <div class="clause-progress progress-not-started">0/5</div>
                        </div>
                        <div class="clause-card">
                            <div class="clause-number">7</div>
                            <div class="clause-status">Klausul 7</div>
                            <div class="clause-progress progress-not-started">0/5</div>
                        </div>
                        <div class="clause-card">
                            <div class="clause-number">8</div>
                            <div class="clause-status">Klausul 8</div>
                            <div class="clause-progress progress-not-started">0/5</div>
                        </div>
                        <div class="clause-card">
                            <div class="clause-number">9</div>
                            <div class="clause-status">Klausul 9</div>
                            <div class="clause-progress progress-not-started">0/5</div>
                        </div>
                        <div class="clause-card">
                            <div class="clause-number">10</div>
                            <div class="clause-status">Klausul 10</div>
                            <div class="clause-progress progress-not-started">0/5</div>
                        </div>
                    </div>
                </div>
                
                <!-- Department 2 -->
                <div class="department-card">
                    <div class="department-header">
                        <h3 class="department-title">
                            <i class="fas fa-building text-blue-600"></i>
                            Quality Control
                        </h3>
                    </div>
                    
                    <div class="clause-grid">
                        <div class="clause-card">
                            <div class="clause-number">4</div>
                            <div class="clause-status">Klausul 4</div>
                            <div class="clause-progress progress-not-started">0/5</div>
                        </div>
                        <div class="clause-card">
                            <div class="clause-number">5</div>
                            <div class="clause-status">Klausul 5</div>
                            <div class="clause-progress progress-not-started">0/5</div>
                        </div>
                        <div class="clause-card">
                            <div class="clause-number">6</div>
                            <div class="clause-status">Klausul 6</div>
                            <div class="clause-progress progress-not-started">0/5</div>
                        </div>
                        <div class="clause-card">
                            <div class="clause-number">7</div>
                            <div class="clause-status">Klausul 7</div>
                            <div class="clause-progress progress-not-started">0/5</div>
                        </div>
                        <div class="clause-card">
                            <div class="clause-number">8</div>
                            <div class="clause-status">Klausul 8</div>
                            <div class="clause-progress progress-not-started">0/5</div>
                        </div>
                        <div class="clause-card">
                            <div class="clause-number">9</div>
                            <div class="clause-status">Klausul 9</div>
                            <div class="clause-progress progress-not-started">0/5</div>
                        </div>
                        <div class="clause-card">
                            <div class="clause-number">10</div>
                            <div class="clause-status">Klausul 10</div>
                            <div class="clause-progress progress-not-started">0/5</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Finish Banner -->
        <div class="finish-banner">
            <div class="finish-icon">🎉</div>
            <h2 class="finish-message">Audit Selesai!</h2>
            <p class="finish-subtext">Semua klausul telah diisi dengan lengkap dan siap direview. Silakan selesaikan proses audit untuk menghasilkan laporan final.</p>
            
            <div class="banner-actions">
                <button class="btn btn-success">
                    <i class="fas-check-circle"></i> Selesaikan Audit
                </button>
                <button class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Audit Lainnya
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyBtn = document.getElementById('copy-token-btn');
            
            if (copyBtn) {
                copyBtn.addEventListener('click', async () => {
                    try {
                        const tokenElement = document.getElementById('audit-token');
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
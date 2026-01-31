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
        /* Hanya gunakan padding & margin dasar — TIDAK ADA BORDER, SHADOW, CARD */
        .section-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a365d;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            padding-bottom: 0.25rem;
            border-bottom: 1px solid #e2e8f0; /* hanya garis tipis, bukan box */
        }

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

        /* Token — tanpa kotak, hanya teks + input */
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
        }

        .token-input {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-family: 'Courier New', monospace;
            font-size: 0.95rem;
            color: #0f172a;
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

        /* Progress per departemen — polos, tanpa card */
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

        .clause-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 0.5rem;
        }

        .clause-item {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.3rem 0.6rem;
            background: #f8fafc;
            border-radius: 6px;
            font-size: 0.85rem;
            color: #475569;
        }

        .clause-number {
            font-weight: 600;
            color: #2563eb;
        }

        .clause-status {
            color: #64748b;
        }

        /* Finish banner — polos, hanya teks + tombol */
        .finish-banner {
            margin: 2rem 0;
            text-align: center;
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

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-outline {
            background: transparent;
            color: #2563eb;
            border: 1px solid #2563eb;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">

    <!-- Header -->
    <header class="max-w-7xl mx-auto px-4 py-4">
        <h1 class="text-2xl font-bold text-gray-900">MULAI AUDIT INTERNAL</h1>
        <p class="text-gray-600 mt-1">Halaman ini digunakan untuk mengisi audit internal berdasarkan kondisi aktual departemen.</p>
    </header>

    <div class="max-w-7xl mx-auto px-4 pb-8">
        <!-- Instruksi Vertikal + Token di Samping -->
        <div class="mt-6">
            <h2 class="section-title">Instruksi Pengisian</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Instruksi (kiri) -->
                <div>
                    <div class="instruction-item">
                        <div class="instruction-number">1</div>
                        <div class="instruction-content">
                            <h3>Pilih Klausul Audit</h3>
                            <p class="instruction-text">Setiap klausul berisi pertanyaan yang wajib dijawab sesuai kondisi aktual departemen.</p>
                        </div>
                    </div>
                    
                    <div class="instruction-item">
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
                    
                    <div class="instruction-item">
                        <div class="instruction-number">3</div>
                        <div class="instruction-content">
                            <h3>Penyimpanan Otomatis</h3>
                            <p class="instruction-text">Jawaban disimpan otomatis. Pastikan seluruh pertanyaan dalam satu klausul telah terisi sebelum berpindah.</p>
                        </div>
                    </div>
                </div>

                <!-- Token (kanan) — tanpa kotak, hanya input + label -->
                <div class="token-container">
                    <div class="token-label">
                        <i class="fas fa-key mr-2 text-lg" style="color: #1a365d;"></i>
                        Token Audit (WAJIB DISIMPAN)
                    </div>
                    <div class="flex items-center gap-1">
                        <input type="text" value="A7B9C2D3E4F5" class="token-input" readonly>
                        <button id="copy-token-btn" class="token-btn">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Per Departemen — POLos, tanpa card/border -->
        <div class="mt-8">
            <h2 class="section-title">Progress Per Departemen</h2>
            
            <div class="dept-list">
                @foreach($relatedAudits as $dept)
                <div>
                    <div class="dept-header">
                        <i class="fas fa-building text-blue-600"></i>
                        {{ $dept['dept_name'] }}
                    </div>
                    <div class="clause-list">
                        @php $clauses = [4,5,6,7,8,9,10]; @endphp
                        @foreach($clauses as $clauseNum)
                            @php
                                $p = $dept['clauses'][$clauseNum] ?? ['count' => 0, 'total' => 0];
                                $status = $p['count'] == $p['total'] ? 'Selesai' : ($p['count'] > 0 ? 'Sedang' : 'Belum');
                            @endphp
                            <div class="clause-item">
                                <span class="clause-number">{{ $clauseNum }}</span>
                                <span class="clause-status">({{ $p['count'] }}/{{ $p['total'] }})</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Finish Banner — minimalis -->
        @if(isset($allFinished) && $allFinished)
        <div class="finish-banner">
            <div class="finish-message">Audit Selesai!</div>
            <p class="text-gray-600 mb-3">Semua klausul telah diisi dengan lengkap dan siap direview.</p>
            <div class="flex justify-center gap-3">
                <button class="btn btn-primary">
                    <i class="fas fa-check-circle mr-1"></i> Selesaikan Audit
                </button>
                <button class="btn btn-outline">
                    <i class="fas fa-arrow-left mr-1"></i> Audit Lainnya
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
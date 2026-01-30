<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Session – {{ $deptName }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-900: #0f172a;
            --border-radius: 16px;
            --shadow-sm: 0 4px 6px -1px rgba(0,0,0,0.05);
            --shadow-md: 0 10px 25px -5px rgba(0,0,0,0.05);
            --shadow-lg: 0 20px 25px -5px rgba(0,0,0,0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: var(--slate-900);
            line-height: 1.6;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* === HEADER === */
        .header-card {
            background: white;
            padding: 2.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--slate-200);
        }

        .header-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 6px;
            background: linear-gradient(90deg, var(--primary) 0%, #3b82f6 100%);
        }

        .header-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--slate-900);
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .header-subtitle {
            color: var(--slate-600);
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
            background: var(--slate-50);
            border-radius: 12px;
            border: 1px solid var(--slate-200);
        }

        .info-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary);
            color: white;
            font-size: 1rem;
        }

        .info-text {
            font-weight: 600;
            color: var(--slate-900);
        }

        .info-label {
            font-size: 0.85rem;
            color: var(--slate-600);
        }

        /* === PROGRESS SECTION === */
        .progress-section {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            padding: 1.5rem;
            border-radius: 12px;
            border: 2px solid var(--primary);
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
            color: var(--slate-900);
            font-size: 1.1rem;
        }

        .progress-value {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.2rem;
        }

        .progress-bar-wrapper {
            height: 12px;
            background: white;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid var(--slate-200);
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, #3b82f6 100%);
            transition: width 0.5s ease;
            border-radius: 6px;
        }

        /* === TOKEN BANNER === */
        .token-banner {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            padding: 1.25rem;
            border-radius: 12px;
            border: 2px solid var(--warning);
            margin-top: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .token-label {
            font-weight: 600;
            color: var(--slate-900);
            font-size: 0.95rem;
        }

        .token-value {
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 700;
            color: var(--slate-900);
            font-family: 'Courier New', monospace;
            font-size: 1.1rem;
            border: 2px solid var(--slate-200);
        }

        /* === AUDIT SWITCHER === */
        .audit-switcher {
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            border: 1px solid var(--slate-200);
        }

        .switcher-title {
            font-weight: 700;
            color: var(--slate-900);
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
        }

        .audit-tab:hover {
            background: var(--slate-100);
            border-color: var(--slate-300);
        }

        .audit-tab.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .audit-tab.completed {
            background: var(--success);
            color: white;
            border-color: var(--success);
        }

        /* === CLAUSE CARDS === */
        .clause-grid {
            display: grid;
            gap: 1.5rem;
        }

        .clause-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            border: 1px solid var(--slate-200);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .clause-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .clause-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-bottom: 2px solid var(--primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .clause-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--slate-900);
            margin: 0;
        }

        .clause-meta {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .clause-progress {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .progress-count {
            font-size: 0.9rem;
            color: var(--slate-600);
            font-weight: 500;
        }

        .progress-percent {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .clause-body {
            padding: 1.5rem;
        }

        .clause-description {
            color: var(--slate-700);
            margin-bottom: 1.5rem;
            line-height: 1.7;
            font-size: 0.95rem;
        }

        .clause-cta {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
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
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-outline {
            background: transparent;
            color: var(--slate-700);
            border-color: var(--slate-300);
        }

        .btn-outline:hover {
            background: var(--slate-100);
            border-color: var(--slate-400);
        }

        .btn-success {
            background: var(--success);
            color: white;
            border-color: var(--success);
        }

        .btn-success:hover {
            background: white;
            color: var(--success);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* === FINISH BANNER === */
        .finish-banner {
            background: linear-gradient(135deg, #f0fdf4 0%, #e6fffa 100%);
            border-radius: var(--border-radius);
            padding: 2.5rem;
            text-align: center;
            border: 2px solid var(--success);
            margin-top: 2rem;
            animation: fadeInUp 0.7s ease-out;
            box-shadow: var(--shadow-lg);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .finish-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            display: block;
            color: var(--success);
        }

        .finish-message {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--slate-900);
            margin-bottom: 1rem;
        }

        .finish-subtext {
            color: var(--slate-600);
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
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .status-completed {
            background: linear-gradient(135deg, #f0fdf4 0%, #e6fffa 100%);
            color: var(--success);
            border: 1px solid var(--success);
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            body {
                padding: 1rem 0.5rem;
            }

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

            .clause-header {
                flex-direction: column;
                text-align: center;
            }

            .clause-meta {
                justify-content: center;
            }

            .clause-cta {
                justify-content: center;
            }

            .banner-actions {
                flex-direction: column;
                align-items: center;
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
</head>
<body>

<div class="container">
    <!-- HEADER -->
    <div class="header-card">
        <h1 class="header-title">Audit Session – {{ $deptName }}</h1>
        <p class="header-subtitle">Sesi audit internal untuk departemen {{ $deptName }} tahun {{ date('Y') }}</p>
        
        <div class="info-grid">
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <div class="info-label">Auditor</div>
                    <div class="info-text">{{ $auditorName }}</div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div>
                    <div class="info-label">Departemen</div>
                    <div class="info-text">{{ $deptName }}</div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <div>
                    <div class="info-label">Tahun Audit</div>
                    <div class="info-text">{{ date('Y') }}</div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-circle-dot"></i>
                </div>
                <div>
                    <div class="info-label">Status</div>
                    <div class="info-text">
                        <span class="status-badge status-in-progress">
                            <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                            In Progress
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Section -->
        <div class="progress-section">
            <div class="progress-header">
                <span class="progress-title">Progress Audit</span>
                <span class="progress-value">Step {{ $completedCount ?? 0 }} of {{ count($mainClauses) }}</span>
            </div>
            <div class="progress-bar-wrapper">
                <div class="progress-fill" style="width: {{ round(($completedCount ?? 0) / count($mainClauses) * 100) }}%;"></div>
            </div>
        </div>

        <!-- TOKEN BANNER -->
        <div class="token-banner">
            <div class="token-label">🔑 Resume Token</div>
            <div class="token-value">{{ $resumeToken ?? 'TOKEN TIDAK TERSEDIA' }}</div>
        </div>
    </div>

    <!-- AUDIT SWITCHER (if multiple audits) -->
    @if(isset($relatedAudits) && count($relatedAudits) > 1)
    <div class="audit-switcher">
        <div class="switcher-title">
            <i class="fas fa-list"></i> Audit Lainnya oleh {{ $auditorName }}
        </div>
        <div class="audit-tabs">
            @foreach($relatedAudits as $auditInfo)
                @php
                    $deptNameTab = DB::table('departments')->where('id', $auditInfo['dept_id'])->value('name');
                    $isCurrent = $auditInfo['id'] === $currentAuditId;
                    $auditStatus = DB::table('audits')->where('id', $auditInfo['id'])->value('status');
                    $isCompleted = in_array($auditStatus, ['COMPLETE', 'COMPLETED']);
                @endphp
                
                <a href="{{ route('audit.menu', ['id' => $auditInfo['id']]) }}" 
                   class="audit-tab {{ $isCurrent ? 'active' : '' }} {{ $isCompleted ? 'completed' : '' }}"
                   title="{{ $isCompleted ? '✅ Selesai' : ($isCurrent ? '📋 Sedang dikerjakan' : 'Klik untuk beralih') }}">
                    <i class="fas fa-building"></i>
                    {{ $deptNameTab }}
                    @if($isCompleted)
                        <i class="fas fa-check-circle"></i>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- CLAUSE GRID -->
    <div class="clause-grid">
        @foreach($mainClauses as $code)
            @php
                $prog = $clauseProgress[$code] ?? ['percentage' => 0, 'count' => 0, 'total' => 0];
                $isDone = $prog['percentage'] >= 100;
                $inProgress = $prog['count'] > 0 && !$isDone;
                $title = $titles[$code] ?? "Klausul {$code}";
            @endphp

            <div class="clause-card">
                <div class="clause-header">
                    <h2 class="clause-title">Klausul {{ $code }} – {{ $title }}</h2>
                    <div class="clause-meta">
                        <div class="clause-progress">
                            <span class="progress-count">{{ $prog['count'] }} / {{ $prog['total'] }} soal</span>
                            <span class="progress-percent">{{ $prog['percentage'] }}%</span>
                        </div>
                    </div>
                </div>
                
                <div class="clause-body">
                    <p class="clause-description">
                        @if($code === '4-1')
                            Evaluasi sistem manajemen mutu terhadap persyaratan standar ISO 9001:2015.
                        @elseif($code === '4-2')
                            Penilaian kesiapan dokumentasi dan prosedur operasional.
                        @elseif($code === '5-1')
                            Pengawasan terhadap pelaksanaan tugas dan tanggung jawab manajemen.
                        @elseif($code === '6-1')
                            Analisis risiko dan peluang dalam konteks sistem manajemen.
                        @elseif($code === '7-1')
                            Ketersediaan sumber daya manusia, infrastruktur, dan lingkungan kerja.
                        @elseif($code === '8-1')
                            Pengendalian proses operasional dan perubahan.
                        @elseif($code === '9-1')
                            Pemantauan, pengukuran, analisis, dan evaluasi kinerja.
                        @else
                            Deskripsi klausul ini akan ditampilkan berdasarkan data sistem.
                        @endif
                    </p>

                    <div class="clause-cta">
                        @if($isDone)
                            <a href="{{ route('audit.show', ['id' => $auditId, 'clause' => $code]) }}" 
                               class="btn btn-outline">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                        @else
                            <a href="{{ route('audit.show', ['id' => $auditId, 'clause' => $code]) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-arrow-right"></i> Lanjutkan Audit
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- FINISH BANNER -->
    @if(isset($allFinished) && $allFinished)
        <div class="finish-banner">
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

</body>
</html>
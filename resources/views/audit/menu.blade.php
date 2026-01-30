<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Session – {{ $deptName }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --success: #10b981;
            --warning: #f59e0b;
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
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--slate-100);
            color: var(--slate-900);
            line-height: 1.6;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        /* === HEADER === */
        .header {
            background: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, #3b82f6 100%);
        }

        .header-content {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .header-title {
            font-size: 1.875rem;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.02em;
        }

        .header-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            font-size: 0.95rem;
            color: var(--slate-600);
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .meta-icon {
            color: var(--slate-400);
            font-size: 1rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 1rem;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-draft { background: #f0f9ff; color: #1d4ed8; border: 1px solid #dbeafe; }
        .status-in-progress { background: #fef3c7; color: #92400e; border: 1px solid #fed7aa; }
        .status-submitted { background: #f0fdf4; color: #059669; border: 1px solid #bbf7d0; }

        .progress-bar-wrapper {
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid var(--slate-200);
        }

        .progress-label {
            display: flex;
            justify-content space-between;
            font-size: 0.875rem;
            color: var(--slate-600);
            margin-bottom: 0.5rem;
        }

        .progress-track {
            height: 8px;
            background: var(--slate-200);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--primary);
            border-radius: 4px;
            transition: width 0.6s ease-out;
        }

        /* === TOKEN BANNER === */
        .token-banner {
            background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid #bae6fd;
            margin-top: 1.5rem;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: #0c4a6e;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.1);
        }

        .token-label {
            font-size: 0.75rem;
            color: #3b82f6;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.25rem;
        }

        .token-value {
            font-size: 1.1rem;
            color: #1e40af;
            letter-spacing: 2px;
        }

        /* === AUDIT SWITCHER (Optional) === */
        .audit-switcher {
            background: var(--slate-50);
            padding: 1.25rem;
            border-radius: var(--border-radius);
            margin-bottom: 2.5rem;
        }

        .switcher-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--slate-700);
            margin-bottom: 1rem;
        }

        .audit-tabs {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .audit-tab {
            padding: 0.625rem 1.125rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid var(--slate-200);
            background: white;
            white-space: nowrap;
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

        /* === VERTICAL CLAUSE LIST (TIMELINE STYLE) === */
        .clause-list {
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
        }

        .clause-section {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .clause-section:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -6px rgba(0,0,0,0.06);
        }

        .clause-header {
            padding: 1.5rem 2rem;
            background: var(--slate-50);
            border-bottom: 1px solid var(--slate-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .clause-title {
            font-size: 1.375rem;
            font-weight: 700;
            color: var(--slate-900);
        }

        .clause-meta {
            display: flex;
            gap: 1.5rem;
            font-size: 0.9rem;
            color: var(--slate-600);
        }

        .clause-progress {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .progress-count {
            font-weight: 600;
            color: var(--slate-900);
        }

        .progress-percent {
            font-size: 0.875rem;
            color: var(--slate-600);
        }

        .clause-body {
            padding: 1.5rem 2rem;
        }

        .clause-description {
            font-size: 1rem;
            color: var(--slate-700);
            margin-bottom: 1.25rem;
            line-height: 1.6;
        }

        .clause-cta {
            display: flex;
            justify-content: flex-end;
            margin-top: 1rem;
        }

        .btn {
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
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

        /* === FINISH BANNER === */
        .finish-banner {
            background: linear-gradient(135deg, #f0fdf4 0%, #e6fffa 100%);
            border-radius: var(--border-radius);
            padding: 2.5rem;
            text-align: center;
            border: 2px solid var(--success);
            margin-top: 3rem;
            animation: fadeInUp 0.7s ease-out;
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
        }

        .banner-actions {
            display: flex;
            justify-content: center;
            gap: 1.25rem;
            flex-wrap: wrap;
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .header {
                padding: 1.5rem;
            }

            .header-title {
                font-size: 1.5rem;
            }

            .header-meta {
                flex-direction: column;
                gap: 0.75rem;
            }

            .clause-header {
                flex-direction: column;
                gap: 1rem;
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
        }
    </style>
</head>
<body>

<div class="container">
    <!-- HEADER -->
    <div class="header">
        <div class="header-content">
            <h1 class="header-title">Audit Session – {{ $deptName }}</h1>
            
            <div class="header-meta">
                <div class="meta-item">
                    <i class="fas fa-user-check meta-icon"></i>
                    <span><strong>{{ $auditorName }}</strong></span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-building meta-icon"></i>
                    <span>{{ $deptName }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar meta-icon"></i>
                    <span>{{ date('Y') }}</span>
                </div>
                <div class="meta-item">
                    <span class="status-badge status-in-progress">
                        <i class="fas fa-circle-dot" style="font-size: 0.75rem;"></i>
                        In Progress
                    </span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="progress-bar-wrapper">
                <div class="progress-label">
                    <span>Step {{ $completedCount ?? 0 }} of {{ count($mainClauses) }}</span>
                    <span>{{ round(($completedCount ?? 0) / count($mainClauses) * 100) }}%</span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill" style="width: {{ $completedCount ?? 0 }}%;"></div>
                </div>
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
        <div class="switcher-title">📋 Audit Lainnya oleh {{ $auditorName }}</div>
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
                    {{ $deptNameTab }}
                    @if($isCompleted)
                        <span style="margin-left: 4px;">✅</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- VERTICAL CLAUSE LIST -->
    <div class="clause-list">
        @foreach($mainClauses as $code)
            @php
                $prog = $clauseProgress[$code] ?? ['percentage' => 0, 'count' => 0, 'total' => 0];
                $isDone = $prog['percentage'] >= 100;
                $inProgress = $prog['count'] > 0 && !$isDone;
                $title = $titles[$code] ?? "Klausul {$code}";
            @endphp

            <div class="clause-section">
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
            <p class="finish-subtext">Semua klausul telah diisi dengan lengkap dan siap direview.</p>
            
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
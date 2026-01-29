<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Dashboard - {{ $deptName }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --success: #10b981;
            --warning: #f59e0b;
            --slate-50: #f8fafc;
            --slate-200: #e2e8f0;
            --slate-600: #475569;
            --slate-900: #0f172a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: var(--slate-900);
            line-height: 1.5;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            border-bottom: 4px solid var(--slate-200);
        }

        .header-left h1 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .header-left p {
            color: var(--slate-600);
            font-size: 0.9rem;
        }

        .token-banner {
            background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
            padding: 12px 20px;
            border-radius: 10px;
            border: 1px solid #bae6fd;
            margin-top: 12px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #0c4a6e;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.1);
        }

        .token-label {
            font-size: 0.75rem;
            color: #3b82f6;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .token-value {
            font-size: 1.1rem;
            color: #1e40af;
            letter-spacing: 2px;
        }

        .audit-switcher {
            background: var(--slate-50);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .switcher-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--slate-600);
            margin-bottom: 0.75rem;
        }

        .audit-tabs {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .audit-tab {
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid var(--slate-200);
            background: white;
        }

        .audit-tab:hover {
            background: var(--slate-50);
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

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.25rem;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-decoration: none;
            color: inherit;
            border: 1px solid var(--slate-200);
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 4px;
            background: var(--primary);
            transition: width 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px -5px rgba(0,0,0,0.1);
            border-color: var(--primary);
        }

        .card:hover::before {
            width: 8px;
        }

        .card-number {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            margin-bottom: 0.5rem;
            display: block;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--slate-900);
            margin-bottom: 2rem;
            padding-right: 1.5rem;
        }

        .status-area {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .card.completed::before { background: var(--success); }
        .card.completed { background: #f0fdf4; border-color: #bbf7d0; }
        .card.completed .card-number { color: var(--success); }

        .badge-completed { background: #dcfce7; color: var(--success); }
        .badge-pending { background: #f1f5f9; color: var(--slate-600); }
        .badge-inprogress { background: #fef3c7; color: var(--warning); }

        .arrow {
            color: var(--primary);
            transition: transform 0.3s;
        }
        .card:hover .arrow { transform: translateX(5px); }

        .finish-banner {
            margin-top: 3rem;
            padding: 2.5rem;
            background: linear-gradient(135deg, #f0fdf4 0%, #e6fffa 100%);
            border-radius: 16px;
            text-align: center;
            border: 2px solid var(--success);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .finish-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            display: block;
            color: var(--success);
        }

        .finish-message {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--slate-900);
            margin: 0 0 1rem;
        }

        .finish-subtext {
            color: var(--slate-600);
            font-size: 1rem;
            max-width: 600px;
            margin: 0 auto 1.5rem;
        }

        .banner-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn {
            padding: 12px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1rem;
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

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .grid-container {
                grid-template-columns: 1fr;
            }
            
            .banner-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="header-left">
            <h1>Audit Dashboard - {{ $deptName }}</h1>
            <p>Auditor: <strong>{{ $auditorName }}</strong> • Dept: <strong>{{ $deptName }}</strong></p>
            
            <!-- ✅ TOKEN RESUME BANNER -->
            <div class="token-banner">
                <div class="token-label">🔑 TOKEN</div>
                <div class="token-value">{{ $resumeToken ?? 'TOKEN TIDAK TERSEDIA' }}</div>
            </div>
            
            @if(isset($showReturnButton) && $showReturnButton)
                <div style="margin-top: 15px;">
                    <a href="{{ route('audit.select_department', ['id' => $auditId]) }}" 
                       style="display: inline-block; background: var(--primary); color: white; padding: 8px 20px; border-radius: 6px; text-decoration: none; font-size: 14px;">
                        ← Kembali Pilih Departemen Lain
                    </a>
                </div>
            @endif
        </div>
        
        <div style="text-align: right;">
            @php
                $completedCount = collect($clauseProgress)->filter(fn($p) => $p['percentage'] >= 100)->count();
            @endphp
            <span style="font-size: 0.85rem; font-weight: 700; color: var(--slate-600);">
                PROGRES: {{ $completedCount }} / {{ count($mainClauses) }} Klausul
            </span>
        </div>
    </div>

    <!-- Audit Switcher -->
    @if(isset($relatedAudits) && count($relatedAudits) > 1)
    <div class="audit-switcher">
        <div class="switcher-title">📋 Audit Lainnya oleh {{ $auditorName }}:</div>
        <div class="audit-tabs">
            @foreach($relatedAudits as $auditInfo)
                @php
                    $deptNameTab = DB::table('departments')->where('id', $auditInfo['dept_id'])->value('name');
                    $isCurrent = $auditInfo['id'] === ($currentAuditId ?? $auditId);
                    $auditStatus = DB::table('audits')->where('id', $auditInfo['id'])->value('status');
                    $isCompleted = in_array($auditStatus, ['COMPLETE', 'COMPLETED']);
                @endphp
                
                <a href="{{ route('audit.menu', ['id' => $auditInfo['id']]) }}" 
                   class="audit-tab {{ $isCurrent ? 'active' : '' }} {{ $isCompleted ? 'completed' : '' }}"
                   style="{{ $isCompleted ? 'opacity: 0.8;' : '' }}"
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

    <div class="grid-container">
        @foreach($mainClauses as $code)
            @php
                $prog = $clauseProgress[$code] ?? ['percentage' => 0, 'count' => 0, 'total' => 0];
                $isDone = $prog['percentage'] >= 100;
                $inProgress = $prog['count'] > 0 && !$isDone;
            @endphp

            <a href="{{ route('audit.show', ['id' => $auditId, 'clause' => $code]) }}" 
                class="card {{ $isDone ? 'completed' : '' }}">
                
                <span class="card-number">Klausul {{ $code }}</span>
                <span class="card-title">{{ $titles[$code] ?? 'Detail Klausul ' . $code }}</span>

                <div style="margin-top: auto; margin-bottom: 1rem;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.7rem; margin-bottom: 4px; font-weight: 700;">
                        <span>{{ $prog['count'] }} / {{ $prog['total'] }} Soal</span>
                        <span>{{ $prog['percentage'] }}%</span>
                    </div>
                    <div style="width: 100%; background: #e2e8f0; height: 6px; border-radius: 10px; overflow: hidden;">
                        <div style="width: {{ $prog['percentage'] }}%; background: {{ $isDone ? 'var(--success)' : ($inProgress ? 'var(--warning)' : 'var(--primary)') }}; height: 100%; transition: width 0.5s;"></div>
                    </div>
                </div>

                <div class="status-area">
                    @if($isDone)
                        <div class="status-badge badge-completed">
                            <span>✅ Selesai</span>
                        </div>
                    @elseif($inProgress)
                        <div class="status-badge badge-inprogress">
                            <span>📝 Dikerjakan</span>
                        </div>
                        <span class="arrow">→</span>
                    @else
                        <div class="status-badge badge-pending">Belum Dimulai</div>
                        <span class="arrow">→</span>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    @if(isset($allFinished) && $allFinished)
        <div class="finish-banner">
            <div class="finish-icon">🎉</div>
            <h2 class="finish-message">Audit {{ $deptName }} Selesai!</h2>
            <p class="finish-subtext">Semua klausul telah diisi dengan lengkap.</p>
            
            @if(isset($relatedAudits) && count($relatedAudits) > 1)
                <div class="banner-actions">
                    <a href="{{ route('audit.finish') }}" class="btn btn-success">
                        ✅ Selesai Semua Audit
                    </a>
                </div>
            @else
                <div class="banner-actions">
                    <a href="{{ route('audit.finish') }}" class="btn btn-success">
                        ✅ Selesai Audit
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>

</body>
</html>
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
            background: #e0f2fe;
            padding: 12px 20px;
            border-radius: 8px;
            border: 1px solid #bae6fd;
            margin-top: 10px;
            font-family: monospace;
            font-weight: bold;
            color: #0c4a6e;
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

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .grid-container {
                grid-template-columns: 1fr;
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
            
            <!-- ✅ TAMPILKAN TOKEN DI SINI -->
            <div class="token-banner">
                🔑 TOKEN RESUME: {{ $resumeToken }}
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
</div>

</body>
</html>
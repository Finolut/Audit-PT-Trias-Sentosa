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
            --slate-50: #f8fafc;
            --slate-200: #e2e8f0;
            --slate-600: #475569;
            --slate-900: #0f172a;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            color: var(--slate-900);
            line-height: 1.5;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Header Modern */
        .header {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid var(--slate-200);
        }

        .header-content h1 {
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.025em;
        }

        .header-content p {
            margin: 0.5rem 0 0;
            color: var(--slate-600);
            font-size: 0.9rem;
        }

        /* Grid */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.25rem;
        }

        /* Card Modern */
        .card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-decoration: none;
            color: inherit;
            border: 1px solid var(--slate-200);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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

        /* Status Styling */
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

        .arrow {
            color: var(--primary);
            transition: transform 0.3s;
        }
        .card:hover .arrow { transform: translateX(5px); }

        /* Finish Banner */
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

        .countdown {
            font-weight: 700;
            color: var(--success);
            font-size: 1.1rem;
            margin-top: 1rem;
        }

        @media (max-width: 640px) {
            .header { flex-direction: column; text-align: center; gap: 1rem; }
            .finish-message { font-size: 1.2rem; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="header-content">
            <h1>Audit Dashboard</h1>
            <p>Auditor: <strong>{{ $auditorName }}</strong> • Dept: <strong>{{ $deptName }}</strong></p>
        </div>
        <div class="progress-info">
            <span style="font-size: 0.85rem; font-weight: 700; color: var(--slate-600);">
                PROGRES: {{ count($completedClauses) }} / {{ count($mainClauses) }} Klausul
            </span>
        </div>
    </div>

<div class="grid-container">
    @foreach($mainClauses as $code)
        @php
            $prog = $clauseProgress[$code] ?? ['percentage' => 0, 'count' => 0, 'total' => 0];
            $isDone = $prog['percentage'] >= 100;
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
                    <div style="width: {{ $prog['percentage'] }}%; background: {{ $isDone ? 'var(--success)' : 'var(--primary)' }}; height: 100%; transition: width 0.5s;"></div>
                </div>
            </div>

            <div class="status-area">
                @if($isDone)
                    <div class="status-badge badge-completed">
                        <i class="fas fa-check-circle"></i> Selesai
                    </div>
                @else
                    <div class="status-badge badge-pending">Belum Lengkap</div>
                    <span class="arrow">→</span>
                @endif
            </div>
        </a>
    @endforeach
</div>

        <script>
            // Auto-redirect setelah 3 detik
            let seconds = 3;
            const countdownEl = document.getElementById('seconds');
            
            const timer = setInterval(() => {
                seconds--;
                countdownEl.textContent = seconds;
                
                if (seconds <= 0) {
                    clearInterval(timer);
                    // Ganti dengan route dashboard utama kamu
                    window.location.href = "{{ route('audit.setup') }}";
                }
            }, 1000);
        </script>
</div>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $auditCode }} - Pilih Departemen</title>
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
            text-align: center;
            margin-bottom: 2rem;
        }

        .audit-title {
            font-size: 28px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .auditor-info {
            color: #64748b;
            font-size: 16px;
        }

        .section-title {
            font-size: 20px;
            color: #334155;
            margin-bottom: 15px;
            text-align: center;
        }

        .section-subtitle {
            color: #64748b;
            text-align: center;
            margin-bottom: 2rem;
        }

        .departments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 2rem;
        }

        .department-card {
            background: #f1f5f9;
            border: 2px solid #94a3b8;
            border-radius: 12px;
            padding: 25px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .department-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.15);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            background: white;
            border: 3px solid #94a3b8;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
            font-weight: bold;
            color: #94a3b8;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 10px;
            text-align: center;
        }

        .status-badge {
            background: #94a3b8;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            display: inline-block;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .progress-container {
            margin-top: 15px;
            padding: 10px;
            background: white;
            border-radius: 8px;
        }

        .progress-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 5px;
        }

        .progress-bar {
            width: 100%;
            background: #e2e8f0;
            border-radius: 4px;
            height: 8px;
            overflow: hidden;
        }

        .progress-fill {
            width: 0%;
            background: #3b82f6;
            height: 100%;
            border-radius: 4px;
            transition: width 0.5s;
        }

        .progress-percentage {
            font-size: 11px;
            color: #64748b;
            text-align: right;
            margin-top: 3px;
        }

        .summary-section {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background: white;
            border-radius: 8px;
        }

        .summary-text {
            color: #64748b;
            margin-bottom: 15px;
        }

        .finish-button {
            display: inline-block;
            background: #22c55e;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s;
        }

        .finish-button:hover {
            background: #16a34a;
            transform: translateY(-2px);
        }

        /* Status-specific styles */
        .department-card.completed {
            background: #dcfce7;
            border-color: #22c55e;
        }

        .department-card.completed .card-icon {
            border-color: #22c55e;
            color: #22c55e;
        }

        .department-card.in_progress {
            background: #fef3c7;
            border-color: #f59e0b;
        }

        .department-card.in_progress .card-icon {
            border-color: #f59e0b;
            color: #f59e0b;
        }

        .status-badge.completed {
            background: #22c55e;
        }

        .status-badge.in_progress {
            background: #f59e0b;
        }

        .progress-fill.completed {
            background: #22c55e;
        }

        @media (max-width: 768px) {
            .departments-grid {
                grid-template-columns: 1fr;
            }
            
            body {
                padding: 1rem;
            }
            
            .audit-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="audit-title">{{ $auditCode }}</h1>
            <p class="auditor-info">Auditor: <strong>{{ $auditorName }}</strong></p>
        </div>

        <div class="section-header">
            <h2 class="section-title">Pilih Departemen yang Akan Diaudit</h2>
            <p class="section-subtitle">Klik card departemen untuk memulai audit</p>
        </div>

        <div class="departments-grid">
            @foreach($departments as $dept)
                @php
                    $progress = $dept['progress'];
                    $status = $dept['status'];
                    
                    // Set class berdasarkan status
                    $cardClass = '';
                    $badgeClass = '';
                    $progressColor = '#3b82f6';
                    
                    if ($status == 'completed') {
                        $cardClass = 'completed';
                        $badgeClass = 'completed';
                        $progressColor = '#22c55e';
                    } elseif ($status == 'in_progress') {
                        $cardClass = 'in_progress';
                        $badgeClass = 'in_progress';
                        $progressColor = '#f59e0b';
                    }
                @endphp

                <div class="department-card {{ $cardClass }}" onclick="selectDepartment('{{ $dept['id'] }}')">
                    <div style="text-align: center;">
                        <div class="card-icon">
                            {{ substr($dept['name'], 0, 1) }}
                        </div>

                        <h3 class="card-title">{{ $dept['name'] }}</h3>

                        @if($status == 'completed')
                            <div class="status-badge {{ $badgeClass }}">
                                ✅ Selesai
                            </div>
                        @elseif($status == 'in_progress')
                            <div class="status-badge {{ $badgeClass }}">
                                📝 Dikerjakan
                            </div>
                        @else
                            <div class="status-badge">
                                ⏳ Belum Dimulai
                            </div>
                        @endif

                        <div class="progress-container">
                            <div class="progress-label">
                                Progress: <strong>{{ $progress['completed'] }} / {{ $progress['total'] }} Klausul</strong>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill {{ $badgeClass }}" style="width: {{ $progress['percentage'] }}%; background: {{ $progressColor }};"></div>
                            </div>
                            <div class="progress-percentage">
                                {{ $progress['percentage'] }}%
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="summary-section">
            <p class="summary-text">
                <strong>{{ $departments->where('status', 'completed')->count() }}</strong> dari <strong>{{ $departments->count() }}</strong> departemen telah selesai
            </p>
            @if($departments->where('status', 'completed')->count() == $departments->count())
                <a href="{{ route('audit.finish') }}" class="finish-button">
                    ✅ Selesai Semua Audit
                </a>
            @endif
        </div>
    </div>

    <form id="deptForm" method="POST" action="{{ route('audit.set_department', ['id' => $auditId]) }}">
        @csrf
        <input type="hidden" name="department_id" id="deptId">
    </form>

    <script>
        function selectDepartment(deptId) {
            if(confirm('Mulai audit untuk departemen ini?')) {
                document.getElementById('deptId').value = deptId;
                document.getElementById('deptForm').submit();
            }
        }
    </script>
</body>
</html>
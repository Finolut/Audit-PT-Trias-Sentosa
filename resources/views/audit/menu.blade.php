<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Session</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --nav-blue: #1a365d;
            --accent-yellow: #fbbf24;
            --white: #ffffff;
            --gray-50: #f8fafc;
            --gray-200: #e2e8f0;
            --gray-600: #475569;
            --gray-900: #0f172a;
            --shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--gray-50); color: var(--gray-900); line-height: 1.6; }
        
        /* NAVBAR */
        .navbar { background: var(--nav-blue); color: var(--white); padding: 1rem 2rem; box-shadow: var(--shadow); }
        .navbar-content { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; }
        .navbar-brand { font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; }
        .navbar-brand i { color: var(--accent-yellow); }
        .navbar-info { font-size: 0.9rem; }

        /* CONTAINER */
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        
        /* HEADER */
        .header { background: var(--white); padding: 2rem; border-radius: 12px; box-shadow: var(--shadow); margin-bottom: 2rem; }
        .header-title { font-size: 1.75rem; font-weight: 700; margin-bottom: 1rem; }
        .header-meta { display: flex; gap: 1.5rem; font-size: 0.9rem; color: var(--gray-600); }
        .meta-item { display: flex; align-items: center; gap: 0.5rem; }

        /* TOKEN BANNER */
        .token-banner { background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%); padding: 1rem; border-radius: 10px; border: 1px solid #bae6fd; margin-top: 1.5rem; }
        .token-label { font-size: 0.8rem; color: #3b82f6; text-transform: uppercase; margin-bottom: 0.5rem; font-weight: 600; }
        .token-value { font-size: 1.1rem; color: #1e40af; font-weight: 700; letter-spacing: 1px; font-family: 'Courier New', monospace; }
        .token-info { font-size: 0.85rem; color: #0c4a6e; margin-top: 0.5rem; font-weight: 500; }

        /* DEPARTMENT CARDS */
        .departments-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .department-card { background: var(--white); border-radius: 12px; box-shadow: var(--shadow); overflow: hidden; transition: transform 0.3s; }
        .department-card:hover { transform: translateY(-4px); }
        .card-header { padding: 1.25rem; background: var(--gray-50); border-bottom: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center; }
        .card-title { font-size: 1.25rem; font-weight: 600; }
        .card-status { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 600; }
        .status-completed { background: #dcfce7; color: #155e41; padding: 0.25rem 0.75rem; border-radius: 20px; }
        .status-inprogress { background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 20px; }
        .status-pending { background: #f1f5f9; color: #64748b; padding: 0.25rem 0.75rem; border-radius: 20px; }
        .card-body { padding: 1.25rem; }
        .progress-bar { height: 6px; background: var(--gray-200); border-radius: 3px; margin-top: 0.75rem; overflow: hidden; }
        .progress-fill { height: 100%; background: var(--accent-yellow); width: 60%; transition: width 0.5s; }
        .progress-label { display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--gray-600); margin-top: 0.5rem; }
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; border-radius: 6px; font-weight: 600; text-decoration: none; transition: all 0.3s; border: none; }
        .btn-primary { background: var(--accent-yellow); color: var(--gray-900); }
        .btn-primary:hover { background: #f59e0b; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3); }
        .btn-outline { background: transparent; color: var(--gray-600); border: 1px solid var(--gray-200); }
        .btn-outline:hover { background: var(--gray-50); }

        /* FOOTER */
        .footer { text-align: center; margin-top: 2rem; color: var(--gray-600); font-size: 0.85rem; }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .departments-grid { grid-template-columns: 1fr; }
            .header-title { font-size: 1.5rem; }
            .header-meta { flex-direction: column; gap: 0.5rem; }
            .card-header { flex-direction: column; text-align: center; gap: 0.75rem; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="navbar-content">
        <div class="navbar-brand">
            <i class="fas fa-clipboard-check"></i>
            <span>Internal Audit System</span>
        </div>
        <div class="navbar-info">
            <i class="fas fa-user"></i> {{ $auditorName }}
        </div>
    </div>
</nav>

<div class="container">
    <!-- HEADER -->
    <div class="header">
        <h1 class="header-title">Audit Session – {{ $deptName }}</h1>
        
        <div class="header-meta">
            <div class="meta-item">
                <i class="fas fa-building"></i>
                <span>{{ $deptName }}</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-calendar"></i>
                <span>{{ date('Y') }}</span>
            </div>
            <div class="meta-item">
                <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                    <i class="fas fa-circle-dot" style="font-size: 0.6rem;"></i> In Progress
                </span>
            </div>
        </div>

        <!-- TOKEN BANNER -->
        <div class="token-banner">
            <div class="token-label">🔑 Resume Token</div>
            <div class="token-value">{{ $resumeToken ?? 'TOKEN TIDAK TERSEDIA' }}</div>
            <div class="token-info">
                <i class="fas fa-info-circle"></i> Simpan token ini untuk melanjutkan audit jika terjadi gangguan
            </div>
        </div>
    </div>

    <!-- DEPARTMENTS GRID -->
    <div class="departments-grid">
        @foreach($relatedAudits as $auditInfo)
            @php
                $isCurrent = $auditInfo['id'] === $currentAuditId;
                $progress = $this->getDepartmentProgress($auditInfo['id'], $auditInfo['dept_id']);
                $status = $this->getDepartmentStatus($auditInfo['id'], $auditInfo['dept_id']);
            @endphp
            
            <div class="department-card {{ $isCurrent ? 'current-department' : '' }}">
                <div class="card-header">
                    <h2 class="card-title">{{ $auditInfo['dept_name'] }}</h2>
                    <div class="card-status">
                        @if($status === 'completed')
                            <span class="status-completed">✅ Selesai</span>
                        @elseif($status === 'in_progress')
                            <span class="status-inprogress">📝 Dikerjakan</span>
                        @else
                            <span class="status-pending">⏳ Belum Dimulai</span>
                        @endif
                    </div>
                </div>
                
                <div class="card-body">
                    <p class="clause-desc" style="color: var(--gray-600); margin-bottom: 1rem;">
                        Audit kualitas untuk {{ $auditInfo['dept_name'] }} berdasarkan standar ISO 9001:2015
                    </p>
                    
                    <div class="progress-label">
                        <span>{{ $progress['completed'] }} / {{ $progress['total'] }} Klausul</span>
                        <span>{{ $progress['percentage'] }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $progress['percentage'] }}%;"></div>
                    </div>
                    
                    <div style="text-align: right; margin-top: 1.25rem;">
                        <a href="{{ route('audit.menu', ['id' => $auditInfo['id']]) }}" 
                           class="btn {{ $isCurrent ? 'btn-primary' : 'btn-outline' }}">
                            @if($isCurrent)
                                <i class="fas fa-check"></i> Sedang Dikerjakan
                            @else
                                <i class="fas fa-arrow-right"></i> Lanjutkan
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>© {{ date('Y') }} PT TRIAS SENTOSA TBK. All Rights Reserved.</p>
        <p style="margin-top: 0.5rem; font-size: 0.8rem; color: var(--gray-500);">
            Sistem ini dirancang khusus untuk proses audit internal berbasis ISO 9001:2015
        </p>
    </div>
</div>

</body>
</html>
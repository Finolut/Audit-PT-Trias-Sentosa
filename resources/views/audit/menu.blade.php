<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit – {{ $deptName }}</title>
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

        .container { max-width: 900px; margin: 2rem auto; padding: 0 1rem; }

        /* HEADER */
        .header { background: var(--white); padding: 2rem; border-radius: 12px; box-shadow: var(--shadow); margin-bottom: 2rem; }
        .header-title { font-size: 1.75rem; font-weight: 700; margin-bottom: 1rem; }
        .header-meta { display: flex; gap: 1.5rem; font-size: 0.9rem; color: var(--gray-600); }
        .meta-item { display: flex; align-items: center; gap: 0.5rem; }

        .progress-bar { height: 6px; background: var(--gray-200); border-radius: 3px; margin-top: 1.5rem; overflow: hidden; }
        .progress-fill { height: 100%; background: var(--accent-yellow); width: {{ $completedCount ?? 0 }}%; transition: width 0.5s; }

        /* TOKEN BANNER */
        .token-banner { background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%); padding: 1rem; border-radius: 10px; border: 1px solid #bae6fd; margin-top: 1.5rem; }
        .token-label { font-size: 0.8rem; color: #3b82f6; text-transform: uppercase; margin-bottom: 0.5rem; font-weight: 600; }
        .token-value { font-size: 1.1rem; color: #1e40af; font-weight: 700; letter-spacing: 1px; font-family: 'Courier New', monospace; }
        .token-info { font-size: 0.85rem; color: #0c4a6e; margin-top: 0.5rem; font-weight: 500; }

        /* CLAUSE LIST */
        .clause-list { display: flex; flex-direction: column; gap: 1.5rem; }
        .clause-card { background: var(--white); border-radius: 12px; box-shadow: var(--shadow); overflow: hidden; transition: transform 0.3s; }
        .clause-card:hover { transform: translateY(-4px); }
        .clause-header { padding: 1.25rem 1.5rem; background: var(--gray-50); border-bottom: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center; }
        .clause-title { font-size: 1.25rem; font-weight: 600; }
        .clause-progress { font-size: 0.85rem; color: var(--gray-600); }
        .clause-body { padding: 1.25rem 1.5rem; }
        .clause-desc { font-size: 0.95rem; color: var(--gray-600); margin-bottom: 1rem; line-height: 1.5; }
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.5rem; border-radius: 6px; font-weight: 600; text-decoration: none; transition: all 0.3s; border: none; }
        .btn-primary { background: var(--accent-yellow); color: var(--gray-900); }
        .btn-primary:hover { background: #f59e0b; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3); }
        .btn-outline { background: transparent; color: var(--gray-600); border: 1px solid var(--gray-200); }
        .btn-outline:hover { background: var(--gray-50); }

        /* FINISH BANNER */
        .finish-banner { background: linear-gradient(135deg, #f0fdf4 0%, #e6fffa 100%); border-radius: 12px; padding: 2rem; text-align: center; border: 2px solid #10b981; margin-top: 2rem; }
        .finish-message { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; }
        .finish-subtext { color: var(--gray-600); margin-bottom: 1.5rem; }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .navbar-content, .header { padding: 1rem; }
            .header-title { font-size: 1.5rem; }
            .header-meta { flex-direction: column; gap: 0.5rem; }
            .clause-header { flex-direction: column; gap: 0.75rem; text-align: center; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="navbar-content">
        <div class="navbar-brand">
            <i class="fas fa-clipboard-check"></i>
            <span>Quality Audit System</span>
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

        <!-- Progress Bar -->
        <div style="margin-top: 1.25rem;">
            <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--gray-600); margin-bottom: 0.5rem;">
                <span>Step {{ $completedCount ?? 0 }} of {{ count($mainClauses) }}</span>
                <span>{{ round(($completedCount ?? 0) / count($mainClauses) * 100) }}%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill"></div>
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

    <!-- CLAUSE LIST -->
    <div class="clause-list">
        @foreach($mainClauses as $code)
            @php
                $prog = $clauseProgress[$code] ?? ['percentage' => 0, 'count' => 0, 'total' => 0];
                $isDone = $prog['percentage'] >= 100;
                $title = $titles[$code] ?? "Klausul {$code}";
            @endphp

            <div class="clause-card">
                <div class="clause-header">
                    <h2 class="clause-title">Klausul {{ $code }} – {{ $title }}</h2>
                    <div class="clause-progress">{{ $prog['count'] }} / {{ $prog['total'] }} soal • {{ $prog['percentage'] }}%</div>
                </div>
                
                <div class="clause-body">
                    <p class="clause-desc">
                        @if($code === '4-1') Evaluasi sistem manajemen mutu ISO 9001:2015.
                        @elseif($code === '4-2') Penilaian kesiapan dokumentasi dan prosedur.
                        @elseif($code === '5-1') Pengawasan tugas dan tanggung jawab manajemen.
                        @elseif($code === '6-1') Analisis risiko dan peluang sistem manajemen.
                        @elseif($code === '7-1') Ketersediaan sumber daya manusia dan infrastruktur.
                        @elseif($code === '8-1') Pengendalian proses operasional dan perubahan.
                        @elseif($code === '9-1') Pemantauan, pengukuran, dan evaluasi kinerja.
                        @else Deskripsi klausul akan ditampilkan berdasarkan data sistem.
                        @endif
                    </p>

                    <div style="text-align: right;">
                        @if($isDone)
                            <a href="{{ route('audit.show', ['id' => $auditId, 'clause' => $code]) }}" class="btn btn-outline">
                                <i class="fas fa-eye"></i> Lihat
                            </a>
                        @else
                            <a href="{{ route('audit.show', ['id' => $auditId, 'clause' => $code]) }}" class="btn btn-primary">
                                <i class="fas fa-arrow-right"></i> Lanjutkan
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
            <div style="font-size: 3rem; margin-bottom: 1rem;">🎉</div>
            <h2 class="finish-message">Audit {{ $deptName }} Selesai!</h2>
            <p class="finish-subtext">Semua klausul telah diisi dengan lengkap dan siap direview.</p>
            
            <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('audit.finish') }}" class="btn btn-primary" style="background: #10b981; color: white;">
                    <i class="fas fa-check-circle"></i> Selesaikan Audit
                </a>
            </div>
        </div>
    @endif
</div>

</body>
</html>
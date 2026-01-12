<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Dashboard - {{ $deptName }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* Abu-abu sangat muda */
            margin: 0;
            padding: 0;
            color: #1e293b;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Header Styles */
        .header {
            margin-bottom: 40px;
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 6px solid #2563eb;
        }
        .header h1 { margin: 0; font-size: 24px; color: #0f172a; }
        .header p { margin: 8px 0 0; color: #64748b; }

        /* Grid Layout */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); /* Responsif */
            gap: 20px;
        }

        /* Card Style (Mirip Gambar) */
        .card {
            background: white;
            border-radius: 8px;
            padding: 24px;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            border-left: 5px solid #3b82f6; /* Garis biru di kiri */
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 140px; /* Tinggi tetap agar rapi */
            position: relative;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-left-color: #2563eb;
        }

        .card-number {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .card-title {
            font-size: 14px;
            color: #475569;
            line-height: 1.5;
            flex-grow: 1; /* Agar teks mengisi ruang */
        }

        .arrow-icon {
            position: absolute;
            top: 24px;
            right: 24px;
            color: #3b82f6;
            font-weight: bold;
            font-size: 18px;
        }

        /* Tombol Selesai */
        .finish-section {
            margin-top: 40px;
            text-align: center;
        }
        .finish-btn {
            background-color: #10b981;
            color: white;
            padding: 14px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.4);
        }
        .finish-btn:hover { background-color: #059669; }

  /* Warna kartu jika sudah selesai */
.card.completed {
    border-left: 5px solid #10b981 !important; /* Hijau */
    background-color: #f0fdf4 !important; /* Hijau sangat muda */
}

/* Badge Status di pojok kanan bawah */
.status-badge {
    position: absolute;
    bottom: 15px;
    right: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 600;
}

.badge-completed { color: #10b981; }
.badge-pending { color: #94a3b8; }

/* Lingkaran Ceklis */
.check-icon {
    background: #10b981;
    color: white;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
}
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Audit Dashboard</h1>
        <p>Auditor: <strong>{{ $auditorName }}</strong> | Target: <strong>{{ $deptName }}</strong></p>
    </div>

   <div class="grid-container">
    @foreach($mainClauses as $code)
        @php
            // Cocokkan apakah kode klausul (misal: "4") ada dalam daftar yang sudah dijawab
            $isDone = in_array((string)$code, $completedClauses);
        @endphp

        <a href="{{ route('audit.show', ['id' => $auditId, 'clause' => $code]) }}" 
           class="card {{ $isDone ? 'completed' : '' }}">
            
            <span class="card-number">{{ $code }}</span>
            
            <span class="card-title">
                {{ $titles[$code] ?? 'Clause ' . $code }}
            </span>

            @if($isDone)
                <div class="status-badge badge-completed">
                    <span class="check-icon">✓</span> Selesai
                </div>
            @else
                <div class="status-badge badge-pending">
                    Belum diisi
                </div>
                <span class="arrow-icon">→</span>
            @endif
        </a>
    @endforeach
</div>
@if(count($completedClauses) == count($mainClauses))
    <div class="finish-section">
        <button type="submit" class="finish-btn">Submit Laporan Final ✓</button>
    </div>
@endif
</body>
</html>
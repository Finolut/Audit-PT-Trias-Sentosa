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

    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Audit Dashboard</h1>
        <p>Auditor: <strong>{{ $auditorName }}</strong> | Target: <strong>{{ $deptName }}</strong></p>
    </div>

    <div class="grid-container">
        @foreach($clauses as $clauseCode)
            <a href="{{ route('audit.show', ['id' => $auditId, 'clause' => $clauseCode]) }}" class="card">
                <span class="card-number">{{ $clauseCode }}</span>
                <span class="card-title">
                    {{ $titles[$clauseCode] ?? 'Klausul ' . $clauseCode }}
                </span>
                <span class="arrow-icon">→</span>
            </a>
        @endforeach
    </div>

    <div class="finish-section">
        <p style="color: #64748b; margin-bottom: 15px;">Sudah menyelesaikan semua klausul?</p>
        <form action="{{ route('audit.finish') }}" method="GET">
             {{-- Nanti bisa ditambahkan logika validasi apakah semua sudah terisi --}}
            <button type="submit" class="finish-btn">Selesai & Submit Laporan</button>
        </form>
    </div>
</div>

</body>
</html>
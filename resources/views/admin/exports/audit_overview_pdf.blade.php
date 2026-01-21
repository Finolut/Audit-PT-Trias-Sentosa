<!DOCTYPE html>
<html>
<head>
    <title>Laporan Audit #{{ $audit->id }}</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            margin: 40px; 
            color: #333;
        }
        
        .header {
            background-color: #f5f5f5;
            padding: 20px;
            border-bottom: 3px solid #3b82f6;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 24px;
            border-radius: 8px;
        }
        
        .company-info {
            flex-grow: 1;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin: 0 0 5px 0;
        }
        
        .tagline {
            font-size: 14px;
            color: #6b7280;
            margin: 0 0 10px 0;
        }
        
        .address-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 15px 0;
        }
        
        .address-box {
            background: white;
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }
        
        .address-title {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        
        .certifications {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }
        
        .cert-logo {
            width: 80px;
            height: auto;
            border: 1px solid #d1d5db;
            padding: 5px;
            background: white;
            border-radius: 4px;
        }
        
        .audit-header {
            text-align: center;
            margin: 30px 0;
            padding: 15px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        
        .audit-title {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            margin: 0 0 5px 0;
        }
        
        .audit-meta {
            font-size: 14px;
            color: #6b7280;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        th, td {
            border: 1px solid #d1d5db;
            padding: 12px;
            text-align: left;
        }
        
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #374151;
            font-size: 14px;
        }
        
        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        
        .status-yes { background: #dcfce7; color: #166534; }
        .status-no { background: #fee2e2; color: #b91c1c; }
        .status-partial { background: #ffedd5; color: #c2410c; }
        .status-na { background: #f3e8ff; color: #7e22ce; }
        .status-unanswered { background: #e2e8f0; color: #4b5563; }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- HEADER SECTION -->
    <div class="header">
        <div class="logo-section">
            <div class="logo">TS</div> <!-- Ganti dengan logo asli jika punya -->
            <div class="company-info">
                <div class="company-name">PT TRIAS SENTOSA Tbk</div>
                <div class="tagline">FLEXIBLE PACKAGING FILM MANUFACTURER</div>
            </div>
        </div>
        
        <div class="certifications">
            <img src="https://via.placeholder.com/80x40?text=LRQA" alt="LRQA Certified" class="cert-logo">
            <img src="https://via.placeholder.com/80x40?text=UKAS" alt="UKAS Certified" class="cert-logo">
            <img src="https://via.placeholder.com/80x40?text=ISCC" alt="ISCC Certified" class="cert-logo">
            <img src="https://via.placeholder.com/80x40?text=ISO+14001" alt="ISO 14001" class="cert-logo">
        </div>
    </div>
    
    <!-- ADDRESS SECTION -->
    <div class="address-section">
        <div class="address-box">
            <div class="address-title">HEAD OFFICE / WARU PLANT</div>
            <div>Jl. Raya Waru No.1 B, Waru,</div>
            <div>Sidoarjo 61256, Indonesia</div>
            <div>Ph: +62-31-8533125, Fax: +62-31-8534116</div>
        </div>
        
        <div class="address-box">
            <div class="address-title">JAKARTA OFFICE</div>
            <div>Altira Business Park</div>
            <div>Jl. Yos Sudarso Kav.85 Blok A01-07, 5th Floor, Sunter</div>
            <div>Jakarta Utara 14350, Indonesia</div>
            <div>Ph: +62-21-29615575, Fax: +62-21-29615565</div>
        </div>
        
        <div class="address-box">
            <div class="address-title">KRIAN PLANT</div>
            <div>Desa Keboharan, Km 26, Krian,</div>
            <div>Sidoarjo 61262, Indonesia</div>
            <div>Ph: +62-31-8975825, Fax: +62-31-8972998</div>
        </div>
        
        <div class="address-box">
            <div class="address-title">SURABAYA OFFICE</div>
            <div>Spazio Tower 15th Floor</div>
            <div>Jl. Mayjen Yono Suwoyo,</div>
            <div>Surabaya 60225, Indonesia</div>
            <div>Ph: +62-31-99144888, Fax: +62-31-99148510</div>
        </div>
    </div>

    <!-- AUDIT INFO -->
    <div class="audit-header">
        <div class="audit-title">PERJANJIAN MAGANG</div>
        <div class="audit-meta">Nomor: 0353/HRGA/XII/2025</div>
    </div>

    <!-- AUDIT DETAILS -->
    <div style="margin: 30px 0;">
        <h2 style="color: #1e40af; border-bottom: 2px solid #3b82f6; padding-bottom: 5px;">AUDIT OVERVIEW</h2>
        <p><strong>Department:</strong> {{ $audit->department->name ?? '-' }}</p>
        <p><strong>Tanggal Audit:</strong> {{ $audit->created_at->format('d F Y') }}</p>
        <p><strong>Auditor:</strong> {{ $audit->auditor_name ?? '-' }}</p>
        <p><strong>Tipe Audit:</strong> 
            @php
                $typeLabels = [
                    'Regular' => 'Pemeriksaan Rutin (Terjadwal)',
                    'Special' => 'Pemeriksaan Khusus (Mendadak)',
                    'FollowUp' => 'Pemeriksaan Lanjutan (Follow Up)'
                ];
            @endphp
            {{ $typeLabels[$audit->type] ?? '-' }}
        </p>
    </div>

    <!-- DETAILED ITEMS TABLE -->
    <table>
        <thead>
            <tr>
                <th>Klausul</th>
                <th>Item</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detailedItems as $item)
            <tr>
                <td>{{ $item['sub_clause'] }}</td>
                <td>{{ $item['item_text'] }}</td>
                <td>
                    <span class="status-badge status-{{ strtolower($item['status']) }}">
                        {{ $item['status'] }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        <p>Generated on {{ now()->format('d F Y H:i') }} | PT TRIAS SENTOSA Tbk - QHSE Department</p>
    </div>
</body>
</html>
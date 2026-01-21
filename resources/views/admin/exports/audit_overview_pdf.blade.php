<!DOCTYPE html>
<html>
<head>
    <title>Laporan Audit #{{ $audit->id }}</title>
    <style>
body { 
        font-family: Arial, sans-serif; 
        margin: 20px; 
        color: #333;
    }
        
       /* Layout Header menggunakan Table agar stabil di PDF */
    .header-table {
        width: 100%;
        border-collapse: collapse;
        border-bottom: 2px solid #003366; /* Garis biru formal */
        margin-bottom: 20px;
    }

    .header-table td {
        vertical-align: top;
        border: none;
        padding: 5px;
    }

    .logo-img {
        height: 50px; /* Ukuran logo diatur agar proporsional */
    }

    .cert-container {
        text-align: right;
    }

    .cert-logo {
        height: 35px;
        margin-left: 10px;
    }

    /* Styling Alamat */
    .address-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .address-table td {
        width: 50%; /* Membagi 2 kolom kiri dan kanan */
        font-size: 9px;
        line-height: 1.2;
        padding-bottom: 10px;
        vertical-align: top;
        border: none;
    }

    .address-title {
        font-weight: bold;
        color: #003366;
        text-transform: uppercase;
        margin-bottom: 2px;
        display: block;
    }

    .contact-info {
        color: #555;
    }
    
    /* Audit Detail Styling */
    .audit-title-section {
        color: #1e40af; 
        border-bottom: 2px solid #3b82f6; 
        padding-bottom: 5px;
        font-size: 18px;
        text-transform: uppercase;
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
  <table class="header-table">
    <tr>
        <td>
            <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="logo-img">
        </td>
        <td class="cert-container">
            <img src="https://via.placeholder.com/80x40?text=ISO+9001" class="cert-logo">
            <img src="https://via.placeholder.com/80x40?text=ISO+14001" class="cert-logo">
        </td>
    </tr>
</table>

<table class="address-table">
    <tr>
        <td>
            <span class="address-title">HEAD OFFICE / WARU PLANT :</span>
            Jl. Raya Waru No.1 B, Waru, Sidoarjo 61256, Indonesia<br>
            <span class="contact-info">Ph: +62-31-8533125, Fax: +62-31-8534116</span>
            
            <div style="margin-top: 8px;">
                <span class="address-title">KRIAN PLANT :</span>
                Desa Keboharan, Km 26, Krian, Sidoarjo 61262, Indonesia<br>
                <span class="contact-info">Ph: +62-31-8975825, Fax: +62-31-8972998</span>
            </div>
        </td>
        
        <td>
            <span class="address-title">JAKARTA OFFICE :</span>
            Altira Business Park, Jl. Yos Sudarso Kav.85 Blok A01-07, 5th Floor<br>
            Sunter, Jakarta Utara 14350, Indonesia<br>
            <span class="contact-info">Ph: +62-21-29615575, Fax: +62-21-29615565</span>
            
            <div style="margin-top: 8px;">
                <span class="address-title">SURABAYA OFFICE :</span>
                Spazio Tower 15th Floor, Jl. Mayjen Yono Suwoyo,<br>
                Surabaya 60225, Indonesia<br>
                <span class="contact-info">Ph: +62-31-99144888, Fax: +62-31-99148510</span>
            </div>
        </td>
    </tr>
</table>

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
        <p>Generated on {{ now()->format('d F Y H:i') }} | PT TRIAS SENTOSA Tbk</p>
    </div>
</body>
</html>
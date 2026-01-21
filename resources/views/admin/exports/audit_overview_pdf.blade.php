<!DOCTYPE html>
<html>
<head>
    <title>Laporan Audit #{{ $audit->id }}</title>
    <style>
        @page {
            margin: 1cm;
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            margin: 10px; 
            color: #333;
            line-height: 1.4;
        }
        
        /* Header utama dengan Logo & Sertifikat */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px solid #003366;
            margin-bottom: 10px;
        }
        .header-table td {
            vertical-align: middle;
            border: none;
            padding-bottom: 10px;
        }
        .logo-img {
            height: 55px;
        }
        .cert-container {
            text-align: right;
        }
        .cert-logo {
            height: 35px;
            margin-left: 8px;
        }

        /* Tabel Alamat 2 Kolom */
        .address-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .address-table td {
            width: 50%;
            font-size: 9px;
            line-height: 1.2;
            padding: 5px;
            vertical-align: top;
            border: none;
        }
        .address-title {
            font-weight: bold;
            color: #003366;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }
        .contact-info {
            color: #555;
        }

        /* Section Audit Overview */
        .audit-overview {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8fafc;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
        }
        .audit-title-section {
            color: #1e40af; 
            border-bottom: 2px solid #3b82f6; 
            padding-bottom: 5px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
            margin-top: 0;
        }
        .audit-info-grid {
            width: 100%;
            font-size: 12px;
        }
        .audit-info-grid td {
            padding: 3px 0;
            border: none;
        }

        /* Tabel Data Audit */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .data-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #374151;
            font-size: 11px;
            text-align: center;
            border: 1px solid #d1d5db;
            padding: 10px;
        }
        .data-table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            font-size: 11px;
            vertical-align: top;
        }
        .text-center { text-align: center; }

        /* Status & Maturity Styling */
        .status-badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
            text-transform: uppercase;
        }
        .status-yes { background: #dcfce7; color: #166534; }
        .status-no { background: #fee2e2; color: #b91c1c; }
        .status-na { background: #f3e8ff; color: #7e22ce; }
        
        .maturity-text {
            font-weight: bold;
            color: #4b5563;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <img src="{{ asset('images/ts.jpg') }}" alt="Logo PT TRIAS SENTOSA Tbk" class="logo-img">
            </td>
            <div class="company-name">PT TRIAS SENTOSA Tbk</div>
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
                
                <div style="margin-top: 10px;">
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
                
                <div style="margin-top: 10px;">
                    <span class="address-title">SURABAYA OFFICE :</span>
                    Spazio Tower 15th Floor, Jl. Mayjen Yono Suwoyo,<br>
                    Surabaya 60225, Indonesia<br>
                    <span class="contact-info">Ph: +62-31-99144888, Fax: +62-31-99148510</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="audit-overview">
        <h2 class="audit-title-section">AUDIT OVERVIEW</h2>
        <table class="audit-info-grid">
            <tr>
                <td style="width: 120px;"><strong>Department</strong></td>
                <td>: {{ $audit->department->name ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Audit</strong></td>
                <td>: {{ $audit->created_at->format('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>Auditor</strong></td>
                <td>: {{ $audit->auditor_name ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Tipe Audit</strong></td>
                <td>: 
                    @php
                        $typeLabels = [
                            'Regular' => 'Pemeriksaan Rutin (Terjadwal)',
                            'Special' => 'Pemeriksaan Khusus (Mendadak)',
                            'FollowUp' => 'Pemeriksaan Lanjutan (Follow Up)'
                        ];
                    @endphp
                    {{ $typeLabels[$audit->type] ?? '-' }}
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">Klausul</th>
                <th style="width: 62%;">Item Pemeriksaan</th>
                <th style="width: 15%;">Maturity</th>
                <th style="width: 15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            {{-- Mengurutkan berdasarkan klausul dari terkecil ke terbesar --}}
            @foreach(collect($detailedItems)->sortBy('sub_clause') as $item)
            <tr>
                <td class="text-center"><strong>{{ $item['sub_clause'] }}</strong></td>
                <td>{{ $item['item_text'] }}</td>
                <td class="text-center">
                    <span class="maturity-text">Level {{ $item['maturity_level'] ?? '1' }}</span>
                </td>
                <td class="text-center">
                    <span class="status-badge status-{{ strtolower($item['status']) }}">
                        {{ $item['status'] }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('d F Y H:i') }} | PT TRIAS SENTOSA Tbk</p>
    </div>

</body>
</html>
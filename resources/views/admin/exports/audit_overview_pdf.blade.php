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
        
        /* Header dengan Warna Biru Tua Konsisten */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px solid #003366;
            margin-bottom: 10px;
        }
        .header-table td {
            vertical-align: middle;
            padding-bottom: 10px;
        }
        .logo-img {
            height: 55px;
        }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #003366;
            margin: 0;
        }
        .tagline {
            font-size: 11px;
            color: #666;
            font-weight: normal;
        }
        .cert-container {
            text-align: right;
        }
        .cert-logo {
            height: 35px;
            margin-left: 8px;
        }

        /* Tabel Alamat */
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
        }
        .address-title {
            font-weight: bold;
            color: #003366;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }

        /* Section Audit Overview */
        .audit-overview {
            margin: 20px 0;
            padding: 15px;
            background-color: #ffffff;
            border-radius: 5px;
            border: 1px solid #003366; 
        }
        .audit-title-section {
            color: #003366; 
            border-bottom: 2px solid #003366; 
            padding-bottom: 5px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 12px;
            margin-top: 0;
        }
        .audit-info-grid {
            width: 100%;
            font-size: 11px;
            border-collapse: collapse;
        }
        .audit-info-grid td {
            padding: 4px 0;
            vertical-align: top;
        }

        /* Tabel Data Audit */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .data-table th {
            background-color: #003366;
            font-weight: bold;
            color: #ffffff;
            font-size: 11px;
            text-align: center;
            border: 1px solid #003366;
            padding: 10px;
        }
        .data-table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            font-size: 11px;
            vertical-align: top;
        }

        /* Status Badges - Bahasa Indonesia */
        .status-badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
            text-transform: uppercase;
        }
        .status-yes { background: #dcfce7; color: #166534; } /* YA */
        .status-no { background: #fee2e2; color: #b91c1c; }  /* TIDAK */
        .status-na { background: #f3e8ff; color: #7e22ce; }  /* N/A */
        .status-unanswered { background: #e2e8f0; color: #4b5563; } /* BELUM DIJAWAB */
        
        .maturity-text {
            font-weight: bold;
            color: #003366;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
        }

        .team-member {
            margin-bottom: 3px;
            line-height: 1.3;
            font-size: 10.5px;
        }
        .team-member-name {
            font-weight: bold;
        }
        .team-member-detail {
            color: #555;
            margin-left: 5px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 60px;">
                <img width="40px"  src="{{ public_path('images/ts.jpg') }}">
            </td>
            <td>
                <div class="company-name">PT TRIAS SENTOSA Tbk</div>
                <div class="tagline">FLEXIBLE PACKAGING FILM MANUFACTURER</div>
            </td>
        </tr>
    </table>

 <div class="audit-overview">
    <h2 class="audit-title-section">INFORMASI AUDIT</h2>
    <table class="audit-info-grid" style="width: 100%; border-collapse: collapse; table-layout: fixed;">
        <tr>
            <td style="width: 100px; vertical-align: top;"><strong>Departemen</strong></td>
            <td style="width: 10px; vertical-align: top;">:</td>
            <td style="vertical-align: top;">{{ $audit->department->name ?? '-' }}</td>
            
            <td style="width: 100px; vertical-align: top;"><strong>Anggota Tim</strong></td>
            <td style="width: 10px; vertical-align: top;">:</td>
            <td style="vertical-align: top;">
                @if($teamMembers->count() > 0)
                    @foreach($teamMembers as $member)
                        <div style="margin-bottom: 4px;">
                            <span class="team-member-name" style="display: block; font-weight: bold;">{{ $member->name }}</span>
                            <span style="color: #666; font-size: 9px;">
                                (NIK: {{ $member->nik ?? 'N/A' }}, Dept: {{ $member->department ?? 'N/A' }})
                            </span>
                        </div>
                    @endforeach
                @else
                    -
                @endif
            </td>
        </tr>

        <tr>
            <td style="vertical-align: top;"><strong>Tanggal Audit</strong></td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top;">{{ \Carbon\Carbon::parse($audit->audit_date)->translatedFormat('d F Y') }}</td>
            
            <td style="vertical-align: top;"><strong>ID Laporan</strong></td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top; font-family: monospace; font-size: 9px; word-break: break-all;">
                {{ $audit->id }}
            </td>
        </tr>

        <tr>
            <td style="vertical-align: top;"><strong>Tipe Audit</strong></td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top;">
                @php
                    $typeLabels = [
                        'Regular' => 'Pemeriksaan Rutin (Terjadwal)',
                        'Special' => 'Pemeriksaan Khusus (Mendadak)',
                        'FollowUp' => 'Pemeriksaan Lanjutan (Follow Up)'
                    ];
                @endphp
                {{ $typeLabels[$audit->type] ?? $audit->type }}
            </td>
        </tr>
    </table>
</div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">Klausul</th>
                <th style="width: 57%;">Item Pemeriksaan</th>
                <th style="width: 15%;">Tingkat Kematangan</th>
                <th style="width: 20%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach(collect($detailedItems)->sortBy('sub_clause') as $item)
            <tr>
                <td style="text-align: center;"><strong>{{ $item['sub_clause'] }}</strong></td>
                <td>{{ $item['item_text'] }}</td>
<td style="text-align: center;">
                    <span class="level-badge">Level {{ $item['maturity_level'] }}</span>
                    <div class="level-desc">{{ $item['maturity_description'] }}</div>
                </td>
                <td style="text-align: center;">
                    @php
                        $statusMap = [
                            'yes' => ['label' => 'YA', 'class' => 'status-yes'],
                            'no' => ['label' => 'TIDAK', 'class' => 'status-no'],
                            'na' => ['label' => 'N/A', 'class' => 'status-na'],
                            'unanswered' => ['label' => 'BELUM DIJAWAB', 'class' => 'status-unanswered']
                        ];
                        $currStatus = strtolower($item['status']);
                        $statusLabel = $statusMap[$currStatus]['label'] ?? $item['status'];
                        $statusClass = $statusMap[$currStatus]['class'] ?? '';
                    @endphp
                    <span class="status-badge {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada {{ now()->format('d F Y H:i') }} | PT TRIAS SENTOSA Tbk</p>
    </div>

</body>
</html>
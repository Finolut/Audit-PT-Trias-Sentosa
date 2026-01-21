<!DOCTYPE html>
<html>
<head>
    <title>Laporan Audit #{{ $audit->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 40px; }
        .header { text-align: center; border-bottom: 2px solid #3b82f6; padding-bottom: 20px; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8fafc; }
        .status-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-yes { background: #dcfce7; color: #166534; }
        .status-no { background: #fee2e2; color: #b91c1c; }
        .status-partial { background: #ffedd5; color: #c2410c; }
        .status-na { background: #f3e8ff; color: #7e22ce; }
        .status-unanswered { background: #e2e8f0; color: #4b5563; }
    </style>
</head>
<body>
    <div class="header">
        <h1>QHSE AUDIT REPORT</h1>
        <p>Department: {{ $audit->department->name ?? '-' }} | Tanggal: {{ $audit->created_at->format('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Klausul</th>
                <th>Item</th>
                <th>Status</th>
                <th>Yes</th>
                <th>No</th>
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
                <td>{{ $item['yes_count'] }}</td>
                <td>{{ $item['no_count'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
@extends('layouts.app')

@section('content')
<div class="audit-body">
    <div class="audit-container">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="font-size: 28px; font-weight: bold; color: #1e293b; margin-bottom: 10px;">
                {{ $auditCode }}
            </h1>
            <p style="color: #64748b; font-size: 16px;">
                Auditor: <strong>{{ $auditorName }}</strong>
            </p>
        </div>

        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="font-size: 20px; color: #334155; margin-bottom: 15px;">
                Pilih Departemen yang Akan Diaudit
            </h2>
            <p style="color: #64748b;">
                Klik card departemen untuk memulai audit
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
            @foreach($departments as $dept)
                @php
                    $progress = $dept['progress'];
                    $status = $dept['status'];
                    
                    $cardBg = $status == 'completed' ? '#dcfce7' : 
                             ($status == 'in_progress' ? '#fef3c7' : '#f1f5f9');
                    $borderColor = $status == 'completed' ? '#22c55e' : 
                                  ($status == 'in_progress' ? '#f59e0b' : '#94a3b8');
                    $progressColor = $status == 'completed' ? '#22c55e' : '#3b82f6';
                @endphp

                <div style="
                    background: {{ $cardBg }};
                    border: 2px solid {{ $borderColor }};
                    border-radius: 12px;
                    padding: 25px;
                    cursor: pointer;
                    transition: all 0.3s;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                " onclick="selectDepartment('{{ $dept['id'] }}')">
                    
                    <div style="text-align: center;">
                        <div style="
                            width: 60px; 
                            height: 60px; 
                            background: white;
                            border: 3px solid {{ $borderColor }};
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin: 0 auto 15px;
                            font-size: 24px;
                            font-weight: bold;
                            color: {{ $borderColor }};
                        ">
                            {{ substr($dept['name'], 0, 1) }}
                        </div>

                        <h3 style="font-size: 18px; font-weight: bold; color: #1e293b; margin-bottom: 10px;">
                            {{ $dept['name'] }}
                        </h3>

                        @if($status == 'completed')
                            <div style="
                                background: #22c55e; 
                                color: white; 
                                padding: 5px 15px; 
                                border-radius: 20px; 
                                display: inline-block;
                                font-size: 12px;
                                font-weight: bold;
                                margin-bottom: 15px;
                            ">
                                ✅ Selesai
                            </div>
                        @elseif($status == 'in_progress')
                            <div style="
                                background: #f59e0b; 
                                color: white; 
                                padding: 5px 15px; 
                                border-radius: 20px; 
                                display: inline-block;
                                font-size: 12px;
                                font-weight: bold;
                                margin-bottom: 15px;
                            ">
                                📝 Dikerjakan
                            </div>
                        @else
                            <div style="
                                background: #94a3b8; 
                                color: white; 
                                padding: 5px 15px; 
                                border-radius: 20px; 
                                display: inline-block;
                                font-size: 12px;
                                font-weight: bold;
                                margin-bottom: 15px;
                            ">
                                ⏳ Belum Dimulai
                            </div>
                        @endif

                        <div style="margin-top: 15px; padding: 10px; background: white; border-radius: 8px;">
                            <div style="font-size: 12px; color: #64748b; margin-bottom: 5px;">
                                Progress: <strong>{{ $progress['completed'] }} / {{ $progress['total'] }} Klausul</strong>
                            </div>
                            <div style="width: 100%; background: #e2e8f0; border-radius: 4px; height: 8px; overflow: hidden;">
                                <div style="
                                    width: {{ $progress['percentage'] }}%; 
                                    background: {{ $progressColor }}; 
                                    height: 100%;
                                    border-radius: 4px;
                                    transition: width 0.5s;
                                "></div>
                            </div>
                            <div style="font-size: 11px; color: #64748b; text-align: right; margin-top: 3px;">
                                {{ $progress['percentage'] }}%
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="text-align: center; margin-top: 30px; padding: 20px; background: white; border-radius: 8px;">
            <p style="color: #64748b; margin-bottom: 15px;">
                <strong>{{ $departments->where('status', 'completed')->count() }}</strong> dari <strong>{{ $departments->count() }}</strong> departemen telah selesai
            </p>
            @if($departments->where('status', 'completed')->count() == $departments->count())
                <a href="{{ route('audit.finish') }}" style="
                    display: inline-block;
                    background: #22c55e;
                    color: white;
                    padding: 12px 30px;
                    border-radius: 8px;
                    text-decoration: none;
                    font-weight: bold;
                    font-size: 16px;
                ">
                    ✅ Selesai Semua Audit
                </a>
            @endif
        </div>
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
@endsection
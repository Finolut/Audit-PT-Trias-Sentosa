<!DOCTYPE html>
<html>
<head>
    <title>EMS Audit Report {{ $audit->year ?? date('Y') }}</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            margin: 0; 
            color: #333;
            line-height: 1.5;
            font-size: 10pt;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 3px solid #000;
        }
        .header-title {
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 5px 0;
        }

        /* Section Title */
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            padding-bottom: 4px;
            margin: 25px 0 12px 0;
            color: #000;
        }

        /* Detail Sections */
        .detail-row {
            margin-bottom: 8px;
            display: flex;
        }
        .detail-label {
            font-weight: bold;
            min-width: 150px;
            display: inline-block;
        }
        .detail-value {
            flex: 1;
            display: inline-block;
        }

        /* Audit Scope Formatting */
        .scope-list {
            margin-left: 20px;
            margin-top: 5px;
        }
        .scope-item {
            margin-bottom: 3px;
        }

        /* Findings Table */
        .findings-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 9pt;
            table-layout: fixed;
        }
        .findings-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: left;
            border: 1px solid #000;
            padding: 6px 4px;
            vertical-align: top;
        }
        .findings-table td {
            border: 1px solid #000;
            padding: 5px 4px;
            vertical-align: top;
            word-wrap: break-word;
        }
        .findings-table th:nth-child(1) { width: 10%; }
        .findings-table th:nth-child(2) { width: 22%; }
        .findings-table th:nth-child(3) { width: 10%; }
        .findings-table th:nth-child(4) { width: 15%; }
        .findings-table th:nth-child(5) { width: 28%; }
        .findings-table th:nth-child(6) { width: 15%; }

        .minor-nc {
            background-color: #fffacd;
            font-weight: bold;
        }
        .major-nc {
            background-color: #ffcccc;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 8pt;
            padding-top: 15px;
            border-top: 1px solid #000;
            line-height: 1.4;
            color: #555;
        }

        /* Page break */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="header-title">EMS AUDIT REPORT {{ $audit->year ?? date('Y') }}</div>
    </div>

    <!-- Company Details -->
    <div class="section-title">COMPANY DETAILS</div>
    <div class="company-details">
        <div class="detail-row">
            <span class="detail-label">Name:</span>
            <span class="detail-value">PT Trias Sentosa Tbk</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Address:</span>
            <span class="detail-value">Keboharan Km. 26, Krian, Sidoarjo, East Java</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Business type:</span>
            <span class="detail-value">Public Company (Tbk)</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">President Director:</span>
            <span class="detail-value">Hananto Indrakusuma</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Contact phone:</span>
            <span class="detail-value">031-8975825</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Licence number:</span>
            <span class="detail-value">8120003862018</span>
        </div>
    </div>

    <!-- Auditor Details -->
    <div class="section-title">AUDITOR DETAILS</div>
    <div class="auditor-details">
        <div class="detail-row">
            <span class="detail-label">Name:</span>
            <span class="detail-value">{{ $leadAuditor['name'] }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Position:</span>
            <span class="detail-value">{{ $leadAuditor['position'] }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Phone:</span>
            <span class="detail-value">{{ $leadAuditor['phone'] }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Address:</span>
            <span class="detail-value">{{ $leadAuditor['address'] }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Email:</span>
            <span class="detail-value">{{ $leadAuditor['email'] }}</span>
        </div>
        
        <!-- Team Members -->
        @if($teamMembers->count() > 0)
            <div class="detail-row" style="margin-top: 10px;">
                <span class="detail-label">Audit Team:</span>
                <span class="detail-value">
                    <div style="margin-left: 5px;">
                        @foreach($teamMembers as $member)
                            <div style="margin-bottom: 3px;">
                                • {{ $member->name }} 
                                @if($member->nik) (NIK: {{ $member->nik }}) @endif
                                @if($member->department) - {{ $member->department }} @endif
                                @if($member->role) [{{ $member->role }}] @endif
                            </div>
                        @endforeach
                    </div>
                </span>
            </div>
        @endif
    </div>

    <!-- Audit Details -->
    <div class="section-title">AUDIT DETAILS</div>
    <div class="audit-details">
        <div class="detail-row">
            <span class="detail-label">Audit type:</span>
            <span class="detail-value">
                @php
                    $typeMap = [
                        'first party' => 'Cross-functional internal audit (CFT)',
                        'follow up' => 'Follow-up Audit (Corrective Action Verification)',
                        'investigative' => 'Investigative Audit (Special/Incidental)',
                        'unannounced' => 'Unannounced Audit (Surprise Audit)'
                    ];
                @endphp
                {{ $typeMap[$audit->audit_type] ?? $audit->audit_type }}
            </span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Scope:</span>
            <span class="detail-value">
                @if(!empty($auditStandards))
                    <div><strong>Standards:</strong></div>
                    <div class="scope-list">
                        @foreach($auditStandards as $standard)
                            <div class="scope-item">• {{ $standard }}</div>
                        @endforeach
                    </div>
                @endif
                
                @if(!empty($auditScope))
                    <div style="margin-top: 8px;"><strong>Audited Areas:</strong></div>
                    <div class="scope-list">
                        @foreach($auditScope as $scope)
                            <div class="scope-item">• {{ $scope }}</div>
                        @endforeach
                    </div>
                @endif
            </span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Details of activities audited:</span>
            <span class="detail-value">
                CFT auditors were assigned by auditor lead to conduct audit, interview, and field observation in certain areas in the scope of EMS. 
                @if(!empty($audit->audit_objective))
                    <strong>Objective:</strong> {{ $audit->audit_objective }}
                @endif
                @if(!empty($methodology))
                    <div style="margin-top: 5px;"><strong>Methodology:</strong></div>
                    <div class="scope-list">
                        @foreach($methodology as $method)
                            <div class="scope-item">• {{ $method }}</div>
                        @endforeach
                    </div>
                @endif
                The audit lasted for {{ \Carbon\Carbon::parse($audit->audit_start_date)->diffInDays(\Carbon\Carbon::parse($audit->audit_end_date)) + 1 }} days starting from {{ \Carbon\Carbon::parse($audit->audit_start_date)->format('j F Y') }} up to {{ \Carbon\Carbon::parse($audit->audit_end_date)->format('j F Y') }}. Reports were then distributed to auditees including the managers and supervisors as well as management representative of EMS.
            </span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Details of audit program:</span>
            <span class="detail-value">
                This audit is a part of annual audit plan that is conducted prior to surveillance audit by certification body.
                @if($audit->audit_type === 'follow up')
                    This follow-up audit specifically verifies the effectiveness of corrective actions from previous audit findings.
                @endif
            </span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Start date:</span>
            <span class="detail-value">{{ \Carbon\Carbon::parse($audit->audit_start_date)->format('j F, Y') }}</span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">End date:</span>
            <span class="detail-value">{{ \Carbon\Carbon::parse($audit->audit_end_date)->format('j F Y') }}</span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Time spent (hours):</span>
            <span class="detail-value">{{ $timeSpent }} hours</span>
        </div>
    </div>

    <!-- Audit Results -->
    <div class="section-title">AUDIT RESULTS</div>
    <div class="audit-results">
        <div>The collective opinion of internal auditors are as follows:</div>
        <div style="margin-left: 20px; margin-top: 8px; line-height: 1.6;">
            <div>1. Most of areas audited comply with the environmental management system requirements.</div>
            <div>2. There are {{ count($findings) }} non-conformities found during the audit in the production areas and supporting departments.</div>
            <div>3. The EMS implementation program generally runs well, monitored, and evaluated regularly through internal audit activities.</div>
        </div>
    </div>

    <!-- Audit Summary Stats -->
    <div class="section-title">AUDIT SUMMARY STATS</div>
    <table class="findings-table">
        <thead>
            <tr>
                <th>Clause</th>
                <th>Activity audited</th>
                <th>Finding Status</th>
                <th>Evidence of the NC</th>
                <th>Action taken or proposed to be taken for each non-compliance</th>
                <th>Agreed completion date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($findings as $finding)
            <tr>
                <td>
                    {{ $finding['sub_clause'] }}<br>
                    <small>{{ Str::limit($finding['clause_text'], 40) }}</small>
                </td>
                <td>{{ $finding['item_text'] }}</td>
                <td>
                    @if($finding['finding_level'] == 'Minor NC')
                        <span class="minor-nc">{{ $finding['finding_level'] }}</span>
                    @elseif($finding['finding_level'] == 'Major NC')
                        <span class="major-nc">{{ $finding['finding_level'] }}</span>
                    @else
                        {{ $finding['finding_level'] }}
                    @endif
                </td>
                <td>{{ $finding['finding_note'] ?? '-' }}</td>
                <td>{{ $finding['action_plan'] ?? 'Corrective action to be determined by responsible department' }}</td>
                <td>
                    @if($finding['completion_date'])
                        {{ \Carbon\Carbon::parse($finding['completion_date'])->format('d-m-Y') }}
                    @else
                        {{ \Carbon\Carbon::parse($audit->audit_end_date)->addDays(30)->format('d-m-Y') }}
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 25px; font-style: italic; color: #777;">
                    No non-conformities found during this audit
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>This audit summary report has been consolidated and based on consent among the auditors.</p>
        <p>This document is intended for internal use only. Any act of exposing the content on this document to external parties without prior consent, is considered as unethical action.</p>
        <p style="margin-top: 8px; font-weight: bold;">PT TRIAS SENTOSA Tbk | EMS Internal Audit Department</p>
        <p>Report generated on {{ now()->format('d F Y') }} at {{ now()->format('H:i') }} WIB</p>
    </div>

</body>
</html>
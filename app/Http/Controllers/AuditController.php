<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuditController extends Controller
{
    private $mainClauses = [
        '4'  => ['4.1', '4.2', '4.3', '4.4'],
        '5'  => ['5.1', '5.2', '5.3'],
        '6'  => ['6.1.1', '6.1.2', '6.1.3', '6.1.4', '6.2.1', '6.2.2'],
        '7'  => ['7.1', '7.2', '7.3', '7.4'],
        '8'  => ['8.1', '8.2'],
        '9'  => ['9.1.1', '9.1.2', '9.2.1 & 9.2.2', '9.3'],
        '10' => ['10.1', '10.2', '10.3'],
    ];

    private $mainClauseTitles = [
        '4'  => 'Context of the organization',
        '5'  => 'Leadership',
        '6'  => 'Planning',
        '7'  => 'Support',
        '8'  => 'Operation',
        '9'  => 'Performance evaluation',
        '10' => 'Improvement',
    ];

    private $auditorsList = [
        // ... (daftar auditor tetap sama seperti sebelumnya)
        ['name' => 'Joko Nofrianto', 'nik' => '2477', 'dept' => 'BOPET'],
        ['name' => 'Laurentius Kelik Dwi Ananta', 'nik' => 'N/A', 'dept' => 'Q A'],
        // ... tambahkan semua sesuai data Anda
    ];

    public function setup()
    {
        $departments = DB::table('departments')->orderBy('name')->get();
        return view('test-form', [
            'departments' => $departments,
            'auditors' => $this->auditorsList
        ]);
    }

    public function startAudit(Request $request) 
    {
        $request->validate([
            'department_id'   => 'required|exists:departments,id',
            'auditor_name'    => 'required',
            'audit_type'      => 'required',
            'audit_objective' => 'required',
            'pic_name'        => 'required',
            'audit_date'      => 'required|date',
        ]);

        return DB::transaction(function () use ($request) {
            $selectedAuditor = collect($this->auditorsList)->firstWhere('name', $request->auditor_name);
            $auditorNik = $selectedAuditor['nik'] ?? $request->auditor_nik;
            $auditorDept = $selectedAuditor['dept'] ?? $request->auditor_department;

            $sessionId = (string) Str::uuid();
            $newAuditId = (string) Str::uuid();

            // Simpan ke audit_sessions
            DB::table('audit_sessions')->insert([
                'id'                 => $sessionId,
                'auditor_name'       => $request->auditor_name,
                'auditor_nik'        => $auditorNik,
                'auditor_department' => $auditorDept,
                'company_name'       => 'Indopoly',
                'audit_date'         => $request->audit_date,
                'created_at'         => now(),
            ]);

            // Simpan ke audits
            DB::table('audits')->insert([
                'id'               => $newAuditId,
                'audit_session_id' => $sessionId,
                'department_id'    => $request->department_id,
                'type'             => $request->audit_type, 
                'objective'        => $request->audit_objective,
                'scope'            => $request->audit_scope,
                'pic_auditee_name' => $request->pic_name,
                'pic_auditee_nik'  => $request->pic_nik ?? null,
                'status'           => 'IN_PROGRESS',
                'submitted_at'     => now(),
                'created_at'       => now(),
            ]);

            // === SIMPAN KE audit_responders ===
            // 1. Lead Auditor
            DB::table('audit_responders')->insert([
                'id' => (string) Str::uuid(),
                'audit_session_id' => $sessionId,
                'responder_name' => $request->auditor_name,
                'responder_role' => 'Lead Auditor',
                'responder_nik' => $auditorNik,
                'responder_department' => $auditorDept,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Tim Audit Tambahan
            $auditTeam = $request->input('audit_team', []);
            foreach ($auditTeam as $member) {
                if (!empty(trim($member['name']))) {
                    DB::table('audit_responders')->insert([
                        'id' => (string) Str::uuid(),
                        'audit_session_id' => $sessionId,
                        'responder_name' => trim($member['name']),
                        'responder_role' => $member['role'] ?? 'Member',
                        'responder_nik' => null,
                        'responder_department' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return redirect()->route('audit.menu', ['id' => $newAuditId])
                             ->with('success', 'Audit baru berhasil dibuat!');
        });
    }

    // ... (method menu(), show(), store(), clauseDetail() tetap sama seperti sebelumnya)
    
    public function menu($auditId)
    {
        $audit = DB::table('audits')->where('id', $auditId)->first();
        if(!$audit) abort(404);

        $session = DB::table('audit_sessions')->where('id', $audit->audit_session_id)->first();
        $deptName = DB::table('departments')->where('id', $audit->department_id)->value('name');

        $answeredSubCodes = DB::table('answers')
            ->join('items', 'answers.item_id', '=', 'items.id')
            ->join('clauses', 'items.clause_id', '=', 'clauses.id')
            ->where('answers.audit_id', $auditId)
            ->distinct()
            ->pluck('clauses.clause_code')
            ->toArray();

        $completedClauses = [];
        foreach ($this->mainClauses as $mainCode => $subCodes) {
            if (count(array_intersect($subCodes, $answeredSubCodes)) > 0) {
                $completedClauses[] = (string)$mainCode;
            }
        }

        return view('audit.menu', [
            'auditId'          => $auditId,
            'auditorName'      => $session->auditor_name,
            'deptName'         => $deptName,
            'mainClauses'      => array_keys($this->mainClauses), 
            'titles'           => $this->mainClauseTitles,
            'completedClauses' => $completedClauses
        ]);
    }

    public function show($auditId, $mainClause)
    {
        if (!array_key_exists($mainClause, $this->mainClauses)) abort(404);

        $audit = DB::table('audits')->where('id', $auditId)->first();
        if(!$audit) abort(404);

        $subCodes = $this->mainClauses[$mainClause];
        $clausesData = DB::table('clauses')->whereIn('clause_code', $subCodes)->get();

        $itemsRaw = DB::table('items')
            ->whereIn('clause_id', $clausesData->pluck('id'))
            ->orderBy('item_order')
            ->get();

        $idToCode = $clausesData->pluck('clause_code', 'id');
        $itemsGrouped = $itemsRaw->groupBy(fn($item) => $idToCode[$item->clause_id]);

        $session = DB::table('audit_sessions')->where('id', $audit->audit_session_id)->first();
        $existingNotes = DB::table('audit_questions')
            ->where('audit_id', $auditId)
            ->whereIn('clause_code', $subCodes)
            ->pluck('question_text', 'clause_code');

        $mainKeys = array_keys($this->mainClauses);
        $nextMain = $mainKeys[array_search($mainClause, $mainKeys) + 1] ?? null;

        return view('audit.questionnaire', [
            'auditId'        => $auditId,
            'currentMain'    => $mainClause,
            'subClauses'     => $subCodes,
            'clauseTitles'   => $clausesData->pluck('title', 'clause_code'),
            'nextMainClause' => $nextMain,
            'auditorName'    => $session->auditor_name,
            'targetDept'     => DB::table('departments')->where('id', $audit->department_id)->value('name'),
            'itemsGrouped'   => $itemsGrouped,
            'existingNotes'  => $existingNotes,
            'maturityLevels' => DB::table('maturity_levels')->orderBy('level_number')->get(),
            'responders'     => DB::table('audit_responders')->where('audit_session_id', $session->id)->get(),
        ]);
    }

    public function store(Request $request, $auditId, $mainClause)
    {
        $answers = $request->input('answers', []);
        $notes   = $request->input('audit_notes', []);

        DB::transaction(function () use ($answers, $notes, $auditId) {
            $auditRecord = DB::table('audits')->where('id', $auditId)->first();
            $departmentId = $auditRecord ? $auditRecord->department_id : null;

            foreach ($notes as $clauseCode => $text) {
                if (empty($text)) continue;
                DB::table('audit_questions')->updateOrInsert(
                    ['audit_id' => $auditId, 'clause_code' => $clauseCode],
                    [
                        'id'            => (string) Str::uuid(),
                        'department_id' => $departmentId,
                        'question_text' => $text,
                        'updated_at'    => now(),
                        'created_at'    => now()
                    ]
                );
            }

            foreach ($answers as $itemId => $people) {
                $yesCount = 0;
                $noCount  = 0;

                foreach ($people as $personName => $data) {
                    if (!empty($data['val'])) {
                        DB::table('answers')->updateOrInsert(
                            ['audit_id' => $auditId, 'item_id' => $itemId, 'auditor_name' => $personName],
                            ['id' => (string) Str::uuid(), 'answer' => $data['val'], 'answered_at' => now()]
                        );

                        if ($data['val'] === 'YES') $yesCount++;
                        if ($data['val'] === 'NO')  $noCount++;
                    }
                }

                $finalYes = ($yesCount > $noCount || ($yesCount > 0 && $yesCount == $noCount)) ? 1 : 0;
                $finalNo  = ($noCount > $yesCount || ($yesCount > 0 && $yesCount == $noCount)) ? 1 : 0;

                if ($yesCount > 0 || $noCount > 0) {
                    DB::table('answer_finals')->updateOrInsert(
                        ['audit_id' => $auditId, 'item_id' => $itemId],
                        [
                            'id'         => (string) Str::uuid(),
                            'yes_count'  => $yesCount,
                            'no_count'   => $noCount,
                            'final_yes'  => $finalYes,
                            'final_no'   => $finalNo,
                            'decided_at' => now(),
                        ]
                    );
                }
            }
        });

        $mainKeys = array_keys($this->mainClauses);
        $currentIndex = array_search($mainClause, $mainKeys);
        $nextMain = $mainKeys[$currentIndex + 1] ?? null;

        if ($nextMain) {
            return redirect()->route('audit.show', ['id' => $auditId, 'clause' => $nextMain])
                             ->with('success', "Clause {$mainClause} berhasil disimpan");
        }

        return redirect()->route('audit.menu', ['id' => $auditId])->with('success', 'Audit selesai 🎉');
    }

    public function clauseDetail($auditId, $mainClause)
    {
        if (!array_key_exists($mainClause, $this->mainClauses)) abort(404);

        $audit = DB::table('audits')->where('id', $auditId)->first();
        if(!$audit) abort(404);

        $subCodes = $this->mainClauses[$mainClause];
        $clausesData = DB::table('clauses')->whereIn('clause_code', $subCodes)->get();

        $itemsRaw = DB::table('items')
            ->leftJoin('answer_finals', function($join) use ($auditId) {
                $join->on('items.id', '=', 'answer_finals.item_id')
                     ->where('answer_finals.audit_id', '=', $auditId);
            })
            ->whereIn('items.clause_id', $clausesData->pluck('id'))
            ->select('items.*', 'answer_finals.yes_count', 'answer_finals.no_count', 'answer_finals.final_yes', 'answer_finals.final_no')
            ->orderBy('items.item_order')
            ->get();

        $idToCode = $clausesData->pluck('clause_code', 'id');
        $itemsGrouped = $itemsRaw->groupBy(fn($item) => $idToCode[$item->clause_id]);
        
        $totalYes  = $itemsRaw->where('final_yes', 1)->where('final_no', 0)->count();
        $totalNo   = $itemsRaw->where('final_no', 1)->where('final_yes', 0)->count();
        $totalNA   = $itemsRaw->whereNull('yes_count')->count();
        $totalDraw = $itemsRaw->where('final_yes', 1)->where('final_no', 1)->count();

        $chartData = $itemsRaw->map(function($item) {
            if($item->final_yes > $item->final_no) return 1;
            if($item->final_no > $item->final_yes) return -1;
            return 0;
        });

        $auditorNotes = DB::table('audit_questions')
            ->where('audit_id', $auditId)
            ->whereIn('clause_code', $subCodes)
            ->pluck('question_text', 'clause_code');

        return view('clause_detail', [
            'audit'           => $audit,
            'mainClause'      => $mainClause,
            'subCodes'        => $subCodes,
            'subClauseTitles' => $clausesData->pluck('title', 'clause_code'),
            'itemsGrouped'    => $itemsGrouped,
            'auditorNotes'    => $auditorNotes,
            'items'           => $itemsRaw,
            'totalYes'  => $totalYes,
            'totalNo'   => $totalNo,
            'totalNA'   => $totalNA,
            'totalDraw' => $totalDraw,
            'chartData' => $chartData
        ]);
    }
}
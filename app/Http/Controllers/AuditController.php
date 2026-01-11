<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuditController extends Controller
{
    // A. STRUKTUR GRUP KLAUSUL (Main Clause -> Sub Clauses)
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

    // Data Auditor Hardcoded (Sesuai Kebutuhan Form)
    private $auditorsList = [
        ['name' => 'Joko Nofrianto', 'nik' => '2477', 'dept' => 'BOPET'],
        ['name' => 'Laurentius Kelik Dwi Ananta', 'nik' => 'N/A', 'dept' => 'Q A'],
        ['name' => 'Edy Setiono', 'nik' => '1631', 'dept' => 'Q A-Product Stewardship'],
        ['name' => 'Mugi Slamet Priyanto', 'nik' => '943', 'dept' => 'Q A'],
        ['name' => 'Satyo Ady Prihatno', 'nik' => '1522', 'dept' => 'QA'],
        ['name' => 'Adhi Setyo Budi', 'nik' => '1074', 'dept' => 'SSSE'],
        ['name' => 'Rizal Pratama Firyanto', 'nik' => '3077', 'dept' => 'SSSE'],
        ['name' => 'Brahmanto Anggoro Laksono', 'nik' => '3186', 'dept' => 'SSSE'],
        ['name' => 'Eko Susanto', 'nik' => '1421', 'dept' => 'BOPET'],
        ['name' => 'Yusriel Yahya Wahyu Lisandi', 'nik' => '3127', 'dept' => 'F E'],
        ['name' => 'M. Agung Wibowo', 'nik' => '2086', 'dept' => 'MANAGEMENT SYSTEM'],
        ['name' => 'Adhek Widyo Purnama', 'nik' => '3138', 'dept' => 'I A'],
        ['name' => 'Benediktus Wahyu Kurniawan', 'nik' => '2701', 'dept' => 'I A'],
        ['name' => 'Nedwin Lembar Hermavian', 'nik' => '3054', 'dept' => 'I A'],
        ['name' => 'Teguh Imam Santosa', 'nik' => '1891', 'dept' => 'G S'],
        ['name' => 'Dodod Wahjudhi', 'nik' => '1456', 'dept' => 'G S'],
        ['name' => 'Dhanny', 'nik' => '2135', 'dept' => 'ENGINEERING'],
        ['name' => 'Kasiyono', 'nik' => '1240', 'dept' => 'ENGINEERING'],
        ['name' => 'M. Nadif', 'nik' => '1002', 'dept' => 'R&D'],
        ['name' => 'Eko Saifudin Yulianto', 'nik' => '944', 'dept' => 'LOGISTICS'],
        ['name' => 'Lisa Santoso', 'nik' => '2319', 'dept' => 'PURCHASING'],
        ['name' => 'Fenny Maria Veronica Lukman', 'nik' => '2910', 'dept' => 'PURCHASING'],
        ['name' => 'Melisa', 'nik' => '2833', 'dept' => 'PURCHASING'],
        ['name' => 'Catur Putra Prajoko', 'nik' => 'N/A', 'dept' => 'R&D'],
        ['name' => 'Sari Dewi Cahyaning Tyas', 'nik' => '2615', 'dept' => 'R&D'],
        ['name' => 'Ahmad Solihudin', 'nik' => '3055', 'dept' => 'TTA'],
        ['name' => 'Fahrisal Surya Kusuma', 'nik' => '2605', 'dept' => 'MFG SUPPORT'],
        ['name' => 'Suhadak', 'nik' => '2148', 'dept' => 'MFG SUPPORT'],
        ['name' => 'Gunaryanto Cahyo Edi', 'nik' => '1511', 'dept' => 'PPIC'],
        ['name' => 'Mohamad Taufik', 'nik' => '991', 'dept' => 'THERMAL'],
        ['name' => 'Nanang Sugianto', 'nik' => '850', 'dept' => 'BOPP'],
        ['name' => 'Solikan', 'nik' => '1207', 'dept' => 'PROJECT'],
    ];

    public function setup()
    {
        $departments = DB::table('departments')->orderBy('name')->get();
        return view('test-form', [
            'departments' => $departments,
            'auditors' => $this->auditorsList
        ]);
    }

    public function checkPendingAudit(Request $request)
    {
        $nik = $request->input('nik');
        $pendingAudit = DB::table('audits')
            ->join('audit_sessions', 'audits.audit_session_id', '=', 'audit_sessions.id')
            ->join('departments', 'audits.department_id', '=', 'departments.id')
            ->where('audit_sessions.auditor_nik', $nik)
            ->where('audits.status', 'IN_PROGRESS')
            ->select('audits.id as audit_id', 'departments.name as dept_name', 'audit_sessions.audit_date')
            ->orderBy('audits.created_at', 'desc')
            ->first();

        if ($pendingAudit) {
            return response()->json([
                'found' => true,
                'audit_id' => $pendingAudit->audit_id,
                'dept_name' => $pendingAudit->dept_name,
                'date' => $pendingAudit->audit_date,
                'resume_link' => route('audit.menu', ['id' => $pendingAudit->audit_id])
            ]);
        }
        return response()->json(['found' => false]);
    }

public function startAudit(Request $request) 
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'auditor_nik'   => 'required',
        ]);

        return DB::transaction(function () use ($request) {
            // 1. Cari data auditor
            $selectedAuditor = collect($this->auditorsList)->firstWhere('nik', $request->auditor_nik);
            
            // 2. Buat Session
            $sessionId = (string) Str::uuid();
            DB::table('audit_sessions')->insert([
                'id'                 => $sessionId,
                'auditor_name'       => $selectedAuditor['name'] ?? 'Unknown',
                'auditor_nik'        => $selectedAuditor['nik'] ?? $request->auditor_nik,
                'auditor_department' => $selectedAuditor['dept'] ?? '-',
                'company_name'       => 'Indopoly',
                'audit_date'         => now()->toDateString(),
                'created_at'         => now(),
            ]);

            // 3. LOGIKA BARU: Simpan Responder (Looping array dari form)
            $responders = $request->input('responders', []);
            foreach($responders as $resp) {
                // Pastikan nama tidak kosong
                if(!empty($resp['name'])) {
                    DB::table('audit_responders')->insert([
                        'id'                   => (string) Str::uuid(),
                        'audit_session_id'     => $sessionId,
                        'responder_name'       => $resp['name'],
                        'responder_department' => $resp['department'] ?? null,
                        'responder_nik'        => $resp['nik'] ?? null, // Pastikan di form ada input name="responders[x][nik]"
                        'created_at'           => now(),
                    ]);
                }
            }

            // 4. Buat Audit Record
            $newAuditId = (string) Str::uuid();
            DB::table('audits')->insert([
                'id'               => $newAuditId,
                'audit_session_id' => $sessionId,
                'department_id'    => $request->department_id,
                'status'           => 'IN_PROGRESS',
                'created_at'       => now(),
            ]);

            return redirect()->route('audit.menu', ['id' => $newAuditId]);
        });
    }

    public function menu($auditId)
    {
        $audit = DB::table('audits')->where('id', $auditId)->first();
        if(!$audit) abort(404);

        $session = DB::table('audit_sessions')->where('id', $audit->audit_session_id)->first();
        $deptName = DB::table('departments')->where('id', $audit->department_id)->value('name');

        return view('audit.menu', [
            'auditId'     => $auditId,
            'auditorName' => $session->auditor_name,
            'deptName'    => $deptName,
            'mainClauses' => array_keys($this->mainClauses), 
            'titles'      => $this->mainClauseTitles
        ]);
    }

    public function show($auditId, $mainClause)
    {
        // Validasi apakah mainClause (4, 5, dll) ada di daftar
        if (!array_key_exists($mainClause, $this->mainClauses)) abort(404);

        $audit = DB::table('audits')->where('id', $auditId)->first();
        if(!$audit) abort(404);

        $subCodes = $this->mainClauses[$mainClause];
        $clausesData = DB::table('clauses')->whereIn('clause_code', $subCodes)->get();

        // Ambil item pertanyaan yang dikelompokkan
        $itemsRaw = DB::table('items')
            ->whereIn('clause_id', $clausesData->pluck('id'))
            ->orderBy('item_order')
            ->get();

        // Map Clause ID ke Clause Code untuk grouping di View
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

        /* ===============================
           1. SIMPAN NOTES AUDIT
        =============================== */
        foreach ($notes as $clauseCode => $text) {
            DB::table('audit_questions')->updateOrInsert(
                [
                    'audit_id'    => $auditId,
                    'clause_code' => $clauseCode,
                ],
                [
                    'question_text' => $text,
                    'updated_at'    => now(),
                ]
            );
        }

        /* ===============================
           2. SIMPAN JAWABAN INDIVIDUAL
        =============================== */
        foreach ($answers as $itemId => $people) {
            foreach ($people as $personName => $data) {
                if (!empty($data['val'])) {
                    DB::table('answers')->updateOrInsert(
                        [
                            'audit_id'     => $auditId,
                            'item_id'      => $itemId,
                            'auditor_name' => $personName,
                        ],
                        [
                            'id'          => (string) Str::uuid(),
                            'answer'      => $data['val'],
                            'answered_at' => now(),
                        ]
                    );
                }
            }
        }

        /* ===============================
           3. HITUNG & SIMPAN FINAL RESULT
        =============================== */
        foreach ($answers as $itemId => $people) {

            $yesCount = 0;
            $noCount  = 0;

            foreach ($people as $data) {
                if (($data['val'] ?? '') === 'YES') $yesCount++;
                if (($data['val'] ?? '') === 'NO')  $noCount++;
            }

            // aturan final
            if ($yesCount === 0 && $noCount === 0) {
                $finalYes = 0;
                $finalNo  = 0;
            } elseif ($yesCount > $noCount) {
                $finalYes = 1;
                $finalNo  = 0;
            } elseif ($noCount > $yesCount) {
                $finalYes = 0;
                $finalNo  = 1;
            } else {
                $finalYes = 1;
                $finalNo  = 1;
            }

            DB::table('answer_finals')->updateOrInsert(
                [
                    'audit_id' => $auditId,
                    'item_id'  => $itemId,
                ],
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
    });

    /* ===============================
       4. REDIRECT KE CLAUSE BERIKUTNYA
    =============================== */

    $mainKeys = array_keys($this->mainClauses);
    $currentIndex = array_search($mainClause, $mainKeys);
    $nextMain = $mainKeys[$currentIndex + 1] ?? null;

    if ($nextMain) {
        return redirect()->route('audit.show', [
            'audit'      => $auditId,
            'mainClause' => $nextMain
        ])->with('success', "Clause {$mainClause} berhasil disimpan");
    }

    // clause terakhir
    return redirect()->route('audit.menu', $auditId)
        ->with('success', 'Audit selesai 🎉');
}


    public function clauseDetail($auditId, $mainClause)
    {
        // Validasi Main Clause
        if (!array_key_exists($mainClause, $this->mainClauses)) abort(404);

        $audit = DB::table('audits')->where('id', $auditId)->first();
        if(!$audit) abort(404);

        $subCodes = $this->mainClauses[$mainClause];
        $clausesData = DB::table('clauses')->whereIn('clause_code', $subCodes)->get();

        // QUERY KUNCI: Join Items dengan Answer_Finals
        // Kita gunakan LEFT JOIN agar item tetap muncul meski belum ada jawaban
        $itemsRaw = DB::table('items')
            ->leftJoin('answer_finals', function($join) use ($auditId) {
                $join->on('items.id', '=', 'answer_finals.item_id')
                     ->where('answer_finals.audit_id', '=', $auditId);
            })
            ->whereIn('items.clause_id', $clausesData->pluck('id'))
            ->select(
                'items.*', 
                // Ambil kolom dari answer_finals
                'answer_finals.yes_count', 
                'answer_finals.no_count', 
                'answer_finals.final_yes', 
                'answer_finals.final_no'
            )
            ->orderBy('items.item_order')
            ->get();

        // Grouping Item berdasarkan Clause Code
        $idToCode = $clausesData->pluck('clause_code', 'id');
        $itemsGrouped = $itemsRaw->groupBy(fn($item) => $idToCode[$item->clause_id]);

        // Ambil data pendukung lain untuk Chart & Header
        $session = DB::table('audit_sessions')->where('id', $audit->audit_session_id)->first();
        
        // Hitung Total Result untuk Chart Atas
        $totalYes  = $itemsRaw->where('final_yes', '>', 'final_no')->count(); // Logic sederhana, sesuaikan jika ada threshold
        $totalNo   = $itemsRaw->where('final_no', '>', 'final_yes')->count();
        $totalNA   = $itemsRaw->where('yes_count', 0)->where('no_count', 0)->count(); // Asumsi N/A jika counts 0
        $totalDraw = $itemsRaw->count() - ($totalYes + $totalNo + $totalNA); // Partial/Draw

        // Data Chart Line (1 = Yes, -1 = No, 0 = Partial/Empty)
        $chartData = $itemsRaw->map(function($item) {
            if($item->final_yes > $item->final_no) return 1;
            if($item->final_no > $item->final_yes) return -1;
            return 0;
        });

        // Ambil Catatan Auditor
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
            'items'           => $itemsRaw, // Untuk chart loop
            // Data Chart
            'totalYes'  => $totalYes,
            'totalNo'   => $totalNo,
            'totalNA'   => $totalNA,
            'totalDraw' => $totalDraw,
            'chartData' => $chartData
        ]);
    }
}
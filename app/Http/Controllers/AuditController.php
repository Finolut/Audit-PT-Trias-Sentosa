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
        '7'  => ['7.1', '7.2', '7.3', '7.4', '7.5.1', '7.5.2', '7.5.3'],
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
                        'responder_department' =>  $member['department'] ?? null,
                        'created_at' => now(),
                    ]);
                }
            }

  if (!empty($responders)) {
            DB::table('audit_responders')->insert($responders); // ✅ Batch insert!
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
        $departmentId = $auditRecord?->department_id;

        // === 1. BATCH UPSERT NOTES ===
        $noteRecords = [];
        foreach ($notes as $clauseCode => $text) {
            if (empty(trim($text))) continue;
            $noteRecords[] = [
                'id'             => Str::uuid()->toString(),
                'audit_id'       => $auditId,
                'clause_code'    => $clauseCode,
                'department_id'  => $departmentId,
                'question_text'  => $text,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        if (!empty($noteRecords)) {
            DB::table('audit_questions')->upsert(
                $noteRecords,
                ['audit_id', 'clause_code'], // unique constraint columns
                ['question_text', 'updated_at'] // columns to update on conflict
            );
        }

        // === 2. KUMPULKAN DATA JAWABAN & FINAL ===
        $answerRecords = [];
        $finalRecords = [];

        foreach ($answers as $itemId => $people) {
            $yesCount = $noCount = $naCount = 0;
            $hasAnswer = false;

            foreach ($people as $personName => $data) {
                if (!empty($data['val'])) {
                    $hasAnswer = true;
                    $answerRecords[] = [
                        'id'           => Str::uuid()->toString(),
                        'audit_id'     => $auditId,
                        'item_id'      => $itemId,
                        'auditor_name' => $personName,
                        'answer'       => $data['val'],
                        'answered_at'  => now(),
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ];

                    match ($data['val']) {
                        'YES' => $yesCount++,
                        'NO'  => $noCount++,
                        'N/A' => $naCount++,
                    };
                }
            }

            if ($hasAnswer) {
                // Logika final: YES menang jika >= NO (termasuk seri)
                $finalYes = ($yesCount >= $noCount && $yesCount > 0) ? 1 : 0;
                $finalNo  = ($noCount > $yesCount) ? 1 : 0;

                $finalRecords[] = [
                    'id'         => Str::uuid()->toString(),
                    'audit_id'   => $auditId,
                    'item_id'    => $itemId,
                    'yes_count'  => $yesCount,
                    'no_count'   => $noCount,
                    'final_yes'  => $finalYes,
                    'final_no'   => $finalNo,
                    'decided_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // === 3. BATCH UPSERT ANSWERS ===
        if (!empty($answerRecords)) {
            DB::table('answers')->upsert(
                $answerRecords,
                ['audit_id', 'item_id', 'auditor_name'],
                ['answer', 'answered_at', 'updated_at']
            );
        }

        // === 4. BATCH UPSERT FINAL ANSWERS ===
        if (!empty($finalRecords)) {
            DB::table('answer_finals')->upsert(
                $finalRecords,
                ['audit_id', 'item_id'],
                ['yes_count', 'no_count', 'final_yes', 'final_no', 'decided_at', 'updated_at']
            );
        }
    });

    // Redirect logic tetap sama
    $mainKeys = array_keys($this->mainClauses);
    $currentIndex = array_search($mainClause, $mainKeys);
    $nextMain = $mainKeys[$currentIndex + 1] ?? null;

    if ($nextMain) {
        return redirect()->route('audit.show', ['id' => $auditId, 'clause' => $nextMain])
                         ->with('success', "Clause {$mainClause} berhasil disimpan");
    }

    return redirect()->route('audit.menu', ['id' => $auditId])
                     ->with('success', 'Audit selesai 🎉');
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
public function showDashboard($auditId)
{
    $audit = DB::table('audits')->where('id', $auditId)->first();
    if (!$audit) abort(404);

    // 1. Ambil SEMUA soal yang seharusnya dijawab
    $allItems = DB::table('items')
        ->join('clauses', 'items.clause_id', '=', 'clauses.id')
        ->select('items.id as item_id', 'clauses.clause_code')
        ->get();

    // 2. Ambil JAWABAN yang sudah masuk
    $finalAnswers = DB::table('answer_finals')
        ->where('audit_id', $auditId)
        ->get()
        ->keyBy('item_id');

    $mainStats = [];
    $detailedStats = [];

    // Inisialisasi awal berdasarkan daftar klausul statis Anda
    foreach ($this->mainClauses as $mCode => $subs) {
        $mainStats[$mCode] = ['yes' => 0, 'no' => 0, 'partial' => 0, 'na' => 0, 'unanswered' => 0];
        foreach ($subs as $sCode) {
            $detailedStats[$sCode] = ['yes' => 0, 'no' => 0, 'partial' => 0, 'na' => 0, 'unanswered' => 0];
        }
    }

    foreach ($allItems as $item) {
        $mainKey = explode('.', $item->clause_code)[0];
        $detailKey = $item->clause_code;

        if (!isset($mainStats[$mainKey])) continue;

        // CEK APAKAH SUDAH ADA JAWABAN?
        if ($finalAnswers->has($item->item_id)) {
            $ans = $finalAnswers->get($item->item_id);

            // Logic N/A: Jika record ada, tapi yes=0 dan no=0 (artinya dipilih N/A saat voting)
            if ($ans->final_yes == 0 && $ans->final_no == 0) {
                $mainStats[$mainKey]['na']++;
                $detailedStats[$detailKey]['na']++;
            } 
            // Logic Partial (Draw)
            elseif ($ans->final_yes == 1 && $ans->final_no == 1) {
                $mainStats[$mainKey]['partial']++;
                $detailedStats[$detailKey]['partial']++;
            }
            // Logic YES
            elseif ($ans->final_yes == 1) {
                $mainStats[$mainKey]['yes']++;
                $detailedStats[$detailKey]['yes']++;
            }
            // Logic NO
            else {
                $mainStats[$mainKey]['no']++;
                $detailedStats[$detailKey]['no']++;
            }
        } else {
            // JIKA TIDAK ADA DI TABEL answer_finals = BELUM DIISI (ABU-ABU)
            $mainStats[$mainKey]['unanswered']++;
            $detailedStats[$detailKey]['unanswered']++;
        }
    }

    return view('audit.overview', [ // Sesuaikan nama file blade anda
        'audit' => $audit,
        'mainStats' => $mainStats,
        'detailedStats' => $detailedStats,
        'mainClauses' => $this->mainClauses,
        'titles' => $this->mainClauseTitles
    ]);
}

public function finalSubmit(Request $request, $auditId)
{
    // Validasi: pastikan semua klausul sudah diisi
    $allClauses = array_keys($this->mainClauses);
    $answeredSubCodes = DB::table('answers')
        ->join('items', 'answers.item_id', '=', 'items.id')
        ->join('clauses', 'items.clause_id', '=', 'clauses.id')
        ->where('answers.audit_id', $auditId)
        ->distinct()
        ->pluck('clauses.clause_code')
        ->toArray();

    $completedMain = [];
    foreach ($this->mainClauses as $main => $subs) {
        if (count(array_intersect($subs, $answeredSubCodes)) > 0) {
            $completedMain[] = (string)$main;
        }
    }

    if (count($completedMain) !== count($allClauses)) {
        return back()->withErrors(['Tidak semua klausul telah diisi.']);
    }

// Update status audit ke "COMPLETED"
    DB::table('audits')
        ->where('id', $auditId)
        ->update([
            'status' => 'COMPLETED', // Nilai ini harus sesuai dengan pengecekan di Blade
            'submitted_at' => now(),
            'updated_at' => now()
        ]);

   // 1. Ambil semua ID item yang seharusnya dijawab (dari Clause 4 - 10)
    $allRequiredItemIds = DB::table('items')
        ->join('clauses', 'items.clause_id', '=', 'clauses.id')
        ->pluck('items.id')
        ->toArray();

    // 2. Ambil ID item yang sudah benar-benar dijawab untuk audit ini
    $answeredItemIds = DB::table('answer_finals')
        ->where('audit_id', $auditId)
        ->pluck('item_id')
        ->toArray();

    // 3. Cross-check: bandingkan jumlahnya
    $missingItems = array_diff($allRequiredItemIds, $answeredItemIds);

    if (count($missingItems) > 0) {
        // Jika ada yang kosong, kembalikan ke menu dengan pesan error
        return redirect()->route('audit.menu', $auditId)
            ->with('error', 'Laporan tidak bisa dikirim. Masih ada pertanyaan yang belum dijawab. Silakan periksa kembali setiap Clause.');
    }

    // 4. Jika sudah lengkap, Update status menjadi COMPLETED
    DB::table('audits')
        ->where('id', $auditId)
        ->update([
            'status' => 'COMPLETED',
            'submitted_at' => now(),
            'updated_at' => now()
        ]);

    // 5. Redirect ke halaman "Terima Kasih"
    return redirect()->route('audit.thanks');
}

}
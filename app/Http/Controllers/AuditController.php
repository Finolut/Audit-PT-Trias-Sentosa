<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

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
    ['id' => '2477', 'name' => 'Joko Nofrianto', 'nik' => '2477', 'dept' => 'BOPET'],
    ['id' => 'NA-1', 'name' => 'Laurentius Kelik Dwi Ananta', 'nik' => 'N/A', 'dept' => 'Q A'],
    ['id' => '1631', 'name' => 'Edy Setiono', 'nik' => '1631', 'dept' => 'Q A-Product Stewardship'],
    ['id' => '943', 'name' => 'Mugi Slamet Priyanto', 'nik' => '943', 'dept' => 'Q A'],
    ['id' => '1522', 'name' => 'Satyo Ady Prihatno', 'nik' => '1522', 'dept' => 'QA'],
    ['id' => '1074', 'name' => 'Adhi Setyo Budi', 'nik' => '1074', 'dept' => 'SSSE'],
    ['id' => '3077', 'name' => 'Rizal Pratama Firyanto', 'nik' => '3077', 'dept' => 'SSSE'],
    ['id' => '3186', 'name' => 'Brahmanto Anggoro Laksono', 'nik' => '3186', 'dept' => 'SSSE'],
    ['id' => '1421', 'name' => 'Eko Susanto', 'nik' => '1421', 'dept' => 'BOPET'],
    ['id' => '3127', 'name' => 'Yusriel Yahya Wahyu Lisandi', 'nik' => '3127', 'dept' => 'F E'],
    ['id' => '2086', 'name' => 'M. Agung Wibowo', 'nik' => '2086', 'dept' => 'MANAGEMENT SYSTEM'],
    ['id' => '3138', 'name' => 'Adhek Widyo Purnama', 'nik' => '3138', 'dept' => 'I A'],
    ['id' => '2701', 'name' => 'Benediktus Wahyu Kurniawan', 'nik' => '2701', 'dept' => 'I A'],
    ['id' => '3054', 'name' => 'Nedwin Lembar Hermavian', 'nik' => '3054', 'dept' => 'I A'],
    ['id' => '1891', 'name' => 'Teguh Imam Santosa', 'nik' => '1891', 'dept' => 'G S'],
    ['id' => '1456', 'name' => 'Dodod Wahjudhi', 'nik' => '1456', 'dept' => 'G S'],
    ['id' => '2135', 'name' => 'Dhanny', 'nik' => '2135', 'dept' => 'ENGINEERING'],
    ['id' => '1240', 'name' => 'Kasiyono', 'nik' => '1240', 'dept' => 'ENGINEERING'],
    ['id' => '1002', 'name' => 'M. Nadif', 'nik' => '1002', 'dept' => 'R&D'],
    ['id' => '944', 'name' => 'Eko Saifudin Yulianto', 'nik' => '944', 'dept' => 'LOGISTICS'],
    ['id' => '2319', 'name' => 'Lisa Santoso', 'nik' => '2319', 'dept' => 'PURCHASING'],
    ['id' => '2910', 'name' => 'Fenny Maria Veronica Lukman', 'nik' => '2910', 'dept' => 'PURCHASING'],
    ['id' => '2833', 'name' => 'Melisa', 'nik' => '2833', 'dept' => 'PURCHASING'],
    ['id' => 'NA-2', 'name' => 'Catur Putra Prajoko', 'nik' => 'N/A', 'dept' => 'R&D'],
    ['id' => '2615', 'name' => 'Sari Dewi Cahyaning Tyas', 'nik' => '2615', 'dept' => 'R&D'],
    ['id' => '3055', 'name' => 'Ahmad Solihudin', 'nik' => '3055', 'dept' => 'TTA'],
    ['id' => '2605', 'name' => 'Fahrisal Surya Kusuma', 'nik' => '2605', 'dept' => 'MFG SUPPORT'],
    ['id' => '2148', 'name' => 'Suhadak', 'nik' => '2148', 'dept' => 'MFG SUPPORT'],
    ['id' => '1511', 'name' => 'Gunaryanto Cahyo Edi', 'nik' => '1511', 'dept' => 'PPIC'],
    ['id' => '991', 'name' => 'Mohamad Taufik', 'nik' => '991', 'dept' => 'THERMAL'],
    ['id' => '850', 'name' => 'Nanang Sugianto', 'nik' => '850', 'dept' => 'BOPP'],
    ['id' => '1207', 'name' => 'Solikan', 'nik' => '1207', 'dept' => 'PROJECT'],
];

    public function setup()
    {
        $departments = DB::table('departments')->orderBy('name')->get();
        return view('test-form', [
            'departments' => $departments,
           'auditorsList' => $this->auditorsList
        ]);
    }

// 1. UPDATE startAudit (Generate Token saat mulai)
public function startAudit(Request $request)
{
    // 1. Validasi Input (Sesuaikan dengan field yang ada di HTML Form)
    $request->validate([
        'lead_auditor_id'    => 'required|string',
        'lead_auditor_email' => 'nullable|email',
        'auditee_dept_ids'   => 'required|array|min:1',
        'auditee_dept_ids.*' => 'exists:departments,id',

        'audit_code'         => 'required|string',
        'audit_type'         => 'required|string',
        'audit_objective'    => 'required|string',
        'audit_scope'        => 'required|array|min:1',
        'audit_standards'    => 'required|array|min:1',
        'methodology'        => 'required|array|min:1',

        'audit_start_date'   => 'required|date',
        'audit_end_date'     => 'required|date|after_or_equal:audit_start_date',
        
        // 'audit_date' kita hapus dari validasi request karena kita generate otomatis di bawah
        // atau kita ambil dari hidden field jika Anda menambahkannya.
        // Untuk aman, kita pakai now() atau ambil dari start_date.
        
        'audit_team'         => 'nullable|array',
    ]);

    return DB::transaction(function () use ($request) {
        $selectedAuditor = collect($this->auditorsList)->firstWhere('nik', $request->lead_auditor_id);
        $auditorName = $selectedAuditor['name'] ?? 'Unknown';
        $auditorNik  = $selectedAuditor['nik'] ?? 'N/A';
        $auditorDept = $selectedAuditor['dept'] ?? 'N/A';

        $sessionId = (string) Str::uuid();
        $newAuditId = (string) Str::uuid();

        // Generate token unik
        do {
            $tokenRaw = strtoupper(Str::random(3) . '-' . Str::random(3));
        } while (DB::table('audit_sessions')->where('resume_token', $tokenRaw)->exists());

        // Simpan sesi audit
        DB::table('audit_sessions')->insert([
            'id'                      => $sessionId,
            'auditor_name'            => $auditorName,
            'auditor_nik'             => $auditorNik,
            'auditor_department'      => $auditorDept,
            'auditor_email'           => $request->lead_auditor_email,
            'audit_date'              => $request->audit_start_date, // Pakai start date sebagai tanggal sesi
            'resume_token'            => $tokenRaw,
            'resume_token_expires_at' => now()->addDays(7),
            'last_activity_at'        => now(),
            'created_at'              => now(),
        ]);

 DB::table('audits')->insert([
    'id'               => $newAuditId,
    'audit_session_id' => $sessionId,

    // MULTI DEPARTEMEN (FINAL)
    'department_ids'   => json_encode($request->auditee_dept_ids),

    'audit_code'       => $request->audit_code,
    'status'           => 'IN_PROGRESS',
    'type'             => $request->audit_type,
    'objective'        => $request->audit_objective,

    'scope'            => json_encode($request->audit_scope),
    'standards'        => json_encode($request->audit_standards),
    'methodology'      => json_encode($request->methodology),

    // RENTANG TANGGAL (BUKAN JAM)
    'audit_start_date' => $request->audit_start_date,
    'audit_end_date'   => $request->audit_end_date,

    'created_at'       => now(),
    'updated_at'       => now(),
]);


        // Simpan responders (Lead Auditor + Tim)
        $responders = [
            [
                'id'                   => (string) Str::uuid(),
                'audit_session_id'     => $sessionId,
                'responder_name'       => $auditorName,
                'responder_role'       => 'Lead Auditor',
                'responder_nik'        => $auditorNik,
                'responder_department' => $auditorDept,
                'created_at'           => now(),
            ]
        ];

        if ($request->has('audit_team')) {
            foreach ($request->audit_team as $member) {
                if (!empty($member['name'])) {
                    $responders[] = [
                        'id'                   => (string) Str::uuid(),
                        'audit_session_id'     => $sessionId,
                        'responder_name'       => $member['name'],
                        'responder_role'       => $member['role'] ?? 'Member',
                        'responder_nik'        => $member['nik'] ?? null,
                        'responder_department' => $member['department'] ?? null,
                        'created_at'           => now(),
                    ];
                }
            }
        }

        DB::table('audit_responders')->insert($responders);

        // PERBAIKAN: Redirect ke route audit.menu sesuai permintaan
        return redirect()->route('audit.select_department', ['id' => $newAuditId])
    ->with('success', "Audit dimulai! TOKEN RESUME ANDA: <strong>{$tokenRaw}</strong>.");
    });
}

    // ----------------------------------------------------------------------
    // 2. Tampilkan Form Input Token Resume
    // ----------------------------------------------------------------------
    public function showResumePage()
    {
        return view('audit.resume_form');
    }

    // ----------------------------------------------------------------------
    // 3. Validasi Token & Tampilkan Decision Gate
    // ----------------------------------------------------------------------
    public function validateResumeToken(Request $request)
    {
        $request->validate(['resume_token' => 'required|string']);
        $token = strtoupper(trim($request->resume_token));

        $session = DB::table('audit_sessions')
            ->where('resume_token', $token)
            ->first();

        if (!$session) {
            return back()->withErrors(['resume_token' => 'Token tidak valid.']);
        }

        if ($session->resume_token_expires_at && Carbon::parse($session->resume_token_expires_at)->isPast()) {
            return back()->withErrors(['resume_token' => 'Token sudah kadaluarsa. Silakan buat audit baru.']);
        }

        $audit = DB::table('audits')
            ->where('audit_session_id', $session->id)
            ->whereIn('status', ['DRAFT', 'IN_PROGRESS'])
            ->orderByDesc('created_at')
            ->first();

        if (!$audit) {
            return back()->withErrors(['resume_token' => 'Tidak ada audit aktif untuk token ini (mungkin sudah Selesai atau Dibatalkan).']);
        }

        $auditeeDept = DB::table('departments')->where('id', $audit->department_id)->value('name');

        return view('audit.resume_decision', [
            'token'         => $token,
            'auditorName'   => $session->auditor_name,
            'auditeeDept'   => $auditeeDept,
            'auditDate'     => Carbon::parse($session->audit_date)->format('d M Y'),
            'lastActivity'  => $session->last_activity_at ? Carbon::parse($session->last_activity_at)->diffForHumans() : '-',
            'auditId'       => $audit->id,
        ]);
    }

    // ----------------------------------------------------------------------
    // 4. Tangani Keputusan: Lanjutkan atau Batalkan Audit
    // ----------------------------------------------------------------------
    public function handleResumeDecision(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'audit_id' => 'required',
            'action'   => 'required|in:continue,abandon',
        ]);

        $auditId = $request->audit_id;

        if ($request->action === 'continue') {
            // Update last activity
            $audit = DB::table('audits')->where('id', $auditId)->first();
            if (!$audit) {
                return redirect()->route('audit.resume.form')->withErrors(['Audit tidak ditemukan.']);
            }

            DB::table('audit_sessions')
                ->where('id', $audit->audit_session_id)
                ->update(['last_activity_at' => now()]);

            return redirect()->route('audit.menu', ['id' => $auditId])
                ->with('success', 'Sesi dipulihkan. Silakan lanjutkan audit.');

        } else {
            // Abandon: batalkan audit & nonaktifkan token
            DB::transaction(function () use ($auditId, $request) {
                DB::table('audits')
                    ->where('id', $auditId)
                    ->update(['status' => 'ABANDONED', 'updated_at' => now()]);

                DB::table('audit_sessions')
                    ->where('resume_token', $request->token)
                    ->update(['resume_token_expires_at' => now()]);
            });

            return redirect()->route('audit.create')
                ->with('info', 'Audit sebelumnya telah dibatalkan. Silakan mulai audit baru.');
        }
    }

public function createAudit() 
{
    // 1. Ambil data departemen (id akan berisi UUID dari DB)
    $departments = DB::table('departments')->select('id', 'name')->orderBy('name')->get();
    
    // 2. Kirim ke view dengan nama 'auditorsList'
    return view('test-form', [
        'departments' => $departments,
        'auditorsList' => $this->auditorsList // Diambil dari private property di atas
    ]);
}
    
public function menu($auditId)
{
    $audit = DB::table('audits')->where('id', $auditId)->first();
    if (!$audit) abort(404);

    // CEK ACTIVE DEPARTMENT DARI SESSION
    $activeDeptId = session('active_department_id');
    
    // Jika belum ada active department, redirect ke pemilihan departemen
    if (!$activeDeptId) {
        return redirect()->route('audit.select_department', ['id' => $auditId]);
    }

    $session = DB::table('audit_sessions')->where('id', $audit->audit_session_id)->first();
    
    // Ambil nama departemen aktif
    $departmentName = DB::table('departments')->where('id', $activeDeptId)->value('name');

    // Hitung progress untuk departemen aktif saja
    $clauseProgress = [];
    $allFinished = true;

    foreach ($this->mainClauses as $mainCode => $subCodes) {
        // Hitung total item soal untuk klausul utama ini
        $totalItems = DB::table('items')
            ->join('clauses', 'items.clause_id', '=', 'clauses.id')
            ->whereIn('clauses.clause_code', $subCodes)
            ->count();

        // Hitung berapa yang sudah dijawab untuk departemen aktif
        $answeredItems = DB::table('answers')
            ->join('items', 'answers.item_id', '=', 'items.id')
            ->join('clauses', 'items.clause_id', '=', 'clauses.id')
            ->where('answers.audit_id', $auditId)
            ->where('answers.department_id', $activeDeptId) // Filter per departemen
            ->whereIn('clauses.clause_code', $subCodes)
            ->count();

        $percentage = ($totalItems > 0) ? round(($answeredItems / $totalItems) * 100) : 0;
        
        // Cek jika ada yang belum 100%
        if ($percentage < 100) {
            $allFinished = false;
        }

        $clauseProgress[$mainCode] = [
            'percentage' => $percentage,
            'count'      => $answeredItems,
            'total'      => $totalItems
        ];
    }

    // Update status jika benar-benar sudah selesai semua
    if ($allFinished && count($this->mainClauses) > 0) {
        DB::table('audits')->where('id', $auditId)->update([
            'status' => 'COMPLETE',
            'updated_at' => now()
        ]);
    }

    // Tampilkan tombol kembali jika semua sudah selesai
    $showReturnButton = $allFinished;

    return view('audit.menu', [
        'auditId'          => $auditId,
        'auditorName'      => $session->auditor_name,
        'deptName'         => $departmentName,
        'mainClauses'      => array_keys($this->mainClauses), 
        'titles'           => $this->mainClauseTitles,
        'clauseProgress'   => $clauseProgress,
        'allFinished'      => $allFinished,
        'showReturnButton' => $showReturnButton,
        'activeDepartmentId' => $activeDeptId,
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

    // --- TAMBAHKAN LOGIKA INI ---
    // Mengambil jawaban yang sudah tersimpan untuk audit ini
    $existingAnswers = [];
    $rawAnswers = DB::table('answers')
        ->where('audit_id', $auditId)
        ->get();

    foreach ($rawAnswers as $ans) {
        // Format: existingAnswers[item_id][nama_auditor] = 'YES'/'NO'/'N/A'
        $existingAnswers[$ans->item_id][$ans->auditor_name] = $ans->answer;
    }
    // ----------------------------

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
        'existingAnswers'=> $existingAnswers, // Kirim ke Blade
    ]);
}

public function store(Request $request, $auditId, $mainClause)
{
    $answers = $request->input('answers', []);
    $notes   = $request->input('audit_notes', []);

    // AMBIL DEPARTMENT ID DARI SESSION
    $activeDeptId = session('active_department_id');
    
    if (!$activeDeptId) {
        return redirect()->route('audit.select_department', ['id' => $auditId])
            ->withErrors(['Pilih departemen terlebih dahulu.']);
    }

    DB::transaction(function () use ($answers, $notes, $auditId, $activeDeptId) {
        // 1. BATCH UPSERT NOTES (dengan department_id)
        $noteRecords = [];
        foreach ($notes as $clauseCode => $text) {
            if (empty(trim($text))) continue;
            $noteRecords[] = [
                'id'             => (string) Str::uuid(),
                'audit_id'       => $auditId,
                'clause_code'    => $clauseCode,
                'department_id'  => $activeDeptId, // Tambahkan department_id
                'question_text'  => $text,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        if (!empty($noteRecords)) {
            DB::table('audit_questions')->upsert(
                $noteRecords, 
                ['audit_id', 'clause_code', 'department_id'], // Unique key include department
                ['question_text', 'updated_at']
            );
        }

        // 2. KUMPULKAN DATA JAWABAN (dengan department_id)
        $answerRecords = [];
        $finalRecords = [];

        foreach ($answers as $itemId => $people) {
            $yesCount = $noCount = $naCount = 0;
            $hasAnswer = false;

            foreach ($people as $personName => $data) {
                if (!empty($data['val'])) {
                    $hasAnswer = true;
                    $answerRecords[] = [
                        'id'           => (string) Str::uuid(),
                        'audit_id'     => $auditId,
                        'item_id'      => $itemId,
                        'auditor_name' => $personName,
                        'department_id' => $activeDeptId, // Tambahkan department_id
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
                $finalYes = ($yesCount >= $noCount && $yesCount > 0) ? 1 : 0;
                $finalNo  = ($noCount > $yesCount) ? 1 : 0;

                $finalRecords[] = [
                    'id'         => (string) Str::uuid(),
                    'audit_id'   => $auditId,
                    'item_id'    => $itemId,
                    'department_id' => $activeDeptId, // Tambahkan department_id
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

        if (!empty($answerRecords)) {
            DB::table('answers')->upsert(
                $answerRecords, 
                ['audit_id', 'item_id', 'auditor_name', 'department_id'], // Include department_id
                ['answer', 'answered_at', 'updated_at']
            );
        }

        if (!empty($finalRecords)) {
            DB::table('answer_finals')->upsert(
                $finalRecords, 
                ['audit_id', 'item_id', 'department_id'], // Include department_id
                ['yes_count', 'no_count', 'final_yes', 'final_no', 'decided_at', 'updated_at']
            );
        }
    });

    // === LOGIKA REDIRECT & CEK FINISH ===
    
    // 1. Cek apakah ini klausul terakhir secara urutan
    $mainKeys = array_keys($this->mainClauses);
    $currentIndex = array_search($mainClause, $mainKeys);
    $nextMain = $mainKeys[$currentIndex + 1] ?? null;

    // 2. Hitung total progres untuk departemen aktif
    $allFinished = true;
    foreach ($this->mainClauses as $mCode => $subCodes) {
        $totalItems = DB::table('items')
            ->join('clauses', 'items.clause_id', '=', 'clauses.id')
            ->whereIn('clauses.clause_code', $subCodes)
            ->count();
        
        $answered = DB::table('answer_finals')
            ->where('audit_id', $auditId)
            ->where('department_id', $activeDeptId) // Filter per departemen
            ->whereIn('item_id', function($q) use ($subCodes) {
                $q->select('items.id')
                  ->from('items')
                  ->join('clauses', 'items.clause_id', '=', 'clauses.id')
                  ->whereIn('clauses.clause_code', $subCodes);
            })
            ->count();

        if ($answered < $totalItems) {
            $allFinished = false;
            break;
        }
    }

    if ($allFinished) {
        // Update status audit jadi COMPLETE untuk departemen ini
        DB::table('audits')->where('id', $auditId)->update([
            'status' => 'COMPLETE',
            'updated_at' => now()
        ]);
        
        return redirect()->route('audit.menu', $auditId)
            ->with('success', 'Audit untuk departemen ini telah selesai!')
            ->with('all_complete', true);
    }

    if ($nextMain) {
        return redirect()->route('audit.show', ['id' => $auditId, 'clause' => $nextMain])
            ->with('success', "Klausul {$mainClause} disimpan. Berlanjut ke Klausul {$nextMain}");
    }

    return redirect()->route('audit.menu', $auditId)
        ->with('success', 'Data berhasil disimpan.');
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

// Tampilkan halaman pemilihan departemen
public function selectDepartment($auditId)
{
    $audit = DB::table('audits')->where('id', $auditId)->first();
    if (!$audit) abort(404);

    $departmentIds = json_decode($audit->department_ids, true);
    
    // Ambil data departemen beserta progress
    $departments = DB::table('departments')
        ->whereIn('id', $departmentIds)
        ->get()
        ->map(function($dept) use ($auditId) {
            return [
                'id' => $dept->id,
                'name' => $dept->name,
                'progress' => $this->getDepartmentProgress($auditId, $dept->id),
                'status' => $this->getDepartmentStatus($auditId, $dept->id)
            ];
        });

    $session = DB::table('audit_sessions')->where('id', $audit->audit_session_id)->first();
    
    return view('audit.select_department', [
        'auditId' => $auditId,
        'departments' => $departments,
        'auditorName' => $session->auditor_name,
        'auditCode' => $audit->audit_code ?? 'Audit',
    ]);
}

// Set departemen aktif untuk dikerjakan
public function setActiveDepartment(Request $request, $auditId)
{
    $request->validate([
        'department_id' => 'required|uuid|exists:departments,id'
    ]);

    // Simpan departemen aktif di session
    session(['active_department_id' => $request->department_id]);
    
    return redirect()->route('audit.menu', ['id' => $auditId]);
}

// Helper: Hitung progress per departemen
private function getDepartmentProgress($auditId, $departmentId)
{
    $totalClauses = count($this->mainClauses);
    $completedClauses = 0;

    foreach ($this->mainClauses as $mainCode => $subCodes) {
        $totalItems = DB::table('items')
            ->join('clauses', 'items.clause_id', '=', 'clauses.id')
            ->whereIn('clauses.clause_code', $subCodes)
            ->count();

        $answeredItems = DB::table('answers')
            ->join('items', 'answers.item_id', '=', 'items.id')
            ->join('clauses', 'items.clause_id', '=', 'clauses.id')
            ->where('answers.audit_id', $auditId)
            ->where('answers.department_id', $departmentId)
            ->whereIn('clauses.clause_code', $subCodes)
            ->count();

        if ($answeredItems >= $totalItems && $totalItems > 0) {
            $completedClauses++;
        }
    }

    return [
        'percentage' => $totalClauses > 0 ? round(($completedClauses / $totalClauses) * 100) : 0,
        'completed' => $completedClauses,
        'total' => $totalClauses
    ];
}

// Helper: Status departemen
private function getDepartmentStatus($auditId, $departmentId)
{
    $progress = $this->getDepartmentProgress($auditId, $departmentId);
    
    if ($progress['percentage'] == 100) {
        return 'completed';
    } elseif ($progress['completed'] > 0) {
        return 'in_progress';
    }
    return 'pending';
}
}
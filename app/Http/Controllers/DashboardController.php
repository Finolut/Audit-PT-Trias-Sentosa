<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Audit;
use App\Models\Item;
use App\Models\Clause;
use Illuminate\Support\Facades\DB;
use App\Models\AuditQuestion;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    // ... (Definisi $mainClauses dan $mainClauseTitles biarkan tetap ada di atas) ...
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

    /**
     * 1. DASHBOARD UTAMA (Tampilan Awal Admin)
     */
public function index()
{
    $departments = Department::orderBy('name', 'asc')->get();

    $stats = [
        'total_audits' => Audit::count(),
        'completed'    => Audit::where('status', 'COMPLETED')->count(),
        'pending'      => Audit::where('status', 'IN_PROGRESS')->count(),
        'departments'  => Department::count(),
    ];

    $recentAudits = Audit::with(['department', 'session'])
                         ->orderBy('created_at', 'desc')
                         ->take(5)
                         ->get();

    // PERBAIKAN QUERY: Join ke tabel sessions (sesuaikan nama tabelnya, biasanya plural 'sessions' atau 'audit_sessions')
    $liveQuestions = DB::table('audit_questions')
    ->join('audits', 'audit_questions.audit_id', '=', 'audits.id')
    ->join('departments', 'audits.department_id', '=', 'departments.id')
    /* KARENA audit_id tidak ada di audit_sessions, besar kemungkinan 
       tabel audits yang memiliki kolom session_id. 
       Atau audit_sessions menggunakan ID sebagai primary key yang dirujuk audit.
    */
    ->leftJoin('audit_sessions', 'audits.audit_session_id', '=', 'audit_sessions.id') 
    ->select(
        'audit_questions.*',
        'departments.name as dept_name',
        'audit_sessions.auditor_name as auditor_name'
    )
    ->orderBy('audit_questions.created_at', 'desc')
    ->take(5)
    ->get();

    return view('admin.dashboard', compact('departments', 'stats', 'recentAudits', 'liveQuestions'));
}

    /**
     * 2. HALAMAN DETAIL DEPARTEMEN
     */
    public function showDepartment(Request $request, $deptId)
    {
        $departments = Department::orderBy('name')->get();
        $currentDept = Department::findOrFail($deptId);
        
        $query = Audit::where('department_id', $deptId)
                      ->with(['session', 'responders']);

        if ($request->has('year') && $request->year != '') {
            $query->whereYear('created_at', $request->year);
        }

        $audits = $query->orderBy('created_at', 'desc')->get();

        // Hitung statistik kecil untuk halaman ini
        $localStats = [
            'total' => $audits->count(),
            'completed' => $audits->where('status', 'COMPLETED')->count(),
            'pending' => $audits->where('status', 'PENDING')->count(),
        ];

        return view('admin.department_audits', compact('departments', 'currentDept', 'audits', 'localStats'));
    }

    // ... (Fungsi showAuditOverview dan showClauseDetail biarkan seperti kode Anda sebelumnya) ...
    public function showAuditOverview($auditId)
    {
        // Copy paste logic showAuditOverview Anda yang panjang di sini
        // Pastikan return view('admin.audit_clauses', ...)
        $departments = Department::all();
       $audit = Audit::with('department')->findOrFail($auditId);

        // --- LOGIKA GRAFIK ---
        $allItems = Item::join('clauses', 'items.clause_id', '=', 'clauses.id')
            ->leftJoin('answer_finals', function($join) use ($auditId) {
                $join->on('items.id', '=', 'answer_finals.item_id')
                     ->where('answer_finals.audit_id', '=', $auditId);
            })
            ->select(
                'clauses.clause_code',
                'answer_finals.final_yes',
                'answer_finals.final_no',
                'answer_finals.yes_count',
                'answer_finals.no_count'
            )
            ->get();

        $detailedStats = []; 
        $mainStats = [];     

// 1. UBAH INISIALISASI ARRAY (Tambahkan key 'unanswered')
foreach($this->mainClauses as $main => $subs) {
    // Tambah 'unanswered' di sini
    $mainStats[$main] = ['yes' => 0, 'no' => 0, 'partial' => 0, 'na' => 0, 'unanswered' => 0]; 
    foreach($subs as $sub) {
        $detailedStats[$sub] = ['yes' => 0, 'no' => 0, 'partial' => 0, 'na' => 0, 'unanswered' => 0];
    }
}

// 2. UBAH LOGIKA LOOPING STATUS
// ... (Bagian atas method tetap sama)

foreach ($allItems as $item) {
    $status = 'unanswered'; // Default status awal

    // LOGIKA PEMISAHAN:
    
    // 1. Cek apakah record jawaban ADA di database (bukan null)
    if (is_null($item->final_yes)) {
        // Jika null, berarti baris ini dihasilkan dari LEFT JOIN karena belum ada datanya
        $status = 'unanswered'; // Warna ABU-ABU
    } 
    // 2. Jika data ADA, cek apakah hasil votingnya kosong (N/A)
    elseif ($item->yes_count == 0 && $item->no_count == 0) {
        // Sudah dikerjakan/disubmit tapi tidak ada pilihan Yes atau No (N/A)
        $status = 'na'; // Warna KUNING
    } 
    // 3. Logika Yes/No/Partial seperti biasa
    elseif ($item->final_yes > $item->final_no) {
        $status = 'yes';
    } elseif ($item->final_no > $item->final_yes) {
        $status = 'no';
    } else {
        $status = 'partial'; // Seri
    }

    // Masukkan ke array statistics
    if (isset($detailedStats[$item->clause_code])) {
        $detailedStats[$item->clause_code][$status]++;
    }

    foreach($this->mainClauses as $mainKey => $subArray) {
        if (in_array($item->clause_code, $subArray)) {
            $mainStats[$mainKey][$status]++;
            break;
        }
    }
}

// ... (Sisa method return view tetap sama)

        return view('admin.audit_clauses', [
            'departments' => $departments,
            'audit' => $audit,
            'mainClauses' => $this->mainClauses,
            'titles' => $this->mainClauseTitles,
            'detailedStats' => $detailedStats, 
            'mainStats' => $mainStats          
        ]);
    }

    public function showClauseDetail($auditId, $mainClause)
    {
        // Copy paste logic showClauseDetail Anda di sini
        // Pastikan return view('admin.clause_detail', ...)
         $departments = Department::all();
        $audit = Audit::findOrFail($auditId);

        if (!array_key_exists($mainClause, $this->mainClauses)) abort(404);

        $subCodes = $this->mainClauses[$mainClause];
        $clausesDb = Clause::whereIn('clause_code', $subCodes)->get();
        $clauseIds = $clausesDb->pluck('id');
        $subClauseTitles = $clausesDb->pluck('title', 'clause_code');

        $auditorNotes = DB::table('audit_questions')
            ->where('audit_id', $auditId)
            ->whereIn('clause_code', $subCodes)
            ->pluck('question_text', 'clause_code');

        $items = Item::whereIn('clause_id', $clauseIds)
            ->join('clauses', 'items.clause_id', '=', 'clauses.id')
            ->join('maturity_levels', 'items.maturity_level_id', '=', 'maturity_levels.id')
            ->select('items.*', 'clauses.clause_code as current_code', 'maturity_levels.level_number')
            ->orderBy('clauses.clause_code')
            ->orderBy('maturity_levels.level_number', 'asc')
            ->orderBy('items.item_order', 'asc')
            ->with(['answerFinals' => function($q) use ($auditId) {
                $q->where('audit_id', $auditId);
            }])
            ->get();

        $itemsGrouped = $items->groupBy('current_code');

        // Statistik Global (Doughnut)
        $totalYes = 0; $totalNo = 0; $totalDraw = 0; $totalNA = 0;

        // Statistik Stacked Bar
        $stackedChartData = [];
        foreach($subCodes as $code) {
            $stackedChartData[$code] = ['yes' => 0, 'no' => 0, 'partial' => 0, 'na' => 0];
        }

        $items->each(function($item) use (&$totalYes, &$totalNo, &$totalDraw, &$totalNA, &$stackedChartData) {
            $final = $item->answerFinals->first();
            $status = 'na';

            if (!$final || ($final->yes_count == 0 && $final->no_count == 0)) {
                $totalNA++;
                $status = 'na';
            } elseif ($final->final_yes > $final->final_no) {
                $totalYes++;
                $status = 'yes';
            } elseif ($final->final_no > $final->final_yes) {
                $totalNo++;
                $status = 'no';
            } else {
                $totalDraw++;
                $status = 'partial';
            }

            if(isset($stackedChartData[$item->current_code])) {
                $stackedChartData[$item->current_code][$status]++;
            }
        });

        return view('admin.clause_detail', compact(
            'departments', 'audit', 'mainClause', 'subCodes', 'subClauseTitles',
            'itemsGrouped', 'auditorNotes', 
            'totalYes', 'totalNo', 'totalDraw', 'totalNA', 
            'stackedChartData',
            'items'
        ));
    }

    // Tambahkan method ini di dalam class DashboardController

public function departmentStatusIndex()
{
    // 1. Ambil list departemen untuk sidebar (agar tidak error di layout)
    $departments = Department::orderBy('name', 'asc')->get();

    // 2. Ambil data summary status per departemen (Logic yang sama dengan dashboard)
    $deptSummary = Department::withCount(['audits as total_audit', 
        'audits as completed_count' => function ($query) {
            $query->where('status', 'COMPLETED');
        },
        'audits as pending_count' => function ($query) {
            $query->where('status', 'IN_PROGRESS'); // Pastikan konsisten dengan enum database Anda
        }
    ])->orderBy('name', 'asc')->get();

    return view('admin.department_status_index', compact('departments', 'deptSummary'));
}

public function questionLog()
{
    $departments = Department::orderBy('name', 'asc')->get();
    
    // Mengambil semua log pertanyaan (dengan paginasi agar tidak berat)
    $allQuestions = \App\Models\AuditQuestion::with(['audit.department', 'audit.session'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

    return view('admin.question_log', compact('departments', 'allQuestions'));
}

public function exportToPdf($auditId)
{
    $audit = Audit::with('department')->findOrFail($auditId);

    // Ambil session terkait audit
    $session = DB::table('audit_sessions')
        ->where('id', $audit->audit_session_id)
        ->first();

    if (!$session) {
        abort(404, 'Audit session not found');
    }

    // Ambil data auditor utama dari kolom langsung
    $leadAuditor = [
        'name' => $session->auditor_name,
        'nik' => $session->auditor_nik,
        'department' => $session->auditor_department,
    ];

    // Ambil anggota tim dari kolom JSON `audit_team`
    $teamMembers = [];
    if (!empty($session->audit_team)) {
        $teamJson = json_decode($session->audit_team, true);
        if (is_array($teamJson)) {
            foreach ($teamJson as $member) {
                $teamMembers[] = [
                    'name' => $member['name'] ?? '-',
                    'nik' => $member['nik'] ?? 'N/A',
                    'department' => $member['department'] ?? 'N/A',
                ];
            }
        }
    }

    // Ambil data klausul & jawaban (kode lama tetap dipertahankan)
    $allItems = Item::join('clauses', 'items.clause_id', '=', 'clauses.id')
        ->leftJoin('answer_finals', function($join) use ($auditId) {
            $join->on('items.id', '=', 'answer_finals.item_id')
                 ->where('answer_finals.audit_id', '=', $auditId);
        })
        ->select(
            'clauses.clause_code',
            'items.item_text',
            'answer_finals.final_yes',
            'answer_finals.final_no',
            'answer_finals.yes_count',
            'answer_finals.no_count'
        )
        ->get();

    // Hitung statistik (kode lama tetap dipertahankan)
    $mainStats = [];
    $detailedItems = [];
    foreach($this->mainClauses as $main => $subs) {
        $mainStats[$main] = ['yes' => 0, 'no' => 0, 'partial' => 0, 'na' => 0, 'unanswered' => 0];
    }

    foreach ($allItems as $item) {
        $status = 'unanswered';
        $mainClause = 'Unknown';

        if (is_null($item->final_yes)) {
            $status = 'unanswered';
        } elseif ($item->yes_count == 0 && $item->no_count == 0) {
            $status = 'na';
        } elseif ($item->final_yes > $item->final_no) {
            $status = 'yes';
        } elseif ($item->final_no > $item->final_yes) {
            $status = 'no';
        } else {
            $status = 'partial';
        }

        foreach($this->mainClauses as $mainKey => $subArray) {
            if (in_array($item->clause_code, $subArray)) {
                $mainClause = $mainKey;
                $mainStats[$mainKey][$status]++;
                break;
            }
        }

        $detailedItems[] = [
            'main_clause' => $mainClause,
            'sub_clause' => $item->clause_code,
            'item_text' => $item->item_text,
            'status' => $status,
            'yes_count' => $item->yes_count,
            'no_count' => $item->no_count,
        ];
    }

    // Generate PDF dengan data tambahan
    $pdf = Pdf::loadView('admin.exports.audit_overview_pdf', [
        'audit' => $audit,
        'leadAuditor' => $leadAuditor,      // ✅ Data auditor utama
        'teamMembers' => $teamMembers,      // ✅ Data anggota tim
        'mainClauses' => $this->mainClauses,
        'titles' => $this->mainClauseTitles,
        'mainStats' => $mainStats,
        'detailedItems' => $detailedItems
    ]);

    return $pdf->download("audit_{$audit->id}_".now()->format('Ymd').".pdf");
}
}


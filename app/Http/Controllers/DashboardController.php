<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Audit;
use App\Models\Item;
use App\Models\Clause;
use Illuminate\Support\Facades\DB;

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
    // Ambil data departemen untuk sidebar
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

    // Data untuk Blue Card (Pertanyaan Audit Terkini)
    $liveQuestions = DB::table('audit_questions')
        ->join('audits', 'audit_questions.audit_id', '=', 'audits.id')
        ->join('departments', 'audits.department_id', '=', 'departments.id')
        ->select('audit_questions.*', 'departments.name as dept_name')
        ->orderBy('audit_questions.created_at', 'desc')
        ->take(3)
        ->get();

    // Pastikan 'departments' disertakan dalam compact
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

        // Inisialisasi Array Kosong
        foreach($this->mainClauses as $main => $subs) {
            $mainStats[$main] = ['yes' => 0, 'no' => 0, 'partial' => 0, 'na' => 0];
            foreach($subs as $sub) {
                $detailedStats[$sub] = ['yes' => 0, 'no' => 0, 'partial' => 0, 'na' => 0];
            }
        }

        // Hitung Data
        foreach ($allItems as $item) {
            $status = 'na';
            
            if (is_null($item->final_yes) || ($item->yes_count == 0 && $item->no_count == 0)) {
                $status = 'na';
            } elseif ($item->final_yes > $item->final_no) {
                $status = 'yes';
            } elseif ($item->final_no > $item->final_yes) {
                $status = 'no';
            } else {
                $status = 'partial';
            }

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
}


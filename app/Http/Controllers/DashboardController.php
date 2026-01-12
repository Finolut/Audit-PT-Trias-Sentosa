<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Audit;
use App\Models\Clause;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // --- KONFIGURASI STRUKTUR KLAUSUL ---
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
     * 1. HALAMAN DASHBOARD UTAMA
     * Route: /admin/dashboard (atau sejenisnya)
     */
    public function index()
    {
        $departments = Department::all();
        $totalAudits = Audit::count();
        $totalDepartments = Department::count();
        $totalAuditors = DB::table('audit_sessions')->distinct('auditor_name')->count('auditor_name');

        // Pastikan view ini ada (biasanya layouts.admin atau admin.dashboard)
        return view('layouts.admin', compact('departments', 'totalAudits', 'totalDepartments', 'totalAuditors'));
    }

    /**
     * 2. HALAMAN DETAIL DEPARTEMEN (Menu Sidebar)
     * Route: /admin/department/{id}
     */
    /**
 * 2. HALAMAN DETAIL DEPARTEMEN (Menu Sidebar)
 * Route: /admin/department/{id}
 */
public function showDepartment(Request $request, $deptId) // Tambahkan Request $request
{
    $departments = Department::all();
    $currentDept = Department::findOrFail($deptId);
    
    // Mulai query
    $query = Audit::where('department_id', $deptId)
                  ->with(['session', 'responders']); // Tambahkan responders agar badge nama muncul

    // LOGIKA FILTER TAHUN
    if ($request->has('year') && $request->year != '') {
        $query->whereYear('created_at', $request->year);
    }

    // Ambil data dengan urutan terbaru
    $audits = $query->orderBy('created_at', 'desc')->get();

    return view('admin.department_audits', compact('departments', 'currentDept', 'audits'));
}

    /**
     * 3. HALAMAN OVERVIEW AUDIT (GRAFIK UTAMA)
     * Route: /admin/audit/{id}
     */
    public function showAuditOverview($auditId)
    {
        $departments = Department::all();
        $audit = Audit::with(['session', 'department'])->findOrFail($auditId);

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

    /**
     * 4. HALAMAN DETAIL PER KLAUSUL (TABEL & CHART BAR)
     * Route: /admin/audit/{id}/clause/{mainClause}
     */
    public function showClauseDetail($auditId, $mainClause)
    {
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

public function log(Request $request, $deptId)
{
    $currentDept = Department::findOrFail($deptId);
    
    // 1. Mulai Query
    $query = Audit::where('department_id', $deptId)
                  ->with(['session', 'responders']);

    // 2. LOGIKA FILTER (Ini yang membuat data berubah)
    if ($request->has('year') && $request->year != '') {
        $query->whereYear('created_at', $request->year);
    }

    // 3. Ambil data dengan urutan terbaru
    $audits = $query->latest()->get();

    return view('admin.department_audits', compact('audits', 'currentDept'));
}
}
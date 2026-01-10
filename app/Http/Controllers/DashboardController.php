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
    // Definisi Struktur Klausul (Sama dengan Auditor Controller)
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

    public function index()
    {
        $departments = Department::all();
        $totalAudits = Audit::count();
        $totalDepartments = Department::count();
        $totalAuditors = DB::table('audit_sessions')->distinct('auditor_name')->count('auditor_name');

        return view('layouts.admin', compact('departments', 'totalAudits', 'totalDepartments', 'totalAuditors'));
    }

    public function showDepartment($deptId)
    {
        $departments = Department::all();
        $currentDept = Department::findOrFail($deptId);
        $audits = Audit::where('department_id', $deptId)
                    ->with(['session']) 
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin.department_audits', compact('departments', 'currentDept', 'audits'));
    }

    /**
     * TAMPILAN MENU GRID (Menggunakan Main Clause 4, 5, 6...)
     */
    public function showAuditOverview($auditId)
    {
        $departments = Department::all();
        $audit = Audit::with(['session'])->findOrFail($auditId);

        // Kita kirim array struktur utama ke View
        return view('admin.audit_clauses', [
            'departments' => $departments,
            'audit' => $audit,
            'mainClauses' => $this->mainClauses,
            'titles' => $this->mainClauseTitles
        ]);
    }

    /**
     * TAMPILAN DETAIL PER MAIN CLAUSE (Gabungan Sub-Klausul)
     */
    public function showClauseDetail($auditId, $mainClause)
    {
        $departments = Department::all();
        $audit = Audit::findOrFail($auditId);

        // Validasi input
        if (!array_key_exists($mainClause, $this->mainClauses)) abort(404);

        $subCodes = $this->mainClauses[$mainClause];

        // Ambil Data Sub-Klausul dari DB berdasarkan kode (4.1, 4.2 dll)
        $clausesDb = Clause::whereIn('clause_code', $subCodes)->get();
        $clauseIds = $clausesDb->pluck('id');
        
        // Mapping Kode ke Judul untuk tampilan
        $subClauseTitles = $clausesDb->pluck('title', 'clause_code');

        // Ambil Catatan Auditor untuk SEMUA sub-klausul ini
        $auditorNotes = DB::table('audit_questions')
            ->where('audit_id', $auditId)
            ->whereIn('clause_code', $subCodes)
            ->pluck('question_text', 'clause_code');

        // Ambil Item Pertanyaan & Jawaban
        $items = Item::whereIn('clause_id', $clauseIds)
            ->join('clauses', 'items.clause_id', '=', 'clauses.id') // Join untuk dapat kode klausul
            ->join('maturity_levels', 'items.maturity_level_id', '=', 'maturity_levels.id')
            ->select('items.*', 'clauses.clause_code as current_code', 'maturity_levels.level_number')
            ->orderBy('clauses.clause_code') // Urutkan berdasarkan sub-klausul dulu
            ->orderBy('maturity_levels.level_number', 'asc')
            ->orderBy('items.item_order', 'asc')
            ->with(['answerFinals' => function($q) use ($auditId) {
                $q->where('audit_id', $auditId);
            }])
            ->get();

        // Grouping Item berdasarkan Sub-Klausul (4.1, 4.2)
        $itemsGrouped = $items->groupBy('current_code');

        // --- LOGIKA CHART (Global untuk Main Clause ini) ---
        $totalYes = 0;
        $totalNo = 0;
        $totalDraw = 0;
        $totalNA = 0;

        $items->each(function($item) use (&$totalYes, &$totalNo, &$totalDraw, &$totalNA) {
            $final = $item->answerFinals->first();
            
            // Logika N/A
            if ($final && $final->yes_count == 0 && $final->no_count == 0) {
                $totalNA++;
            } elseif (!$final) {
                $totalDraw++; // Belum diisi dianggap abu-abu/draw
            } elseif ($final->final_yes > $final->final_no) {
                $totalYes++;
            } elseif ($final->final_no > $final->final_yes) {
                $totalNo++;
            } else {
                $totalDraw++;
            }
        });

        // Data untuk Chart Line (Trend)
        $chartData = $items->map(function($item) {
            $final = $item->answerFinals->first();
            if ($final && $final->yes_count == 0 && $final->no_count == 0) return null; // N/A
            if (!$final) return 0;
            if ($final->final_yes > $final->final_no) return 1;
            if ($final->final_no > $final->final_yes) return -1;
            return 0;
        });

        return view('admin.clause_detail', compact(
            'departments', 'audit', 'mainClause', 'subCodes', 'subClauseTitles',
            'itemsGrouped', 'auditorNotes', 
            'totalYes', 'totalNo', 'totalDraw', 'totalNA', 'chartData', 'items'
        ));
    }
}
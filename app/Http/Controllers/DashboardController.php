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
    // ... (Definisi $mainClauses dan $mainClauseTitles biarkan sama) ...
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

    public function showAuditOverview($auditId)
    {
        $departments = Department::all();
        $audit = Audit::with(['session'])->findOrFail($auditId);

        // --- NEW: LOGIKA UNTUK GRAFIK ADMIN DASHBOARD (ALL CLAUSES & MAIN CLAUSES) ---
        
        // 1. Ambil semua item dan jawabannya untuk audit ini
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

        // 2. Siapkan wadah data
        $detailedStats = []; // Untuk 4.1, 4.2, dst
        $mainStats = [];     // Untuk 4, 5, 6, dst

        // Inisialisasi Main Stats
        foreach($this->mainClauses as $main => $subs) {
            $mainStats[$main] = ['yes' => 0, 'no' => 0, 'partial' => 0, 'na' => 0];
            foreach($subs as $sub) {
                $detailedStats[$sub] = ['yes' => 0, 'no' => 0, 'partial' => 0, 'na' => 0];
            }
        }

        // 3. Looping hitung statistik
        foreach ($allItems as $item) {
            // Tentukan status item ini
            $status = 'na'; // Default N/A
            
            // Logika N/A: Jika belum ada di answer_finals atau count 0 semua
            if ($item->yes_count == 0 && $item->no_count == 0 && is_null($item->final_yes)) {
                $status = 'na';
            } elseif ($item->final_yes > $item->final_no) {
                $status = 'yes';
            } elseif ($item->final_no > $item->final_yes) {
                $status = 'no';
            } else {
                $status = 'partial';
            }

            // Masukkan ke Detailed Stats (4.1, 4.2...)
            if (isset($detailedStats[$item->clause_code])) {
                $detailedStats[$item->clause_code][$status]++;
            }

            // Masukkan ke Main Stats (4, 5...)
            // Cari Main Clause dari Sub Clause
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
            'detailedStats' => $detailedStats, // Data untuk Grafik Rinci
            'mainStats' => $mainStats          // Data untuk Grafik Inti
        ]);
    }

    public function showClauseDetail($auditId, $mainClause)
    {
        $departments = Department::all();
        $audit = Audit::findOrFail($auditId);

        if (!array_key_exists($mainClause, $this->mainClauses)) abort(404);

        $subCodes = $this->mainClauses[$mainClause];
        $clausesDb = Clause::whereIn('clause_code', $subCodes)->get();
        $clauseIds = $clausesDb->pluck('id');
        $subClauseTitles = $clausesDb->pluck('title', 'clause_code');

        // Ambil Notes
        $auditorNotes = DB::table('audit_questions')
            ->where('audit_id', $auditId)
            ->whereIn('clause_code', $subCodes)
            ->pluck('question_text', 'clause_code');

        // Ambil Items + Jawaban
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

        // --- STATISTIK GLOBAL (Pie Chart) ---
        $totalYes = 0; $totalNo = 0; $totalDraw = 0; $totalNA = 0;

        // --- DATA UNTUK STACKED BAR CHART (PER SUB-CLAUSE) ---
        // Format: ['4.1' => ['yes'=>2, 'no'=>1...], '4.2' => ...]
        $stackedChartData = [];

        foreach($subCodes as $code) {
            $stackedChartData[$code] = ['yes' => 0, 'no' => 0, 'partial' => 0, 'na' => 0];
        }

        $items->each(function($item) use (&$totalYes, &$totalNo, &$totalDraw, &$totalNA, &$stackedChartData) {
            $final = $item->answerFinals->first();
            
            $status = 'na'; // Default

            // Logika Penentuan Status
            if ($final && $final->yes_count == 0 && $final->no_count == 0) {
                $totalNA++;
                $status = 'na';
            } elseif (!$final) {
                $totalNA++; // Belum ada record = N/A
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

            // Push ke array Stacked Chart berdasarkan kode sub-clause item ini
            if(isset($stackedChartData[$item->current_code])) {
                $stackedChartData[$item->current_code][$status]++;
            }
        });

        return view('admin.clause_detail', compact(
            'departments', 'audit', 'mainClause', 'subCodes', 'subClauseTitles',
            'itemsGrouped', 'auditorNotes', 
            'totalYes', 'totalNo', 'totalDraw', 'totalNA', 
            'stackedChartData', // Variabel baru untuk grafik batang
            'items'
        ));
    }
}
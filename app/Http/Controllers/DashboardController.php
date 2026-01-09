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
    /**
     * 1. HALAMAN DASHBOARD UTAMA (ROOT)
     * Menampilkan sidebar departemen dan ringkasan (opsional)
     */
    public function index()
    {
        // Data untuk Sidebar
        $departments = Department::all();
        
        // Data ringkasan untuk kartu-kartu di dashboard (sesuai gambar desain)
        $totalAudits = Audit::count();
        $totalDepartments = Department::count();
        // Menghitung jumlah auditor unik dari sesi audit
        $totalAuditors = \App\Models\AuditSession::distinct('auditor_name')->count('auditor_name');

        return view('layouts.admin', compact('departments', 'totalAudits', 'totalDepartments', 'totalAuditors'));
    }

    /**
     * 2. HALAMAN DEPARTEMEN & LIST AUDIT
     * Muncul saat salah satu departemen di sidebar diklik
     */
    public function showDepartment($deptId)
    {
        $departments = Department::all(); // Sidebar harus selalu ada
        $currentDept = Department::findOrFail($deptId);

        // Ambil list audit di departemen ini
        $audits = Audit::where('department_id', $deptId)
                    ->with(['session', 'responders']) 
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin.department_audits', compact('departments', 'currentDept', 'audits'));
    }

    /**
     * 3. HALAMAN PILIH KLAUSUL (OVERVIEW)
     * Muncul saat salah satu Audit diklik. Menampilkan kotak 4.1, 4.2, dll.
     */
    public function showAuditOverview($auditId)
    {
        $departments = Department::all();
        $audit = Audit::with(['session'])->findOrFail($auditId);

        // Ambil semua Clause untuk ditampilkan sebagai menu
        $clauses = Clause::orderBy('clause_code', 'asc')->get();

        return view('admin.audit_clauses', compact('departments', 'audit', 'clauses'));
    }

    /**
     * 4. HALAMAN DETAIL SOAL & DIAGRAM
     * Muncul saat salah satu Klausul diklik.
     */
public function showClauseDetail($auditId, $clauseId)
{
    $departments = Department::all();
    $audit = Audit::findOrFail($auditId);
    $clause = Clause::findOrFail($clauseId);

    // Ambil Catatan/Pertanyaan Auditor untuk Klausul ini
    $auditorQuestion = DB::table('audit_questions')
                        ->where('audit_id', $auditId)
                        ->where('clause_code', $clause->clause_code) // Menggunakan code (misal 4.1)
                        ->value('question_text');

    $items = Item::where('clause_id', $clauseId)
                ->join('maturity_levels', 'items.maturity_level_id', '=', 'maturity_levels.id')
                ->select('items.*')
                ->orderBy('maturity_levels.level_number', 'asc')
                ->orderBy('items.item_order', 'asc')
                ->with(['maturityLevel', 'answerFinals' => function($q) use ($auditId) {
                    $q->where('audit_id', $auditId);
                }])
                ->get();

    $totalYes = 0;
    $totalNo = 0;
    $totalDraw = 0;
    $totalNA = 0; // Tambahkan ini

    $chartData = $items->map(function($item) use (&$totalYes, &$totalNo, &$totalDraw, &$totalNA) {
        $final = $item->answerFinals->first();
        
        // Logika pengecekan N/A: Jika yes_count dan no_count keduanya 0 tapi ada record di answer_finals
        if ($final && $final->yes_count == 0 && $final->no_count == 0) {
            $totalNA++;
            return null; // Gunakan null untuk N/A di chart agar garis terputus atau diabaikan
        }

        if (!$final) {
            $totalDraw++;
            return 0;
        }

        if ($final->final_yes > $final->final_no) {
            $totalYes++;
            return 1;
        } elseif ($final->final_no > $final->final_yes) {
            $totalNo++;
            return -1;
        } else {
            $totalDraw++;
            return 0;
        }
    });

    return view('admin.clause_detail', compact(
        'departments', 'audit', 'clause', 'items', 
        'totalYes', 'totalNo', 'totalDraw', 'totalNA', 'chartData','auditorQuestion'
    ));
}
}
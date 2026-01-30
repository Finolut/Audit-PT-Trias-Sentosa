<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EvidencesController extends Controller
{
   public function evidenceLog()
{
    $evidences = DB::table('answer_evidences')
        ->join('answers', 'answer_evidences.answer_id', '=', 'answers.id')
        ->join('items', 'answers.item_id', '=', 'items.id')
        ->join('clauses', 'items.clause_id', '=', 'clauses.id')
        ->join('audits', 'answer_evidences.audit_id', '=', 'audits.id')
        ->join('departments', 'audits.department_id', '=', 'departments.id')
        ->select([
            'answer_evidences.id as evidence_id',
            'answer_evidences.file_path',
            'answer_evidences.created_at as evidence_time',
            'answers.answer',
            'answers.finding_level',
            'answers.auditor_name',
            'items.item_text',
            'clauses.clause_code',
            'departments.name as department_name',
            'audits.id as audit_id',
        ])
        ->orderByDesc('answer_evidences.created_at')
        ->paginate(15);

    // 🔑 INI YANG KAMU LUPA
    $departments = DB::table('departments')
        ->orderBy('name')
        ->get();

    return view('admin.evidence_log', compact('evidences', 'departments'));
}

}
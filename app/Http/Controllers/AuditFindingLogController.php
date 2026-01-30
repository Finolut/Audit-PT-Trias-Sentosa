<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditFindingLogController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua departemen untuk filter (opsional)
        $departments = DB::table('departments')
            ->orderBy('name')
            ->get();

        // Query catatan temuan (hanya yang memiliki finding_level)
        $query = DB::table('answers')
            ->join('items', 'answers.item_id', '=', 'items.id')
            ->join('clauses', 'items.clause_id', '=', 'clauses.id')
            ->join('departments', 'answers.department_id', '=', 'departments.id')
            ->join('audits', 'answers.audit_id', '=', 'audits.id')
            ->whereNotNull('answers.finding_level')
            ->where('answers.finding_level', '!=', '');

        // Filter berdasarkan departemen (jika dipilih)
        if ($request->filled('department_id')) {
            $query->where('departments.id', $request->department_id);
        }

        // Filter pencarian (opsional: bisa tambah logika cari di item_text, auditor_name, dll)
        // Contoh sederhana: cari di item_text atau auditor_name
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('items.item_text', 'ILIKE', "%{$search}%")
                  ->orWhere('answers.auditor_name', 'ILIKE', "%{$search}%")
                  ->orWhere('clauses.clause_code', 'ILIKE', "%{$search}%");
            });
        }

        $findings = $query->select(
                'answers.*',
                'clauses.clause_code',
                'items.item_text',
                'departments.name as department_name'
            )
            ->orderBy('answers.answered_at', 'desc')
            ->paginate(15)
            ->appends($request->only(['search', 'department_id']));

        return view('admin.finding_logs', compact('findings', 'departments'));
    }
}
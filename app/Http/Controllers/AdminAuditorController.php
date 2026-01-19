<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Audit;
use App\Models\AuditSession; // Pastikan ada Model ini
use Illuminate\Support\Facades\DB;

class AdminAuditorController extends Controller
{
    public function index()
    {
        $auditors = User::where('role', 'auditor')
                        ->orderBy('name', 'asc')
                        ->get();

        foreach($auditors as $auditor) {
            // PERBAIKAN: Hitung dari tabel audit_sessions karena auditor_name ada di sana
            $auditor->total_audits = DB::table('audit_sessions')
                                        ->where('auditor_name', $auditor->name)
                                        ->count();
        }

        return view('admin.auditors.index', compact('auditors'));
    }

    public function show($id)
    {
        $auditor = User::findOrFail($id);

        // PERBAIKAN: Gunakan JOIN untuk mengambil data Audit berdasarkan nama Auditor di Session
        $history = Audit::join('audit_sessions', 'audits.audit_session_id', '=', 'audit_sessions.id')
                        ->select(
                            'audits.*', 
                            'audit_sessions.audit_date', 
                            'audit_sessions.audit_team',
                            'audit_sessions.auditor_name'
                        )
                        ->where('audit_sessions.auditor_name', $auditor->name)
                        ->orderBy('audit_sessions.audit_date', 'desc')
                        ->get();

        // Statistik Ringkas (Ambil dari hasil query di atas)
        $stats = [
            'total' => $history->count(),
            'regular' => $history->where('type', 'Regular')->count(), // Perhatikan nama kolom 'type' di DB (bukan audit_type)
            'special' => $history->where('type', 'Special')->count(),
            'followup' => $history->where('type', 'FollowUp')->count(),
        ];

        return view('admin.auditors.show', compact('auditor', 'history', 'stats'));
    }
}
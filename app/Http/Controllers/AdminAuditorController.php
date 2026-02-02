<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Audit;
use App\Models\AuditSession;
use Illuminate\Support\Facades\DB;
use App\Models\Department;

class AdminAuditorController extends Controller
{
    public function index()
    {
        $auditors = User::where('role', 'auditor')
                        ->orderBy('name', 'asc')
                        ->get();

        foreach($auditors as $auditor) {
            $auditor->total_audits = DB::table('audit_sessions')
                                        ->where('auditor_name', $auditor->name)
                                        ->count();
        }

        return view('admin.auditors.index', compact('auditors'));
    }

    public function show($id)
    {
        $auditor = User::findOrFail($id);
        $departments = Department::orderBy('name', 'asc')->get();

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

        // ✅ SESUAIKAN DENGAN NILAI ASLI DI DATABASE
        $stats = [
            'total' => $history->count(),
            'first_party' => $history->where('type', 'first party')->count(),
            'follow_up' => $history->where('type', 'follow up')->count(),
            'investigative' => $history->where('type', 'investigative')->count(),
            'unannounced' => $history->where('type', 'unannounced')->count(),
        ];

        return view('admin.auditors.show', compact('auditor', 'history', 'stats', 'departments'));
    }

    public function destroy($id)
    {
        $auditor = User::findOrFail($id);
        $auditor->delete();
        return redirect()->route('admin.auditors.index')->with('success', 'Auditor berhasil dihapus.');
    }
}
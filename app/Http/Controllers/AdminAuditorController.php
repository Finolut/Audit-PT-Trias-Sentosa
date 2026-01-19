<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Audit; // Pastikan model Audit Anda benar
use App\Models\Department;

class AdminAuditorController extends Controller
{
    /**
     * Menampilkan daftar semua auditor
     */
    public function index()
    {
        // Ambil user yang rolenya 'auditor'
        $auditors = User::where('role', 'auditor')
                        ->orderBy('name', 'asc')
                        ->get();

        // Kita bisa menghitung total audit per auditor untuk ditampilkan di tabel
        // Asumsi: Di tabel audits ada kolom 'auditor_name' atau relasi user_id
        foreach($auditors as $auditor) {
            // Mencari berdasarkan nama karena form lama menyimpan nama string
            $auditor->total_audits = Audit::where('auditor_name', $auditor->name)->count();
            
            // Atau jika pakai session:
            // $auditor->total_audits = \DB::table('audit_sessions')->where('auditor_name', $auditor->name)->count();
        }

        return view('admin.auditors.index', compact('auditors'));
    }

    /**
     * Menampilkan Detail History satu Auditor
     */
    public function show($id)
    {
        $auditor = User::findOrFail($id);

        // Ambil semua audit yang pernah dikerjakan auditor ini
        // Kita ambil data sesuai inputan 'test-form' (scope, objective, type, dll)
        $history = Audit::with(['department']) // load relasi departemen
                        ->where('auditor_name', $auditor->name) 
                        ->orderBy('created_at', 'desc')
                        ->get();

        // Statistik Ringkas
        $stats = [
            'total' => $history->count(),
            'regular' => $history->where('audit_type', 'Regular')->count(),
            'special' => $history->where('audit_type', 'Special')->count(),
            'followup' => $history->where('audit_type', 'FollowUp')->count(),
        ];

        return view('admin.auditors.show', compact('auditor', 'history', 'stats'));
    }
}
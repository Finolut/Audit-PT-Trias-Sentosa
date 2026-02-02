<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TokenSessionController extends Controller
{
public function index()
{
    $sessions = AuditSession::where('is_parent', true)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.token-sessions.index', compact('sessions'));
}


    public function store(Request $request)
    {
        $request->validate([
            'auditor_name' => 'required|string|max:255',
            'auditor_email' => 'required|email',
            'auditor_nik' => 'required|string|max:50',
            'auditor_department' => 'required|string|max:255',
        ]);

        AuditSession::create([
            'auditor_name' => $request->auditor_name,
            'auditor_email' => $request->auditor_email,
            'auditor_nik' => $request->auditor_nik,
            'auditor_department' => $request->auditor_department,
            'resume_token' => Str::random(12),
        ]);

        return back()->with('success', 'Sesi token berhasil dibuat.');
    }

    public function update(Request $request, AuditSession $session)
    {
        $request->validate([
            'auditor_name' => 'required|string|max:255',
            'auditor_email' => 'required|email',
            'auditor_nik' => 'required|string|max:50',
            'auditor_department' => 'required|string|max:255',
        ]);

        $session->update($request->only([
            'auditor_name',
            'auditor_email',
            'auditor_nik',
            'auditor_department'
        ]));

        return back()->with('success', 'Data sesi berhasil diperbarui.');
    }

    public function regenerateToken(AuditSession $session)
    {
        $session->update(['resume_token' => Str::random(12)]);
        return back()->with('success', 'Token berhasil diperbarui.');
    }

    public function destroy(AuditSession $session)
    {
        $session->delete();
        return back()->with('success', 'Sesi token berhasil dihapus.');
    }
}
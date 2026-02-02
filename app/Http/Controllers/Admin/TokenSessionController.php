<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TokenSessionController extends Controller
{
    public function index()
    {
        $sessions = AuditSession::where('is_parent', true)
            ->orderBy('created_at', 'desc')
            ->get();

        // Tambahkan atribut dinamis untuk view
        $sessions->each(function ($session) {
            if ($session->resume_token_expires_at) {
                $session->remaining_days = max(0, $session->resume_token_expires_at->diffInDays(now(), false));
                $session->is_expired = !$session->resume_token_expires_at->isFuture();
            } else {
                $session->remaining_days = null;
                $session->is_expired = false;
            }
        });

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
            'resume_token_expires_at' => now()->addDays(7), // default 7 hari
        ]);

        return back()->with('success', 'Sesi token berhasil dibuat (berlaku 7 hari).');
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
        $session->update([
            'resume_token' => Str::random(12),
            'resume_token_expires_at' => now()->addDays(7)
        ]);
        return back()->with('success', 'Token dan masa berlaku diperbarui (7 hari).');
    }

    public function destroy(AuditSession $session)
    {
        $session->delete();
        return back()->with('success', 'Sesi token berhasil dihapus.');
    }

    // 🔹 HALAMAN PERPANJANG TOKEN
    public function showExtendForm(AuditSession $session)
    {
        return view('admin.token-sessions.extend', compact('session'));
    }

    // 🔹 PROSES PERPANJANGAN
    // 🔹 PROSES PERPANJANGAN
public function extendToken(Request $request, AuditSession $session)
{
    $request->validate([
        'days' => 'required|integer|min:1|max:365' // ← perbaikan di sini
    ]);

    $newExpiry = now()->addDays((int) $request->days);
    $session->update(['resume_token_expires_at' => $newExpiry]);

    return redirect()->route('admin.token-sessions.index')
        ->with('success', "Token diperpanjang {$request->days} hari. Berlaku hingga " . $newExpiry->format('d M Y'));
}
}
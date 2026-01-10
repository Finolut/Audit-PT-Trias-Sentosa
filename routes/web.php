<?php

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\AuditController;

Route::get('/', function () {
    return view('test-form');
});

/* PROSES FORM */
Route::get('/test-form', function () {
    $departments = DB::table('departments')->orderBy('name')->get();
    return view('test-form', compact('departments'));
});

/* PROSES FORM IDENTITAS */
Route::post('/test-form', function (Request $request) {

    $request->validate([
        'auditor_name' => 'required|string',
        'auditor_department' => 'required|string',
        'audit_date' => 'required|date',
        'department_id' => 'required|uuid', // ⬅️ WAJIB
    ]);

    // =========================
    // 1. SIMPAN AUDIT SESSION
    // =========================
    $sessionId = Str::uuid();

    DB::table('audit_sessions')->insert([
        'id' => $sessionId,
        'auditor_name' => $request->auditor_name,
        'auditor_nik' => $request->auditor_nik,
        'auditor_department' => $request->auditor_department,
        'audit_date' => $request->audit_date,
        'created_at' => now()
    ]);

    // =========================
    // 2. SIMPAN AUDIT (INI YANG TADI ERROR)
    // =========================
    $auditId = Str::uuid();

    DB::table('audits')->insert([
        'id' => $auditId,
        'audit_session_id' => $sessionId,
        'department_id' => $request->department_id, // ✅ TIDAK NULL
        'status' => 'in_progress',
        'created_at' => now()
    ]);

    // =========================
    // 3. SIMPAN RESPONDER (OPSIONAL)
    // =========================
    if ($request->has('responders')) {
        foreach ($request->responders as $responder) {
            if (!empty($responder['name'])) {
                DB::table('audit_responders')->insert([
                    'id' => Str::uuid(),
                    'audit_session_id' => $sessionId,
                    'responder_name' => $responder['name'],
                    'responder_department' => $responder['department'] ?? null,
                    'responder_nik' => $responder['nik'] ?? null,
                    'created_at' => now()
                ]);
            }
        }
    }

    // =========================
    // 4. REDIRECT KE AUDIT 4.1
    // =========================
    return redirect("/audit/{$auditId}/4-1");
});

Route::get('/api/auditors/search', function (Request $request) {
    return DB::table('users')
        ->where('role', 'auditor')
        ->where('name', 'ILIKE', '%' . $request->q . '%')
        ->select('name', 'nik', 'department')
        ->limit(10)
        ->get();
});

Route::post('/audit/{audit}/4-1/submit', function ($auditId) {

    // tandai audit 4.1 selesai
    DB::table('audit_sessions')
        ->where('id', $auditId)
        ->update([
            'audit_41_completed_at' => now()
        ]);

    return response()->json([
        'status' => 'ok',
        'message' => 'Audit 4.1 berhasil disimpan'
    ]);
});

// Halaman Depan
Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard');

// Level 1: List Audit per Dept
Route::get('/dept/{id}', [DashboardController::class, 'showDepartment'])->name('dept.show');

// Level 2: List Klausul dalam Audit (Ganti route detail yang lama)
Route::get('/audit/{id}/overview', [DashboardController::class, 'showAuditOverview'])->name('audit.overview');

// Level 3: Detail Soal per Klausul + Diagram
Route::get('/audit/{id}/clause/{clause_id}', [DashboardController::class, 'showClauseDetail'])->name('audit.clause');

// 1. Halaman Setup Awal (Mengakses fungsi setup di Controller)
Route::get('/audit/setup', [AuditController::class, 'setup'])->name('audit.setup');

// 2. Proses Mulai Audit (POST Form Setup)
Route::post('/audit/start', [AuditController::class, 'startAudit'])->name('audit.start');

// 3. Halaman Soal Audit (Dinamis per Clause)
Route::get('/audit/{id}/{clause}', [AuditController::class, 'show'])->name('audit.show');

// 4. Simpan Jawaban Soal
Route::post('/audit/{id}/{clause}', [AuditController::class, 'store'])->name('audit.store');

// 5. Halaman Selesai
Route::get('/audit/finish', function() {
    return "<h1 style='text-align:center; margin-top:50px;'>Terima Kasih, Audit Selesai!</h1>";
})->name('audit.finish');

Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return "Koneksi Berhasil ke: " . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return "Gagal konek database: " . $e->getMessage();
    }
});

/* -------------------------------------------------------------------------- */
/* WEB ROUTES                                */
/* -------------------------------------------------------------------------- */


// 2. API: Cek apakah Auditor punya audit yang belum selesai
Route::post('/audit/check-resume', [AuditController::class, 'checkPendingAudit'])->name('audit.check_resume');

// 5. Halaman Finish
Route::get('/audit/finish', function() {
    return "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
            <h1 style='color:green;'>Terima Kasih!</h1>
            <p>Audit telah selesai disimpan.</p>
            <a href='/audit/setup'>Kembali ke Menu Awal</a>
            </div>";
})->name('audit.finish');

// Redirect root ke setup
Route::get('/', function () { return redirect()->route('audit.setup'); });
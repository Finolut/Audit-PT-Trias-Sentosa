<?php

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminAuditorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EvidencesController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\UnableToReadFile;
use App\Http\Controllers\Admin\AuditFindingLogController; // ✅ Tambahkan ini

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Bisa diakses siapa saja)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome'); 
})->name('landing');

// LOGIN/LOGOUT SYSTEM
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/* PROSES FORM LAMA (Tetap Dipertahankan) */
Route::get('/test-form', function () {
    $departments = DB::table('departments')->orderBy('name')->get();
    return view('test-form', compact('departments'));
});

Route::post('/test-form', function (Request $request) {
    $request->validate([
        'auditor_name' => 'required|string',
        'auditor_department' => 'required|string',
        'audit_date' => 'required|date',
        'department_id' => 'required|uuid',
    ]);

    $sessionId = Str::uuid();
    DB::table('audit_sessions')->insert([
        'id' => $sessionId,
        'auditor_name' => $request->auditor_name,
        'auditor_nik' => $request->auditor_nik,
        'auditor_department' => $request->auditor_department,
        'audit_date' => $request->audit_date,
        'created_at' => now()
    ]);

    $auditId = Str::uuid();
    DB::table('audits')->insert([
        'id' => $auditId,
        'audit_session_id' => $sessionId,
        'department_id' => $request->department_id,
        'status' => 'in_progress',
        'created_at' => now()
    ]);

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
    return redirect("/audit/{$auditId}/4-1");
});

/*
|--------------------------------------------------------------------------
| AUDIT PROCESS ROUTES (Public / Khusus Auditor lapangan)
|--------------------------------------------------------------------------

*/
// 1. Route untuk MENAMPILKAN halaman konfirmasi resume (pakai GET)
// 1. Halaman Input Token (GET)
Route::get('/audit/resume/form', [AuditController::class, 'showResumePage'])
    ->name('audit.resume.form');

// 2. Proses Validasi Token dari Form Input (POST)
Route::post('/audit/resume/validate', [AuditController::class, 'processTokenInput'])
    ->name('audit.resume.validate');

// 3. Tampilkan Halaman Keputusan (GET) - INI YANG ANDA BUTUHKAN
Route::get('/audit/resume/decision/{token}', [AuditController::class, 'showDecisionPage'])
    ->name('audit.resume.decision');

// 4. Eksekusi Keputusan (Lanjut/Batal) (POST)
Route::post('/audit/resume/action', [AuditController::class, 'handleResumeDecision'])
    ->name('audit.resume.action');
Route::get('/audit/setup', [AuditController::class, 'setup'])->name('audit.setup');
Route::get('/audit/create', [AuditController::class, 'createAudit'])->name('audit.create');
Route::post('/audit/start', [AuditController::class, 'startAudit'])->name('audit.start');
Route::post('/audit/check-resume', [AuditController::class, 'checkPendingAudit'])->name('audit.check_resume');
Route::get('/audit/menu/{id}', [AuditController::class, 'menu'])->name('audit.menu');
Route::get('/audit/{id}/select-department', [AuditController::class, 'selectDepartment'])->name('audit.select_department');
Route::post('/audit/{id}/set-department', [AuditController::class, 'setActiveDepartment'])->name('audit.set_department');

// Dynamic Clause Routes (UUID validation)
Route::get('/audit/{id}/{clause}', [AuditController::class, 'show'])
    ->name('audit.show')
    ->where('id', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

Route::post('/audit/{id}/{clause}', [AuditController::class, 'store'])->name('audit.store');

// Special Submission Routes
Route::post('/audit/save-ajax', [AuditController::class, 'saveAjax'])->name('audit.saveAjax');
Route::post('/audit/save-sub-clause', [AuditController::class, 'saveSubClause']);
Route::post('/audit/{audit}/4-1/submit', function ($auditId) {
    DB::table('audit_sessions')->where('id', $auditId)->update(['audit_41_completed_at' => now()]);
    return response()->json(['status' => 'ok', 'message' => 'Audit 4.1 berhasil disimpan']);
});

Route::post('/audit/{id}/final-submit', [AuditController::class, 'finalSubmit'])
    ->name('audit.final_submit');

Route::get('/audit/finish', function() {
    return view('audit.thanks');
})->name('audit.finish');

/*
|--------------------------------------------------------------------------
| ADMIN AREA (Protected by Middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    
    // DASHBOARD & LOGS
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/question-log', [DashboardController::class, 'questionLog'])->name('question_log');
    Route::get('/audit/search', [DashboardController::class, 'searchAudit'])->name('audit.search');
    Route::get('audit/day-details', [DashboardController::class, 'getDayDetails'])
        ->name('audit.day-details');
    Route::get('/audit/{auditId}', [DashboardController::class, 'showAuditOverview'])->name('audit.overview');
    Route::get('/audit/{auditId}/clause/{mainClause}', [DashboardController::class, 'showClauseDetail'])->name('audit.clause_detail');
    Route::get('/department-status', [DashboardController::class, 'departmentStatusIndex'])->name('dept_status');
    Route::get('/department/{deptId}', [DashboardController::class, 'showDepartment'])->name('dept.show');

    // EXPORT PDF
    Route::get('/audit/{auditId}/export-pdf', [DashboardController::class, 'exportToPdf'])->name('audit.export.pdf');

    // USER & AUDITOR MANAGEMENT
    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::get('/auditors/{id}', [AdminAuditorController::class, 'show'])->name('auditors.show');
    Route::delete('/auditors/{id}', [AdminAuditorController::class, 'destroy'])->name('auditors.destroy');

    // ITEMS MANAGEMENT
    Route::resource('items', ItemController::class);

    Route::get('/cari-laporan', function () {
        return view('admin.search-report');
    })->name('search.report');

    // Pastikan route search tetap ada
    Route::get('/admin/audit/search', [\App\Http\Controllers\AuditController::class, 'search'])
         ->name('admin.audit.search');

    Route::get('/admin/items/create', [ItemController::class, 'create'])->name('admin.items.create');
    Route::post('/admin/items', [ItemController::class, 'store'])->name('admin.items.store');

    // ✅ ROUTE BARU: Catatan Temuan Audit
    Route::get('/audit/findings', [AuditFindingLogController::class, 'index'])
         ->name('audit.finding-logs');
});
// Preserved special routes (public)
Route::get('/audit/thanks', function () {
    return view('audit.thanks');
})->name('audit.thanks');


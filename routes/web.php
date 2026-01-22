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

/*
|--------------------------------------------------------------------------
| LANDING & INITIAL ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome'); 
})->name('landing');

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
| ADMIN AREA (DASHBOARD & LOGS)
|--------------------------------------------------------------------------
*/

// Gunakan as('admin.') agar semua rute di dalam otomatis punya awalan nama 'admin.'
Route::prefix('admin')->as('admin.')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/question-log', [DashboardController::class, 'questionLog'])->name('question_log');
    Route::get('/audit/search', [DashboardController::class, 'searchAudit'])->name('audit.search');

    // Route Detail Audit (Gunakan satu saja)
    Route::get('/audit/{auditId}', [DashboardController::class, 'showAuditOverview'])->name('audit.overview');

    Route::get('/audit/{auditId}/clause/{mainClause}', [DashboardController::class, 'showClauseDetail'])->name('audit.clause_detail');
    Route::get('/department-status', [DashboardController::class, 'departmentStatusIndex'])->name('dept_status');
    
    // Route untuk Department (Tetap gunakan dept.show jika sudah terlanjur banyak dipakai)
    Route::get('/department/{deptId}', [DashboardController::class, 'showDepartment'])->withoutMiddleware([])->name('dept.show');
});

/*
|--------------------------------------------------------------------------
| AUDIT PROCESS ROUTES (SURVEY)
|--------------------------------------------------------------------------
*/

// Setup & Start
Route::get('/audit/setup', [AuditController::class, 'setup'])->name('audit.setup');
Route::post('/audit/start', [AuditController::class, 'startAudit'])->name('audit.start');
Route::post('/audit/check-resume', [AuditController::class, 'checkPendingAudit'])->name('audit.check_resume');

// Menu & Soal Dinamis
Route::get('/audit/menu/{id}', [AuditController::class, 'menu'])->name('audit.menu');

// Route Show & Store (Gunakan regex UUID agar tidak bentrok)
Route::get('/audit/{id}/{clause}', [AuditController::class, 'show'])
    ->name('audit.show')
    ->where('id', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

Route::post('/audit/{id}/{clause}', [AuditController::class, 'store'])->name('audit.store');

// AJAX & Submit Khusus
Route::post('/audit/save-ajax', [AuditController::class, 'saveAjax'])->name('audit.saveAjax');
Route::post('/audit/save-sub-clause', [AuditController::class, 'saveSubClause']);
Route::post('/audit/{audit}/4-1/submit', function ($auditId) {
    DB::table('audit_sessions')->where('id', $auditId)->update(['audit_41_completed_at' => now()]);
    return response()->json(['status' => 'ok', 'message' => 'Audit 4.1 berhasil disimpan']);
});

// Finish
Route::get('/audit/finish', function() {
    return "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
            <h1 style='color:green;'>Terima Kasih!</h1>
            <p>Audit telah selesai disimpan.</p>
            <a href='/audit/setup'>Kembali ke Menu Awal</a>
            </div>";
})->name('audit.finish');

/*
|--------------------------------------------------------------------------
| SYSTEM UTILITIES
|--------------------------------------------------------------------------
*/

Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return "Koneksi Berhasil ke: " . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return "Gagal konek database: " . $e->getMessage();
    }
});

Route::get('/admin/department-status', [DashboardController::class, 'departmentStatusIndex'])->name('admin.dept.status_index');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('items', ItemController::class);
});

Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
Route::post('/admin/users/store', [AdminUserController::class, 'store'])->name('admin.users.store');

// Group Admin
Route::prefix('admin')->name('admin.')->group(function () {
    // ... route lain ...
    
    // Route Khusus Monitoring Auditor
    Route::get('/auditors', [AdminAuditorController::class, 'index'])->name('auditors.index');
    Route::get('/auditors/{id}', [AdminAuditorController::class, 'show'])->name('auditors.show');
});

Route::delete('/admin/auditors/{id}', [AdminAuditorController::class, 'destroy'])->name('admin.auditors.destroy');

Route::post('/audit/{id}/final-submit', [AuditController::class, 'finalSubmit'])
    ->name('audit.final_submit');

    // HANYA ROUTE PDF
Route::get('admin/audit/{auditId}/export-pdf', [DashboardController::class, 'exportToPdf'])
    ->name('admin.audit.export.pdf');
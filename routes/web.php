<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuditController;

/*
|--------------------------------------------------------------------------
| Web Routes - Client / Auditor Side
|--------------------------------------------------------------------------
*/

// Redirect root ke halaman setup awal
Route::get('/', function () { 
    return redirect()->route('audit.setup'); 
});

// Alur Mulai Audit
Route::get('/audit/setup', [AuditController::class, 'setup'])->name('audit.setup');
Route::post('/audit/start', [AuditController::class, 'startAudit'])->name('audit.start');
Route::post('/audit/check-resume', [AuditController::class, 'checkPendingAudit'])->name('audit.check_resume');

// Halaman Menu & Kuesioner (Dinamis per Clause)
Route::get('/audit/menu/{id}', [AuditController::class, 'menu'])->name('audit.menu');
Route::get('/audit/{id}/{clause}', [AuditController::class, 'show'])->name('audit.show');
Route::post('/audit/{id}/{clause}', [AuditController::class, 'store'])->name('audit.store');

// Halaman Selesai
Route::get('/audit/finish', function() {
    return view('audit.finish'); // Pastikan Anda buat view ini atau gunakan inline HTML seperti sebelumnya
})->name('audit.finish');


/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    // Dashboard Utama
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // List Audit per Departemen
    Route::get('/dept/{id}', [DashboardController::class, 'showDepartment'])->name('dept.show');

    // Overview Klausul dalam satu Audit
    Route::get('/audit/{id}', [DashboardController::class, 'showAuditOverview'])->name('audit.overview');

    // Detail Ringkasan per Main Clause (Struktur baru: Clause 4, 5, 6 dst)
    Route::get('/audit/{id}/clause/{mainClause}', [DashboardController::class, 'showClauseDetail'])->name('audit.clause');
});


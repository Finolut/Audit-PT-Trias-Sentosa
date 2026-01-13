<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuditController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| File ini mengatur seluruh routing aplikasi Anda.
| Struktur:
| 1. Landing Page (Public)
| 2. Admin Dashboard (Protected/Admin)
| 3. Proses Audit (User/Auditor)
| 4. System/Testing
|
*/

// =========================================================================
// 1. LANDING PAGE & PUBLIC
// =========================================================================

// Halaman Depan (Landing Page dengan tombol Admin & Survey)
Route::get('/', function () {
    return view('welcome');
})->name('landing');

// =========================================================================
// 2. ADMIN DASHBOARD AREA
// =========================================================================

Route::prefix('admin')->group(function () {
    
    // Dashboard Utama (Ringkasan Statistik Seluruh Departemen)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    // Detail Log Audit per Departemen (Tabel List Audit)
    Route::get('/department/{deptId}', [DashboardController::class, 'showDepartment'])
        ->name('admin.department.show');

    // Overview Hasil Audit Spesifik (Grafik Radar & Doughnut)
    Route::get('/audit/{auditId}', [DashboardController::class, 'showAuditOverview'])
        ->name('audit.overview');

    // Detail Per Klausul Utama (Tabel Detail & Stacked Bar Chart)
    // Contoh URL: /admin/audit/{uuid}/clause/4
    Route::get('/audit/{auditId}/clause/{mainClause}', [DashboardController::class, 'showClauseDetail'])
        ->name('audit.clause_detail');
});

// =========================================================================
// 3. AUDIT PROCESS (SURVEY AUDITOR)
// =========================================================================

Route::prefix('audit')->group(function () {

    // --- A. Setup & Inisiasi ---
    
    // Halaman Form Identitas Auditor
    Route::get('/setup', [AuditController::class, 'setup'])
        ->name('audit.setup');

    // Proses Submit Form Identitas (Membuat Sesi Audit)
    Route::post('/start', [AuditController::class, 'startAudit'])
        ->name('audit.start');

    // Cek apakah ada audit yang tertunda (Resume feature)
    Route::post('/check-resume', [AuditController::class, 'checkPendingAudit'])
        ->name('audit.check_resume');


    // --- B. Menu & Navigasi ---

    // Halaman Menu Utama Audit (Daftar Klausul)
    Route::get('/menu/{id}', [AuditController::class, 'menu'])
        ->name('audit.menu');


    // --- C. Halaman Akhir ---

    // Halaman Terima Kasih / Selesai
    Route::get('/finish', function() {
        return view('audit.finish'); 
        // Pastikan Anda punya file resources/views/audit/finish.blade.php
        // Atau return string sederhana jika belum ada blade-nya:
        // return "<div style='text-align:center; margin-top:50px;'><h1>Audit Selesai!</h1><a href='/'>Kembali</a></div>";
    })->name('audit.finish');


    // --- D. Simpan Jawaban (AJAX) ---
    
    // Simpan jawaban via AJAX (Auto-save)
    Route::post('/save-ajax', [AuditController::class, 'saveAjax'])
        ->name('audit.saveAjax');
    
    // Simpan sub-klausul (jika ada logika khusus)
    Route::post('/save-sub-clause', [AuditController::class, 'saveSubClause']);


    // --- E. Halaman Soal (Wildcard Routes) ---
    // PENTING: Route ini harus diletakkan PALING BAWAH di dalam grup 'audit'
    // agar tidak menimpa route spesifik seperti '/audit/setup' atau '/audit/finish'.

    // Menampilkan Halaman Soal per Klausul (Contoh: /audit/{uuid}/4.1)
    Route::get('/{id}/{clause}', [AuditController::class, 'show'])
        ->where('id', '[0-9a-fA-F\-]{36}') // Validasi UUID agar lebih aman
        ->name('audit.show');

    // Menyimpan Jawaban Soal (Form Submit Biasa)
    Route::post('/{id}/{clause}', [AuditController::class, 'store'])
        ->name('audit.store');
});

// =========================================================================
// 4. SYSTEM & TESTING
// =========================================================================

// Route untuk mengetes koneksi database (Hapus saat production)
Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return "Koneksi Database Berhasil ke: " . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return "Gagal koneksi database: " . $e->getMessage();
    }
});
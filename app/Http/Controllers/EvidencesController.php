<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class EvidencesController extends Controller
{
   public function evidenceLog()
{
    $evidences = DB::table('answer_evidences')
        ->join('answers', 'answer_evidences.answer_id', '=', 'answers.id')
        ->join('items', 'answers.item_id', '=', 'items.id')
        ->join('clauses', 'items.clause_id', '=', 'clauses.id')
        ->join('audits', 'answer_evidences.audit_id', '=', 'audits.id')
        ->join('departments', 'audits.department_id', '=', 'departments.id')
        ->select([
            'answer_evidences.id as evidence_id',
            'answer_evidences.file_path',
            'answer_evidences.created_at as evidence_time',
            'answers.answer',
            'answers.finding_level',
            'answers.auditor_name',
            'items.item_text',
            'clauses.clause_code',
            'departments.name as department_name',
            'audits.id as audit_id',
        ])
        ->orderByDesc('answer_evidences.created_at')
        ->paginate(15);

    // 🔑 INI YANG KAMU LUPA
    $departments = DB::table('departments')
        ->orderBy('name')
        ->get();

    return view('admin.evidence_log', compact('evidences', 'departments'));
}

public function showImage($id)
{
    // 1. Ambil data dari database
    $evidence = DB::table('answer_evidences')->where('id', $id)->first();

    if (!$evidence) {
        abort(404, 'Data evidence tidak ditemukan.');
    }

    // 2. Logika Path yang Lebih Aman (Cek dulu path asli, baru fallback ke prefix)
    $disk = Storage::disk('s3');
    $path = ltrim($evidence->file_path, '/'); // Hilangkan slash di depan jika ada

    // Skenario A: Cek path apa adanya (sesuai database)
    if (!$disk->exists($path)) {
        // Skenario B: Jika tidak ada, coba tambah folder 'pttrias/' (Fallback)
        $altPath = 'pttrias/' . $path;
        
        if ($disk->exists($altPath)) {
            $path = $altPath;
        } else {
            // DEBUG: Jika masih develop, uncomment baris bawah ini untuk lihat path yang dicari
            // dd("File tidak ketemu. Path dicari: $path DAN $altPath");
            abort(404, 'File fisik tidak ditemukan di Storage S3.');
        }
    }

    // 3. Ambil File & Tentukan Mime Type
    try {
        $fileContent = $disk->get($path);
        
        // Prioritaskan mime_type dari database (lihat screenshot skema DB Anda), 
        // jika kosong baru cek fisik file.
        $contentType = $evidence->mime_type ?? $disk->mimeType($path) ?? 'image/jpeg';

    } catch (\Exception $e) {
        // Log error asli agar Anda tahu kenapa gagal (misal: AccessDenied)
        \Illuminate\Support\Facades\Log::error("Gagal ambil gambar S3: " . $e->getMessage());
        abort(404);
    }

    // 4. Return Response yang Benar
    return Response::make($fileContent, 200, [
        'Content-Type' => $contentType,
        'Content-Disposition' => 'inline', // Agar tampil di browser, bukan download
        'Cache-Control' => 'public, max-age=86400', // Cache browser 1 hari biar cepat
    ]);
}

}
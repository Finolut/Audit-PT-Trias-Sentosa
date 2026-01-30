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
        abort(404, 'Data evidence tidak ditemukan di database.');
    }

    // 2. Tentukan Path (Hati-hati dengan prefix folder)
    $path = $evidence->file_path;
    
    // Cek apakah file ada di path asli dulu
    if (!Storage::disk('s3')->exists($path)) {
        // Jika tidak ada, baru coba tambahkan prefix 'pttrias/' (Fallback)
        $pathWithPrefix = 'pttrias/' . ltrim($path, '/');
        
        if (Storage::disk('s3')->exists($pathWithPrefix)) {
            $path = $pathWithPrefix;
        } else {
            // Debugging: Jika masih tidak ketemu, matikan abort dan tampilkan path yang dicari
            // return "File tidak ditemukan di S3. Path dicari: " . $path . " ATAU " . $pathWithPrefix;
            abort(404, 'File fisik tidak ditemukan di S3.');
        }
    }

    // 3. Ambil Konten File
    try {
        $fileContent = Storage::disk('s3')->get($path);
        $mimeType = Storage::disk('s3')->mimeType($path);
    } catch (\Exception $e) {
        abort(404, 'Gagal mengambil file dari S3: ' . $e->getMessage());
    }

    // 4. Return Response dengan MIME Type yang dinamis (Bukan hardcoded jpeg)
    // Gunakan mime_type dari DB jika ada, atau dari Storage
    $contentType = $evidence->mime_type ?? $mimeType ?? 'image/jpeg';

    return Response::make($fileContent, 200, [
        'Content-Type' => $contentType,
        'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        'Cache-Control' => 'public, max-age=86400', // Cache 1 hari agar loading cepat
    ]);
}

}
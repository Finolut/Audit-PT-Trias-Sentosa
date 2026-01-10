<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AuditController extends Controller
{
    // Daftar urutan klausul untuk navigasi otomatis
    private $clauseOrder = ['4.1', '4.2', '4.3', '4.4', '5.1', '5.2','5.3','6.1.1','6.1.2','6.1.3','6.1.4','6.2.1','6.2.2','7.1','7.2','7.3','7.4','8.1','8.2','9.1.1','9.1.2', '9.2.1 & 9.2.2', '9.3','10.1','10.2','10.3'];

    // ==========================================
    // 1. FORM SETUP (Halaman Awal) - INI YANG TADI HILANG
    // ==========================================
    public function setup()
    {
        // Ambil data departemen dari database
        $departments = DB::table('departments')->get();

        // Kirim data ke view 'test-form.blade.php'
        return view('test-form', compact('departments'));
    }

   // ==========================================
    // 2. MULAI AUDIT (Proses Form Awal)
    // ==========================================
    public function startAudit(Request $request) 
    {
        return DB::transaction(function () use ($request) {
            // A. Simpan Session Audit (Info Auditor)
            $sessionId = (string) Str::uuid();
            
            // Perbaikan: Menghapus 'updated_at' karena tidak ada di tabel audit_sessions
            DB::table('audit_sessions')->insert([
                'id' => $sessionId,
                'auditor_name'       => $request->auditor_name,
                'auditor_nik'        => $request->auditor_nik,
                'auditor_department' => $request->auditor_department,
                'audit_date'         => $request->audit_date, // Kolom ini ada di gambar
                'created_at'         => now(),
            ]);

            // B. Simpan Responders (Jika ada)
            if ($request->has('responders')) {
                foreach ($request->responders as $resp) {
                    if (!empty($resp['name'])) { 
                        DB::table('audit_responders')->insert([
                            'id'               => (string) Str::uuid(),
                            'audit_session_id' => $sessionId,
                            'responder_name'   => $resp['name'],
                            'responder_department'   => $resp['department'] ?? null,
                            'responder_nik'    => $resp['nik'] ?? null,
                            'created_at'       => now(),
                        ]);
                    }
                }
            }

            // C. Buat Record Audit Utama
            $newAuditId = (string) Str::uuid();
            
            // Perbaikan: Menghapus 'updated_at' karena tidak ada di tabel audits
            DB::table('audits')->insert([
                'id'               => $newAuditId,
                'audit_session_id' => $sessionId,
                'department_id'    => $request->department_id,
                'status'           => 'IN_PROGRESS',
                'created_at'       => now(),
            ]);

            // D. Redirect ke Klausul Pertama (4.1)
            return redirect()->route('audit.show', ['id' => $newAuditId, 'clause' => '4.1']);
        });
    }

    // ==========================================
    // 3. TAMPILKAN SOAL (Dinamis per Klausul)
    // ==========================================
    public function show($auditId, $clause)
{
    $audit = DB::table('audits')->where('id', $auditId)->first();
    abort_if(!$audit, 404);

    // 1. Cari ID Klausul berdasarkan kode (misal: '4.1')
    $clauseData = DB::table('clauses')->where('clause_code', $clause)->first();
    abort_if(!$clauseData, 404);

    // 2. Ambil semua item yang berhubungan dengan klausul ini
    $itemsRaw = DB::table('items')
        ->where('clause_id', $clauseData->id)
        ->orderBy('item_order')
        ->get();

    // 3. Ambil Maturity Levels yang hanya berhubungan dengan item di klausul ini
    // Diurutkan berdasarkan level_number sesuai skema
    $maturityLevels = DB::table('maturity_levels')
        ->whereIn('id', $itemsRaw->pluck('maturity_level_id'))
        ->orderBy('level_number')
        ->get();

    // 4. Kelompokkan item berdasarkan maturity_level_id untuk dikirim ke view
    $items = $itemsRaw->groupBy('maturity_level_id');

    // ... sisa kode lainnya (session, responders, existingQuestion) ...
    $session = DB::table('audit_sessions')->where('id', $audit->audit_session_id)->first();
    $responders = DB::table('audit_responders')->where('audit_session_id', $session->id)->get();
    
    $existingQuestion = DB::table('audit_questions')
        ->where('audit_id', $auditId)
        ->where('clause_code', $clause)
        ->value('question_text');

    $currentIndex = array_search($clause, $this->clauseOrder);
    $nextClause = $this->clauseOrder[$currentIndex + 1] ?? null;

    return view('audit.questionnaire', [
        'auditId'          => $auditId,
        'clause'           => $clause,
        'nextClause'       => $nextClause,
        'auditorName'      => $session->auditor_name,
        'responders'       => $responders,
        'maturityLevels'   => $maturityLevels,
        'items'            => $items,
        'existingQuestion' => $existingQuestion,
    ]);
}

    // ==========================================
    // 4. SIMPAN JAWABAN & LANJUT
    // ==========================================
    public function store(Request $request, $auditId, $clause)
    {
        $answers = $request->input('answers', []);
        $questionText = $request->input('audit_question');

        DB::transaction(function () use ($answers, $auditId, $clause, $questionText) {
            $audit = DB::table('audits')->where('id', $auditId)->first();

            // A. Simpan Pertanyaan/Catatan Klausul
            if ($questionText !== null) {
                DB::table('audit_questions')->updateOrInsert(
                    ['audit_id' => $auditId, 'clause_code' => $clause],
                    [
                        'id' => (string) Str::uuid(),
                        'department_id' => $audit->department_id,
                        'question_text' => $questionText,
                        'updated_at' => now(),
                        'created_at' => now(), 
                    ]
                );
            }

            // B. Simpan Jawaban Item
            foreach ($answers as $itemId => $data) {
                DB::table('answers')->where('audit_id', $auditId)->where('item_id', $itemId)->delete();
                DB::table('answer_responders')->where('audit_id', $auditId)->where('item_id', $itemId)->delete();

                if (!empty($data['answer']) && !empty($data['auditor_name'])) {
                    DB::table('answers')->insert([
                        'id' => (string) Str::uuid(),
                        'audit_id' => $auditId,
                        'item_id' => $itemId,
                        'auditor_name' => $data['auditor_name'],
                        'answer' => $data['answer'],
                        'answered_at' => now(),
                    ]);
                }

                if (!empty($data['responders'])) {
                    foreach ($data['responders'] as $responderName => $responderAnswer) {
                        DB::table('answer_responders')->insert([
                            'id' => (string) Str::uuid(),
                            'audit_id' => $auditId,
                            'item_id' => $itemId,
                            'responder_name' => $responderName,
                            'answer' => $responderAnswer,
                            'answered_at' => now(),
                        ]);
                    }
                }

                $this->calculateFinalAnswer($auditId, $itemId);
            }
        });

        // C. Redirect ke Klausul Berikutnya
        $currentIndex = array_search($clause, $this->clauseOrder);
        if (isset($this->clauseOrder[$currentIndex + 1])) {
            $next = $this->clauseOrder[$currentIndex + 1];
            return redirect()->route('audit.show', ['id' => $auditId, 'clause' => $next])
                             ->with('success', "Klausul $clause berhasil disimpan.");
        }

        return redirect('/audit/finish')->with('success', 'Seluruh Audit Selesai!');
    }

    // ==========================================
    // 5. LOGIC HITUNG HASIL
    // ==========================================
    private function calculateFinalAnswer($auditId, $itemId)
    {
        $yes = 0; $no = 0;

        $auditorAnswer = DB::table('answers')
            ->where('audit_id', $auditId)->where('item_id', $itemId)->value('answer');

        if ($auditorAnswer === 'YES') $yes++;
        if ($auditorAnswer === 'NO')  $no++;

        $responderAnswers = DB::table('answer_responders')
            ->where('audit_id', $auditId)->where('item_id', $itemId)->pluck('answer');

        foreach ($responderAnswers as $ans) {
            if ($ans === 'YES') $yes++;
            if ($ans === 'NO')  $no++;
        }

        $finalYes = 0; $finalNo = 0;
        if ($yes > 0 || $no > 0) {
            if ($yes > $no) { $finalYes = 1; $finalNo = 0; }
            elseif ($no > $yes) { $finalYes = 0; $finalNo = 1; }
            else { $finalYes = 1; $finalNo = 1; }
        }

        DB::table('answer_finals')->updateOrInsert(
            ['audit_id' => $auditId, 'item_id' => $itemId],
            [
                'yes_count'  => $yes,
                'no_count'   => $no,
                'final_yes'  => $finalYes,
                'final_no'   => $finalNo,
                'decided_at' => now(),
            ]
        );
    }
    // AuditController.php
public function showForm() {
    try {
        $departments = DB::table('departments')->orderBy('name')->get();
        return view('test-form', compact('departments'));
    } catch (\Exception $e) {
        // Ini akan membantu kamu melihat error di log Vercel
        Log::error("Gagal ambil departemen: " . $e->getMessage());
        return view('test-form', ['departments' => []]);
    }
}
}
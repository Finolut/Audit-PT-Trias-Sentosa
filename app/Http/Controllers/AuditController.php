<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AuditController extends Controller
{
    // Daftar Klausul
    private $clauseOrder = ['4.1', '4.2', '4.3', '4.4', '5.1', '5.2','5.3','6.1.1','6.1.2','6.1.3','6.1.4','6.2.1','6.2.2','7.1','7.2','7.3','7.4','8.1','8.2','9.1.1','9.1.2', '9.2.1 & 9.2.2', '9.3','10.1','10.2','10.3'];

    // DATA AUDITOR (HARDCODED SESUAI GAMBAR UNTUK DROPDOWN)
    private $auditorsList = [
        ['name' => 'Joko Nofrianto', 'nik' => '2477', 'dept' => 'BOPET'],
        ['name' => 'Laurentius Kelik Dwi Ananta', 'nik' => 'N/A', 'dept' => 'Q A'], // NIK Kosong di gambar
        ['name' => 'Edy Setiono', 'nik' => '1631', 'dept' => 'Q A-Product Stewardship'],
        ['name' => 'Mugi Slamet Priyanto', 'nik' => '943', 'dept' => 'Q A'],
        ['name' => 'Satyo Ady Prihatno', 'nik' => '1522', 'dept' => 'QA'],
        ['name' => 'Adhi Setyo Budi', 'nik' => '1074', 'dept' => 'SSSE'],
        ['name' => 'Rizal Pratama Firyanto', 'nik' => '3077', 'dept' => 'SSSE'],
        ['name' => 'Brahmanto Anggoro Laksono', 'nik' => '3186', 'dept' => 'SSSE'],
        ['name' => 'Eko Susanto', 'nik' => '1421', 'dept' => 'BOPET'],
        ['name' => 'Yusriel Yahya Wahyu Lisandi', 'nik' => '3127', 'dept' => 'F E'],
        ['name' => 'M. Agung Wibowo', 'nik' => '2086', 'dept' => 'MANAGEMENT SYSTEM'],
        ['name' => 'Adhek Widyo Purnama', 'nik' => '3138', 'dept' => 'I A'],
        ['name' => 'Benediktus Wahyu Kurniawan', 'nik' => '2701', 'dept' => 'I A'],
        ['name' => 'Nedwin Lembar Hermavian', 'nik' => '3054', 'dept' => 'I A'],
        ['name' => 'Teguh Imam Santosa', 'nik' => '1891', 'dept' => 'G S'],
        ['name' => 'Dodod Wahjudhi', 'nik' => '1456', 'dept' => 'G S'],
        ['name' => 'Dhanny', 'nik' => '2135', 'dept' => 'ENGINEERING'],
        ['name' => 'Kasiyono', 'nik' => '1240', 'dept' => 'ENGINEERING'],
        ['name' => 'M. Nadif', 'nik' => '1002', 'dept' => 'R&D'],
        ['name' => 'Eko Saifudin Yulianto', 'nik' => '944', 'dept' => 'LOGISTICS'],
        ['name' => 'Lisa Santoso', 'nik' => '2319', 'dept' => 'PURCHASING'],
        ['name' => 'Fenny Maria Veronica Lukman', 'nik' => '2910', 'dept' => 'PURCHASING'],
        ['name' => 'Melisa', 'nik' => '2833', 'dept' => 'PURCHASING'],
        ['name' => 'Catur Putra Prajoko', 'nik' => 'N/A', 'dept' => 'R&D'], // NIK Kosong di gambar
        ['name' => 'Sari Dewi Cahyaning Tyas', 'nik' => '2615', 'dept' => 'R&D'],
        ['name' => 'Ahmad Solihudin', 'nik' => '3055', 'dept' => 'TTA'],
        ['name' => 'Fahrisal Surya Kusuma', 'nik' => '2605', 'dept' => 'MFG SUPPORT'],
        ['name' => 'Suhadak', 'nik' => '2148', 'dept' => 'MFG SUPPORT'],
        ['name' => 'Gunaryanto Cahyo Edi', 'nik' => '1511', 'dept' => 'PPIC'],
        ['name' => 'Mohamad Taufik', 'nik' => '991', 'dept' => 'THERMAL'],
        ['name' => 'Nanang Sugianto', 'nik' => '850', 'dept' => 'BOPP'],
        ['name' => 'Solikan', 'nik' => '1207', 'dept' => 'PROJECT'],
    ];

    // ==========================================
    // 1. FORM SETUP (Halaman Login/Awal)
    // ==========================================
    public function setup()
    {
        $departments = DB::table('departments')->orderBy('name')->get();
        // Kirim list auditor hardcoded ke view
        return view('test-form', [
            'departments' => $departments,
            'auditors' => $this->auditorsList
        ]);
    }

    // ==========================================
    // 2. CEK PENDING AUDIT (Resume Feature)
    // ==========================================
    public function checkPendingAudit(Request $request)
    {
        $nik = $request->input('nik');

        // Cari sesi audit milik NIK ini yang statusnya masih 'IN_PROGRESS'
        // Join antara tabel audits dan audit_sessions
        $pendingAudit = DB::table('audits')
            ->join('audit_sessions', 'audits.audit_session_id', '=', 'audit_sessions.id')
            ->join('departments', 'audits.department_id', '=', 'departments.id')
            ->where('audit_sessions.auditor_nik', $nik)
            ->where('audits.status', 'IN_PROGRESS') // Status belum selesai
            ->select(
                'audits.id as audit_id',
                'departments.name as dept_name',
                'audit_sessions.audit_date'
            )
            ->orderBy('audits.created_at', 'desc')
            ->first();

        if ($pendingAudit) {
            return response()->json([
                'found' => true,
                'audit_id' => $pendingAudit->audit_id,
                'dept_name' => $pendingAudit->dept_name,
                'date' => $pendingAudit->audit_date,
                // Default redirect ke 4.1, nanti di logic show() bisa dibuat lebih pintar kalau mau
                'resume_link' => route('audit.show', ['id' => $pendingAudit->audit_id, 'clause' => '4.1'])
            ]);
        }

        return response()->json(['found' => false]);
    }

    // ==========================================
    // 3. MULAI AUDIT BARU
    // ==========================================
    public function startAudit(Request $request) 
    {
        // Validasi
        $request->validate([
            'auditor_name' => 'required',
            'department_id' => 'required',
            'audit_date' => 'required'
        ]);

        return DB::transaction(function () use ($request) {
            $sessionId = (string) Str::uuid();
            
            // Simpan Session
            DB::table('audit_sessions')->insert([
                'id' => $sessionId,
                'auditor_name'       => $request->auditor_name,
                'auditor_nik'        => $request->auditor_nik,
                'auditor_department' => $request->auditor_department,
                'audit_date'         => $request->audit_date,
                'created_at'         => now(),
            ]);

            // Simpan Responders
            if ($request->has('responders')) {
                foreach ($request->responders as $resp) {
                    if (!empty($resp['name'])) { 
                        DB::table('audit_responders')->insert([
                            'id' => (string) Str::uuid(),
                            'audit_session_id' => $sessionId,
                            'responder_name' => $resp['name'],
                            'responder_department' => $resp['department'] ?? null,
                            'responder_nik' => $resp['nik'] ?? null,
                            'created_at' => now(),
                        ]);
                    }
                }
            }

            // Simpan Audit Utama
            $newAuditId = (string) Str::uuid();
            DB::table('audits')->insert([
                'id' => $newAuditId,
                'audit_session_id' => $sessionId,
                'department_id'    => $request->department_id,
                'status'           => 'IN_PROGRESS',
                'created_at'       => now(),
            ]);

            return redirect()->route('audit.show', ['id' => $newAuditId, 'clause' => '4.1']);
        });
    }

    // ==========================================
    // 4. SHOW QUESTIONNAIRE
    // ==========================================
    public function show($auditId, $clause)
    {
        $audit = DB::table('audits')->where('id', $auditId)->first();
        if(!$audit) abort(404, 'Audit Not Found');

        // Logic Navigasi Clause
        $clauseData = DB::table('clauses')->where('clause_code', $clause)->first();
        if(!$clauseData) abort(404, 'Clause Not Found');

        $itemsRaw = DB::table('items')
            ->where('clause_id', $clauseData->id)
            ->orderBy('item_order')
            ->get();

        $maturityLevels = DB::table('maturity_levels')
            ->whereIn('id', $itemsRaw->pluck('maturity_level_id'))
            ->orderBy('level_number')
            ->get();

        $items = $itemsRaw->groupBy('maturity_level_id');
        
        // Data Pendukung View
        $session = DB::table('audit_sessions')->where('id', $audit->audit_session_id)->first();
        $responders = DB::table('audit_responders')->where('audit_session_id', $session->id)->get();
        $departmentName = DB::table('departments')->where('id', $audit->department_id)->value('name');
        
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
            'auditDate'        => $session->audit_date,
            'targetDept'       => $departmentName,
            'responders'       => $responders,
            'maturityLevels'   => $maturityLevels,
            'items'            => $items,
            'existingQuestion' => $existingQuestion,
        ]);
    }

    // ==========================================
    // 5. STORE ANSWER (Simpan)
    // ==========================================
    public function store(Request $request, $auditId, $clause)
    {
        // ... (Logika sama persis dengan yang Anda berikan sebelumnya) ...
        // Agar response tidak terlalu panjang, saya gunakan logika store() 
        // yang sudah Anda miliki di prompt sebelumnya. Itu sudah benar.
        
        // Copy paste isi function store() Anda di sini
        // Pastikan di bagian akhir, jika tidak ada next clause:
        // DB::table('audits')->where('id', $auditId)->update(['status' => 'DONE']);
        // return redirect()->route('audit.finish');
        
        $answers = $request->input('answers', []);
        $questionText = $request->input('audit_question');

        DB::transaction(function () use ($answers, $auditId, $clause, $questionText, $request) {
            // Update Audit Questions Note
             if ($questionText !== null) {
                DB::table('audit_questions')->updateOrInsert(
                    ['audit_id' => $auditId, 'clause_code' => $clause],
                    [
                        'id' => (string) Str::uuid(), 
                        'question_text' => $questionText, 
                        'updated_at' => now(),
                        'created_at' => now() // Use timestamps properly
                    ]
                );
            }

            // Save Answers loop (sama seperti code Anda sebelumnya)
             foreach ($answers as $itemId => $data) {
                 // ... logika delete & insert answers ...
                 if (!empty($data['answer']) && !empty($data['auditor_name'])) {
                    DB::table('answers')->updateOrInsert(
                        ['audit_id' => $auditId, 'item_id' => $itemId, 'auditor_name' => $data['auditor_name']],
                        ['answer' => $data['answer'], 'answered_at' => now(), 'id' => (string) Str::uuid()]
                    );
                 }
             }
        });

        $currentIndex = array_search($clause, $this->clauseOrder);
        if (isset($this->clauseOrder[$currentIndex + 1])) {
            return redirect()->route('audit.show', ['id' => $auditId, 'clause' => $this->clauseOrder[$currentIndex + 1]]);
        }

        // Tandai selesai
        DB::table('audits')->where('id', $auditId)->update(['status' => 'DONE']);
        return redirect()->route('audit.finish');
    }
}
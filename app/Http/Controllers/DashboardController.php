<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Audit;
use App\Models\Item;
use App\Models\Clause;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Throwable;
use App\Models\AuditQuestion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use App\Models\User;


class DashboardController extends Controller
{
    // ... (Definisi $mainClauses dan $mainClauseTitles biarkan tetap ada di atas) ...
    private $mainClauses = [
        '4'  => ['4.1', '4.2', '4.3', '4.4'],
        '5'  => ['5.1', '5.2', '5.3'],
        '6'  => ['6.1.1', '6.1.2', '6.1.3', '6.1.4', '6.2.1', '6.2.2'],
        '7'  => ['7.1', '7.2', '7.3', '7.4','7.5.1', '7.5.2', '7.5.3'],
        '8'  => ['8.1', '8.2'],
        '9'  => ['9.1.1', '9.1.2', '9.2.1 & 9.2.2', '9.3'],
        '10' => ['10.1', '10.2', '10.3'],
    ];

private $mainClauseTitles = [
    '4'  => 'Konteks Organisasi',
    '5'  => 'Kepemimpinan',
    '6'  => 'Perencanaan',
    '7'  => 'Dukungan',
    '8'  => 'Operasional',
    '9'  => 'Evaluasi Kinerja',
    '10' => 'Peningkatan',
];


    /**
     * 1. DASHBOARD UTAMA (Tampilan Awal Admin)
     */
public function index(Request $request)
{
    $departments = Department::orderBy('name', 'asc')->get();
    
    $stats = [
        'total_audits' => Audit::count(),
        'completed' => Audit::whereIn('status', ['COMPLETE', 'COMPLETED'])->count(),
        'pending' => Audit::whereNotIn('status', ['COMPLETE', 'COMPLETED'])->count(),
        'departments'  => Department::count(),
    ];

    // Ambil recent audits dan tambahkan department_names
 $recentAudits = Audit::whereNotNull('audit_session_id')
    ->with('session', 'department')
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get()
    ->map(function ($audit) {
     // --- 1. Department Names (gunakan department_id sebagai SINGLE UUID)
$deptId = $audit->department_id ?? null;
$audit->department_names = [];

if ($deptId) {
    // Jika department_id adalah UUID tunggal
    $dept = Department::find($deptId);
    if ($dept) {
        $audit->department_names = [$dept->name];
    } else {
        // Jika tidak ditemukan, simpan UUID saja sebagai fallback
        $audit->department_names = [$deptId];
    }
} elseif ($audit->department_ids) {
    // Fallback: coba baca department_ids jika ada (untuk kompatibilitas)
    $deptNamesRaw = trim($audit->department_ids ?? '');
    if ($deptNamesRaw) {
        $decoded = json_decode($deptNamesRaw, true);
        if (is_array($decoded)) {
            $audit->department_names = collect($decoded)
                ->filter(fn($n) => is_string($n) && trim($n) !== '')
                ->map(fn($n) => trim($n))
                ->toArray();
        } else {
            $audit->department_names = [trim($deptNamesRaw)];
        }
    }
}

        // --- 2. Scope (Aman dari null/empty/string) ---
        $scopeRaw = trim($audit->scope ?? '');
        $audit->scope_clean = '';
        if ($scopeRaw) {
            $decoded = json_decode($scopeRaw, true);
            if (is_array($decoded)) {
                $audit->scope_clean = collect($decoded)
                    ->filter(fn($item) => is_string($item) && trim($item) !== '')
                    ->map(fn($item) => trim($item))
                    ->join(', ');
            } else {
                // Jika bukan array, coba parse sebagai string tunggal (fallback)
                $audit->scope_clean = $scopeRaw;
            }
        }

        // --- 3. Methodology ---
        $methodRaw = trim($audit->methodology ?? '');
        $audit->methodology_clean = '';
        if ($methodRaw) {
            $decoded = json_decode($methodRaw, true);
            if (is_array($decoded)) {
                $audit->methodology_clean = collect($decoded)
                    ->filter(fn($item) => is_string($item) && trim($item) !== '')
                    ->map(fn($item) => trim($item))
                    ->join(', ');
            } else {
                $audit->methodology_clean = $methodRaw;
            }
        }

        return $audit;
    });


    // Query live questions (sudah benar)
   $findings = DB::table('answers')
    ->join('items', 'answers.item_id', '=', 'items.id')
    ->join('clauses', 'items.clause_id', '=', 'clauses.id')
    ->join('departments', 'answers.department_id', '=', 'departments.id')
    ->whereNotNull('answers.finding_note')
    ->where('answers.finding_note', '!=', '')
    ->select(
        'answers.finding_level',
        'answers.finding_note',
        'answers.auditor_name',
        'answers.created_at',
        'clauses.clause_code',
        'departments.name as dept_name'
    )
    ->orderBy('answers.created_at', 'desc')
    ->limit(20)
    ->get();


  // 1. Tentukan Tahun yang dipilih (Default tahun sekarang)
    $selectedYear = $request->input('year', Carbon::now()->year);
    
    // 2. Siapkan array tahun untuk sidebar (3 tahun ke belakang + 1 tahun depan/sekarang)
    $availableYears = [
        $selectedYear,
        $selectedYear + 1,
        $selectedYear + 2,
        $selectedYear + 3
    ];

    // 3. Tentukan Start & End date tahun tersebut
    // Kita mulai dari hari Senin pertama di tahun itu atau sebelumnya agar gridnya rapi
    $startOfYear = Carbon::createFromDate($selectedYear, 1, 1);
    $endOfYear   = Carbon::createFromDate($selectedYear, 12, 31);
    
    // Adjust start date ke hari Senin terdekat (bisa mundur ke tahun sebelumnya dikit)
    $startDate = $startOfYear->copy()->startOfWeek(); 
    // Adjust end date ke akhir minggu
    $endDate   = $endOfYear->copy()->endOfWeek();

    // 4. Query Data
    $auditCounts = Audit::whereBetween('created_at', [$startOfYear, $endOfYear]) // Filter data stricly tahun ini
        ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
        ->groupBy('date')
        ->pluck('count', 'date')
        ->toArray();

    // 5. Generate Grid
    $contributionData = [];
    $currentDate = $startDate->copy();
    $lastMonth = null;

    // Loop mingguan sampai melewati akhir tahun
    while ($currentDate <= $endDate) {
        $weekData = [];
        // Cek bulan dari hari pertama minggu ini (atau hari ke-4 untuk akurasi label bulan)
        $monthCheckDate = $currentDate->copy()->addDays(6); // Cek akhir minggu untuk label bulan
        $weekMonth = $monthCheckDate->format('M');
        
        // Logika Label Bulan: Tampilkan jika bulan berubah dari minggu sebelumnya
        // DAN pastikan labelnya masuk di tahun yang dipilih (kosmetik)
        $showMonthLabel = ($weekMonth !== $lastMonth) && ($monthCheckDate->year == $selectedYear);
        $lastMonth = $weekMonth;

        for ($d = 0; $d < 7; $d++) {
            $dateString = $currentDate->format('Y-m-d');
            
            // Cek apakah tanggal ini masih dalam range tahun yang dipilih (untuk memutihkan tanggal luar range)
            $isInYear = $currentDate->year == $selectedYear;
            
            $count = $auditCounts[$dateString] ?? 0;
            
            // Level Warna (0 = Kosong, 1 = Sedikit, 2 = Sedang, 3 = Banyak)
            $level = 0;
            if ($count > 0) $level = 1;
            if ($count > 3) $level = 2;
            if ($count > 7) $level = 3;

            $weekData['days'][] = [
                'date' => $currentDate->format('d M Y'),
                'count' => $count,
                'level' => $level,
                'in_year' => $isInYear // Flag untuk visual
            ];
            
            $currentDate->addDay();
        }
        
        $weekData['month_label'] = $showMonthLabel ? $weekMonth : '';
        $contributionData[] = $weekData;
        
        // Safety break agar tidak infinite loop
        if($currentDate->year > $selectedYear && $currentDate->month > 1) break; 
    }

    return view('admin.dashboard', compact(
        'stats', 
        'recentAudits', 
        'findings', 
        'contributionData', 
        'selectedYear', 
        'availableYears'
        
    ));
}

    /**
     * 2. HALAMAN DETAIL DEPARTEMEN
     */
    public function showDepartment(Request $request, $deptId)
    {
        $departments = Department::orderBy('name')->get();
        $currentDept = Department::findOrFail($deptId);
        
        $query = Audit::where('department_id', $deptId)
                      ->with(['session', 'responders']);

        if ($request->has('year') && $request->year != '') {
            $query->whereYear('created_at', $request->year);
        }

        $audits = $query->orderBy('created_at', 'desc')->get();

        // Hitung statistik kecil untuk halaman ini
        $localStats = [
            'total' => $audits->count(),
            'completed' => $audits->where('status', 'COMPLETED')->count(),
            'pending' => $audits->where('status', 'PENDING')->count(),
        ];

        return view('admin.department_audits', compact('departments', 'currentDept', 'audits', 'localStats'));
    }
    public function showAuditOverview($auditId)
    {
        // Copy paste logic showAuditOverview Anda yang panjang di sini
        // Pastikan return view('admin.audit_clauses', ...)
        $departments = Department::all();
       $audit = Audit::with('department')->findOrFail($auditId);

       // --- TAMBAHKAN INI AGAR NAMA AUDITOR MUNCUL ---
    $session = DB::table('audit_sessions')
        ->where('id', $audit->audit_session_id)
        ->first();

$leadAuditor = [
    'name'  => $session->auditor_name ?? '-',
    'email' => $session->auditor_email ?? '-',
    'nik'   => $session->auditor_nik ?? '-',
];

$teamMembers = DB::table('audit_responders')
    ->where('audit_session_id', $audit->audit_session_id)
    ->where(function ($q) use ($leadAuditor) {
        $q->where('responder_nik', '!=', $leadAuditor['nik'])
          ->orWhereNull('responder_nik');
    })
    ->select(
        'responder_name as name',
        'responder_nik as nik',
        'responder_department as department'
    )
    ->get();
       
        // --- LOGIKA GRAFIK ---
        $allItems = Item::join('clauses', 'items.clause_id', '=', 'clauses.id')
            ->leftJoin('answer_finals', function($join) use ($auditId) {
                $join->on('items.id', '=', 'answer_finals.item_id')
                     ->where('answer_finals.audit_id', '=', $auditId);
            })
            ->select(
                'clauses.clause_code',
                'answer_finals.final_yes',
                'answer_finals.final_no',
                'answer_finals.yes_count',
                'answer_finals.no_count'
            )
            ->get();

        $detailedStats = []; 
        $mainStats = [];     

// 1. UBAH INISIALISASI ARRAY (Tambahkan key 'unanswered')
foreach($this->mainClauses as $main => $subs) {
    // Tambah 'unanswered' di sini
    $mainStats[$main] = ['yes' => 0, 'no' => 0, 'partial' => 0, 'na' => 0, 'unanswered' => 0]; 
    foreach($subs as $sub) {
        $detailedStats[$sub] = ['yes' => 0, 'no' => 0, 'partial' => 0, 'na' => 0, 'unanswered' => 0];
    }
}

// 2. UBAH LOGIKA LOOPING STATUS
// ... (Bagian atas method tetap sama)

foreach ($allItems as $item) {
    $status = 'unanswered'; // Default status awal

    // LOGIKA PEMISAHAN:
    
    // 1. Cek apakah record jawaban ADA di database (bukan null)
    if (is_null($item->final_yes)) {
        // Jika null, berarti baris ini dihasilkan dari LEFT JOIN karena belum ada datanya
        $status = 'unanswered'; // Warna ABU-ABU
    } 
    // 2. Jika data ADA, cek apakah hasil votingnya kosong (N/A)
    elseif ($item->yes_count == 0 && $item->no_count == 0) {
        // Sudah dikerjakan/disubmit tapi tidak ada pilihan Yes atau No (N/A)
        $status = 'na'; // Warna KUNING
    } 
    // 3. Logika Yes/No/Partial seperti biasa
    elseif ($item->final_yes > $item->final_no) {
        $status = 'yes';
    } elseif ($item->final_no > $item->final_yes) {
        $status = 'no';
    } else {
        $status = 'partial'; // Seri
    }

    // Masukkan ke array statistics
    if (isset($detailedStats[$item->clause_code])) {
        $detailedStats[$item->clause_code][$status]++;
    }

    foreach($this->mainClauses as $mainKey => $subArray) {
        if (in_array($item->clause_code, $subArray)) {
            $mainStats[$mainKey][$status]++;
            break;
        }
    }
}

$auditSummary = [
    'audit_code' => $audit->audit_code ?? '-',
    'status'     => strtoupper($audit->status),

    'type' => $audit->type,

    'objective' => $audit->objective,

    'scope' => $audit->scope
        ? implode(', ', json_decode($audit->scope, true))
        : '-',

    'standards' => $audit->standards
        ? implode(', ', json_decode($audit->standards, true))
        : '-',

    'methodology' => $audit->methodology
        ? implode(', ', json_decode($audit->methodology, true))
        : '-',

    'start_date' => $audit->audit_start_date
        ? \Carbon\Carbon::parse($audit->audit_start_date)->format('d F Y')
        : '-',

    'end_date' => $audit->audit_end_date
        ? \Carbon\Carbon::parse($audit->audit_end_date)->format('d F Y')
        : '-',
];




// ... (Sisa method return view tetap sama)


        return view('admin.audit_clauses', [
            'departments' => $departments,
            'audit' => $audit,
            'leadAuditor' => $leadAuditor,
            'teamMembers' => $teamMembers,
            'auditSummary' => $auditSummary,
            'mainClauses' => $this->mainClauses,
            'titles' => $this->mainClauseTitles,
            'detailedStats' => $detailedStats, 
            'mainStats' => $mainStats          
        ]);
    }

public function showClauseDetail($auditId, $mainClause)
{
    $departments = Department::all();
    $audit = Audit::findOrFail($auditId);
    
    if (!array_key_exists($mainClause, $this->mainClauses)) {
        abort(404);
    }

    $subCodes = $this->mainClauses[$mainClause];
    $clausesDb = Clause::whereIn('clause_code', $subCodes)->get();
    $clauseIds = $clausesDb->pluck('id');
    $subClauseTitles = $clausesDb->pluck('title', 'clause_code');

    // Ambil catatan auditor (opsional, bisa dihapus jika tidak dipakai)
    $auditorNotes = DB::table('audit_questions')
        ->where('audit_id', $auditId)
        ->whereIn('clause_code', $subCodes)
        ->pluck('question_text', 'clause_code');

    // Query utama: ambil item + finding_note dari tabel answers
    $items = Item::whereIn('clause_id', $clauseIds)
        ->join('clauses', 'items.clause_id', '=', 'clauses.id')
        ->join('maturity_levels', 'items.maturity_level_id', '=', 'maturity_levels.id')
        ->leftJoin('answers', function($join) use ($auditId) {
            $join->on('items.id', '=', 'answers.item_id')
                 ->where('answers.audit_id', '=', $auditId);
        })
        ->select(
            'items.*',
            'clauses.clause_code as current_code',
            'maturity_levels.level_number',
            'answers.finding_note'
        )
        ->orderBy('clauses.clause_code')
        ->orderBy('maturity_levels.level_number', 'asc')
        ->orderBy('items.item_order', 'asc')
        ->with(['answerFinals' => function($q) use ($auditId) {
            $q->where('audit_id', $auditId);
        }])
        ->get();

    $itemsGrouped = $items->groupBy('current_code');

    // ✅ INISIALISASI SEMUA VARIABEL STATISTIK
    $totalYes = 0;
    $totalNo = 0;
    $totalDraw = 0;
    $totalNA = 0;
    $totalUnanswered = 0; // ← INI YANG KURANG!

    // Statistik per sub-clause untuk stacked bar
    $stackedChartData = [];
    foreach($subCodes as $code) {
        $stackedChartData[$code] = [
            'yes' => 0,
            'no' => 0,
            'partial' => 0,
            'na' => 0,
            'unanswered' => 0 // ← tambahkan ini
        ];
    }

    // Hitung status tiap item
    $items->each(function($item) use (&$totalYes, &$totalNo, &$totalDraw, &$totalNA, &$totalUnanswered, &$stackedChartData) {
        $final = $item->answerFinals->first();

        if (!$final) {
            // BELUM DIJAWAB → tidak ada record di answer_finals
            $totalUnanswered++;
            $status = 'unanswered';
        } elseif ($final->yes_count == 0 && $final->no_count == 0) {
            // SUDAH DIJAWAB TAPI SEMUA PILIHAN N/A
            $totalNA++;
            $status = 'na';
        } elseif ($final->final_yes > $final->final_no) {
            $totalYes++;
            $status = 'yes';
        } elseif ($final->final_no > $final->final_yes) {
            $totalNo++;
            $status = 'no';
        } else {
            $totalDraw++;
            $status = 'partial';
        }

        if (isset($stackedChartData[$item->current_code])) {
            $stackedChartData[$item->current_code][$status]++;
        }
    });

    return view('admin.clause_detail', compact(
        'departments',
        'audit',
        'mainClause',
        'subCodes',
        'subClauseTitles',
        'itemsGrouped',
        'auditorNotes',
        'totalYes',
        'totalNo',
        'totalDraw',
        'totalNA',
        'totalUnanswered', // ← pastikan dikirim ke view
        'stackedChartData'
    ));
}

    // Tambahkan method ini di dalam class DashboardController

public function departmentStatusIndex()
{
    // 1. Ambil list departemen untuk sidebar (agar tidak error di layout)
    $departments = Department::orderBy('name', 'asc')->get();

    // 2. Ambil data summary status per departemen (Logic yang sama dengan dashboard)
   $deptSummary = Department::withCount(['audits as total_audit', 
        'audits as completed_count' => function ($query) {
            // Gunakan whereIn agar sinkron
            $query->whereIn('status', ['COMPLETE', 'COMPLETED', 'complete', 'completed']);
        },
        'audits as pending_count' => function ($query) {
            // Gunakan whereNotIn untuk menghitung sisanya
            $query->whereNotIn('status', ['COMPLETE', 'COMPLETED', 'complete', 'completed']);
        }
    ])->orderBy('name', 'asc')->get();

    return view('admin.department_status_index', compact('departments', 'deptSummary'));
}

public function exportToPdf($auditId)
{
$audit = Audit::with([
    'department',
    'session' // <-- sesuaikan dengan nama method di model
])->findOrFail($auditId);

    // Ambil session lengkap dengan lead auditor
    $session = DB::table('audit_sessions')
        ->where('id', $audit->audit_session_id)
        ->first();

    if (!$session) {
        abort(404, 'Audit session not found');
    }

    // Data Auditor & Tim (dari form charter)
    $leadAuditor = [
        'name' => $session->auditor_name ?? 'Yuli Kurniawati',
        'position' => $session->auditor_position ?? 'Governance & Compliance Dept. Head',
        'phone' => $session->auditor_phone ?? '031-8975825 ext. 361',
        'address' => 'Keboharan Km. 26, Krian, Sidoarjo, East Java',
        'email' => $session->auditor_email ?? 'yuli.kurniawati@trias-sentosa.com',
        'department' => $session->auditor_department ?? 'Governance & Compliance',
    ];

    // Ambil anggota tim dari audit_responders
    $teamMembers = DB::table('audit_responders')
        ->where('audit_session_id', $audit->audit_session_id)
        ->select('responder_name as name', 'responder_nik as nik', 'responder_department as department', 'responder_role as role')
        ->get();

    // Query items dengan finding_level dan finding_note dari answers
    $allItems = Item::join('clauses', 'items.clause_id', '=', 'clauses.id')
        ->join('maturity_levels', 'items.maturity_level_id', '=', 'maturity_levels.id')
        ->leftJoin('answers', function($join) use ($auditId) {
            $join->on('items.id', '=', 'answers.item_id')
                 ->where('answers.audit_id', '=', $auditId);
        })
        ->select(
            'clauses.clause_code',
            'clauses.clause_text',
            'items.item_text',
            'maturity_levels.level_number',
            'maturity_levels.description as maturity_desc',
            'answers.answer as current_answer',
            'answers.finding_level',
            'answers.finding_note',
            'answers.action_plan',
            'answers.completion_date'
        )
        ->orderBy('maturity_levels.level_number', 'asc')
        ->orderBy('clauses.clause_code', 'asc')
        ->get();

    $detailedItems = [];
    $findings = []; // Kumpulkan temuan terpisah
    
    foreach ($allItems as $item) {
        // Status mapping
        $statusRaw = strtolower($item->current_answer ?? 'unanswered');
        $status = match ($statusRaw) {
            'yes' => 'yes',
            'no' => 'no',
            'n/a', 'na' => 'na',
            default => 'unanswered',
        };

        $itemData = [
            'sub_clause' => $item->clause_code,
            'clause_text' => $item->clause_text,
            'item_text' => $item->item_text,
            'status' => $status,
            'maturity_level' => $item->level_number, 
            'maturity_description' => $item->maturity_desc,
            'finding_level' => $item->finding_level,
            'finding_note' => $item->finding_note,
            'action_plan' => $item->action_plan,
            'completion_date' => $item->completion_date,
        ];

        $detailedItems[] = $itemData;

        // Kumpulkan hanya item yang memiliki temuan
        if (!empty($item->finding_level) && in_array($item->finding_level, ['Minor NC', 'Major NC'])) {
            $findings[] = $itemData;
        }
    }

    // Format scope dari JSON/array
    $auditScope = is_array($audit->audit_scope) ? $audit->audit_scope : 
                 (is_string($audit->audit_scope) ? json_decode($audit->audit_scope, true) : []);
    
    $auditStandards = is_array($audit->audit_standards) ? $audit->audit_standards : 
                     (is_string($audit->audit_standards) ? json_decode($audit->audit_standards, true) : []);

    // Format methodology
    $methodology = is_array($audit->methodology) ? $audit->methodology : 
                  (is_string($audit->methodology) ? json_decode($audit->methodology, true) : []);

    // Hitung durasi audit dalam jam (asumsi 8 jam/hari)
    $startDate = \Carbon\Carbon::parse($audit->audit_start_date);
    $endDate = \Carbon\Carbon::parse($audit->audit_end_date);
    $durationDays = $startDate->diffInDays($endDate) + 1;
    $timeSpent = $durationDays * 8;

    // Logo base64
    $imagePath = public_path('images/ts.jpg');
    $logoBase64 = '';
    if (file_exists($imagePath)) {
        $type = pathinfo($imagePath, PATHINFO_EXTENSION);
        $data = file_get_contents($imagePath);
        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.exports.audit_overview_pdf', [
        'audit' => $audit,
        'leadAuditor' => $leadAuditor,
        'teamMembers' => $teamMembers,
        'detailedItems' => $detailedItems,
        'findings' => $findings,
        'auditScope' => $auditScope,
        'auditStandards' => $auditStandards,
        'methodology' => $methodology,
        'timeSpent' => $timeSpent,
        'logoBase64' => $logoBase64
    ]);

    $pdf->setPaper('A4', 'portrait');
    return $pdf->download("EMS_Audit_Report_{$audit->id}_".now()->format('Ymd').".pdf");
}
public function searchAudit(Request $request)
{
    try {
        // Validasi UUID (gagal di sini = tidak lempar ke DB)
        $request->validate([
            'audit_id' => ['required', 'uuid'],
        ]);

        // Bersihkan whitespace tersembunyi
        $searchId = preg_replace('/\s+/', '', $request->audit_id);

        // Cari audit
        $audit = Audit::with('session')->find($searchId);

        if (!$audit) {
            return back()->with('search_error', '❌ ID laporan tidak ditemukan.');
        }

        if (!$audit->session) {
            return back()->with('search_error', '⚠️ Data sesi audit belum lengkap.');
        }

        $auditorUser = \App\Models\User::where('name', $audit->session->auditor_name)
            ->where('role', 'auditor')
            ->first();

        if (!$auditorUser) {
            return back()->with('search_error', '⚠️ Auditor tidak ditemukan.');
        }

        return redirect()
            ->route('admin.auditors.show', $auditorUser->id)
            ->with('highlight_audit', $searchId);

   } catch (QueryException $e) {
    // Just use Log:: directly since it's imported above
    Log::error('Search audit DB error', [
        'input' => $request->audit_id,
        'error' => $e->getMessage()
    ]);

    return back()->with('search_error', 'Terjadi kesalahan sistem.');
} catch (Throwable $e) {
    Log::error('Search audit fatal error', [
        'input' => $request->audit_id,
        'error' => $e->getMessage()
    ]);

        return back()->with('search_error', 'ID yang anda cari tidak ada atau mungkin salah.');
    }
}

public function getDayDetails(Request $request)
{
    try {
        $date = $request->query('date');
        
        if (!$date) {
            return response()->json(['success' => false, 'message' => 'Tanggal tidak disertakan'], 400);
        }

        // Gunakan format standar agar tidak salah interpretasi bulan/hari
        $carbonDate = \Illuminate\Support\Carbon::parse($date);
        $start = $carbonDate->copy()->startOfDay();
        $end = $carbonDate->copy()->endOfDay();

        // Eager load session dan department
        $audits = \App\Models\Audit::with(['session', 'department'])
            ->whereBetween('created_at', [$start, $end])
            ->get()
            ->map(function ($audit) {
                return [
                    'id' => $audit->id,
                    // Cek jika session ada, jika tidak beri N/A
                    'auditor' => $audit->session ? $audit->session->auditor_name : 'Auditor Tidak Ada',
                    // Cek jika department ada, ambil kolom 'name'
                    'dept' => $audit->department ? $audit->department->name : 'Tanpa Departemen',
                ];
            });

        return response()->json([
            'success' => true,
            'audits' => $audits,
            'count' => $audits->count()
        ]);

    } catch (\Exception $e) {
        // Jika error 500 lagi, pesan aslinya akan muncul di panel untuk kita debug
        return response()->json([
            'success' => false, 
            'message' => 'Pesan Error: ' . $e->getMessage()
        ], 500);
    }
}

// Di method index() atau buat method baru di controller
public function findingsIndex()
{
    $findings = DB::table('answers')
        ->join('items', 'answers.item_id', '=', 'items.id')
        ->join('clauses', 'items.clause_id', '=', 'clauses.id')
        ->join('departments', 'answers.department_id', '=', 'departments.id')
        ->whereNotNull('answers.finding_note')
        ->where('answers.finding_note', '!=', '')
        ->select(
            'answers.finding_level',
            'answers.finding_note',
            'answers.auditor_name',
            'answers.created_at',
            'clauses.clause_code',
            'departments.name as dept_name'
        )
        ->orderBy('answers.created_at', 'desc')
        ->paginate(20); // ← gunakan paginate()

    return view('admin.audit-findings.index', compact('findings'));
}
// App\Http\Controllers\DashboardController.php

public function searchAuditByCode(Request $request)
{
    $request->validate([
        'audit_code' => 'required|string|max:255',
    ]);

    $auditCode = trim($request->audit_code);

    // Cari di tabel audits berdasarkan audit_code
    $audit = Audit::where('audit_code', $auditCode)->first();

    if (!$audit) {
        return back()->with('search_error', '❌ Kode audit tidak ditemukan.');
    }

    // Cari user auditor berdasarkan nama di session
    $auditorUser = User::where('name', $audit->session?->auditor_name)
        ->where('role', 'auditor')
        ->first();

    if (!$auditorUser) {
        return back()->with('search_error', '⚠️ Auditor tidak ditemukan.');
    }

    return redirect()
        ->route('admin.auditors.show', $auditorUser->id)
        ->with('highlight_audit', $audit->id);
}
}



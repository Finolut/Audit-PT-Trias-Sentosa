<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Clause;
use App\Models\MaturityLevel;
use Illuminate\Http\Request;
use App\Models\Department;

class ItemController extends Controller
{
public function index(Request $request)
{

$request->validate([
    'main_clause' => 'nullable|integer|min:4|max:10',
    'clause_id' => 'nullable|exists:clauses,id',
    'maturity_level_id' => 'nullable|exists:maturity_levels,id',
]);
    $departments = Department::orderBy('name', 'asc')->get();
    $clauses = Clause::orderBy('clause_code')->get();
    $levels = MaturityLevel::orderBy('level_number')->get();

    $query = Item::with(['clause', 'maturityLevel']);

    // Filter berdasarkan Klausul Utama (misal: 4, 5, ..., 10)
    if ($request->filled('main_clause')) {
        $mainClause = $request->main_clause;
        // Pastikan hanya angka 4-10
        if (in_array($mainClause, range(4, 10))) {
            $query->whereHas('clause', function ($q) use ($mainClause) {
                $q->where('clause_code', 'like', $mainClause . '.%');
            });
        }
    }

    // Filter berdasarkan Sub-Klausul spesifik
    if ($request->filled('clause_id')) {
        $query->where('clause_id', $request->clause_id);
    }

    // Filter berdasarkan Maturity Level
    if ($request->filled('maturity_level_id')) {
        $query->where('maturity_level_id', $request->maturity_level_id);
    }

    // Urutkan berdasarkan item_order
    $items = $query->orderBy('item_order')->get();

    // Jika ada main_clause, filter clauses untuk dropdown sub-klausul
    if ($request->filled('main_clause') && in_array($request->main_clause, range(4, 10))) {
        $filteredClauses = $clauses->filter(fn($c) => str_starts_with($c->clause_code, $request->main_clause . '.'));
    } else {
        $filteredClauses = $clauses;
    }

    return view('admin.items.index', compact(
        'items', 
        'departments', 
        'clauses', 
        'levels',
        'filteredClauses' // tambahkan ini
    ));
}

public function create() 
{
    $departments = Department::orderBy('name', 'asc')->get(); // Tambahkan ini agar sidebar tidak error
    $clauses = Clause::orderBy('clause_code')->get();
    $levels = MaturityLevel::orderBy('level_number')->get();
    
    return view('admin.items.create', compact('departments', 'clauses', 'levels'));
}

    public function store(Request $request) {
        $request->validate([
            'clause_id' => 'required',
            'maturity_level_id' => 'required',
            'item_text' => 'required',
            'item_order' => 'required|integer'
        ]);

        Item::create($request->all());
        return redirect()->route('admin.items.index')->with('success', 'Soal berhasil ditambahkan');
    }

    public function destroy($id) {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.items.index')->with('success', 'Soal berhasil dihapus.');
    }
    public function edit($id)
{
    $departments = Department::orderBy('name', 'asc')->get(); // Tambahkan ini
    $item = Item::findOrFail($id);
    $clauses = Clause::orderBy('clause_code')->get();
    $levels = MaturityLevel::orderBy('level_number')->get();

    return view('admin.items.edit', compact('departments', 'item', 'clauses', 'levels'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'clause_id' => 'required',
        'maturity_level_id' => 'required',
        'item_text' => 'required',
        'item_order' => 'required|integer',
    ]);

    $item = Item::findOrFail($id);
    $item->update($request->all());

    return redirect()->route('admin.items.index')->with('success', 'Soal berhasil diperbarui.');
}
}
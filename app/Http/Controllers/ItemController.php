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
    $departments = Department::orderBy('name', 'asc')->get();
    $clauses = Clause::orderBy('clause_code', 'asc', SORT_NATURAL)->get(); // ← urut natural
    $levels = MaturityLevel::orderBy('level_number')->get();

    $query = Item::with(['clause', 'maturityLevel']);

    // Filter Klausul Utama
    if ($request->filled('main_clause') && in_array($request->main_clause, range(4, 10))) {
        $mainClause = $request->main_clause;
        $query->whereHas('clause', fn($q) => $q->where('clause_code', 'like', "$mainClause.%"));
    }

    // Filter Sub-Klausul
    if ($request->filled('clause_id')) {
        $query->where('clause_id', $request->clause_id);
    }

    // Filter Maturity
    if ($request->filled('maturity_level_id')) {
        $query->where('maturity_level_id', $request->maturity_level_id);
    }

    // Ambil & urutkan
    $items = $query->get()->sortBy([
        ['clause.clause_code', 'asc', SORT_NATURAL],
        ['item_order', 'asc']
    ])->values();

    // Filter clauses untuk dropdown
    if ($request->filled('main_clause') && in_array($request->main_clause, range(4, 10))) {
        $filteredClauses = $clauses->filter(fn($c) => str_starts_with($c->clause_code, $request->main_clause . '.'));
    } else {
        $filteredClauses = $clauses;
    }

    return view('admin.items.index', compact('items', 'departments', 'clauses', 'levels', 'filteredClauses'));
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
<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Clause;
use App\Models\MaturityLevel;
use Illuminate\Http\Request;
use App\Models\Department;

class ItemController extends Controller
{
public function index()
{
    $departments = Department::orderBy('name', 'asc')->get(); // Tambahkan ini
    $items = Item::with(['clause', 'maturityLevel'])->orderBy('item_order')->get();
    
    return view('admin.items.index', compact('items', 'departments'));
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
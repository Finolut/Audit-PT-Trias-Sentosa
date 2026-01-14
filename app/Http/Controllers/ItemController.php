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

    public function create() {
        $clauses = Clause::all();
        $levels = MaturityLevel::all();
        return view('admin.items.create', compact('clauses', 'levels'));
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

    // Tambahkan Edit, Update, dan Delete sesuai standar Laravel
}
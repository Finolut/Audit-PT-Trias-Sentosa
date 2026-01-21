<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class LandingController extends Controller
{
public function landing()
{
    // Jika ingin menampilkan statistik publik atau daftar departemen
    $departments = Department::all();
    return view('welcome', compact('departments'));
}
}


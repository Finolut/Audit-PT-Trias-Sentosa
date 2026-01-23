<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Pastikan Model User Anda sesuai
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{

    public function index()
{
    $users = User::orderBy('name')->get();
    return view('admin.users.index', compact('users'));
}
    public function create()
    {
        // Ambil data departemen untuk dropdown
        $departments = Department::orderBy('name', 'asc')->get();
        return view('admin.users.create', compact('departments'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|unique:users,nik', // Pastikan NIK unik
            'department' => 'required|string',
            'role' => 'required|in:admin,auditor',
            'email' => 'required_if:role,admin|email|unique:users,email|nullable',
            'password' => 'nullable|required_if:role,admin|min:6',
        ]);

        // 2. Tentukan Password
        // Jika Auditor, kita beri password default atau null (tergantung setting database). 
        // Di sini saya buat default random agar tidak error di database jika kolom tidak boleh null.
        $passwordToSave = null;
        
        if ($request->role === 'admin') {
            $passwordToSave = Hash::make($request->password);
        } else {
            // Jika Auditor tidak perlu password, kita bisa isi default atau biarkan null 
            // (Asumsi kolom password_hash bisa nullable atau punya default)
            // Opsi Aman: Beri default string acak agar akun aman
            $passwordToSave = Hash::make('default123'); 
        }

        // 3. Simpan ke Database (Sesuai struktur gambar Anda)
        // Pastikan Model User Anda fillable-nya sudah diatur atau gunakan forceCreate
        $user = new User();
        // $user->id akan otomatis jika menggunakan UUID di model (HasUuids trait)
$user->name = $request->name;
$user->nik = $request->nik;
$user->department = $request->department; 
$user->role = $request->role;
$user->email = $request->email; 
$user->password = $passwordToSave; 
$user->save();

       return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

     public function edit(User $user)
    {
        $departments = Department::orderBy('name', 'asc')->get();
        return view('admin.users.edit', compact('user', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|unique:users,nik,' . $user->id, // exclude current user
            'department' => 'required|string',
            'role' => 'required|in:admin,auditor',
            'email' => 'required_if:role,admin|email|unique:users,email,' . $user->id . '|nullable',
            'password' => 'nullable|required_if:role,admin|min:6',
        ]);

        // Update data dasar
        $user->name = $request->name;
        $user->nik = $request->nik;
        $user->department = $request->department;
        $user->role = $request->role;
        $user->email = $request->role === 'admin' ? $request->email : null;

        // Hanya update password jika diisi
        if ($request->role === 'admin' && $request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        // Jika role berubah dari admin ke auditor → hapus email & reset password
        if ($request->role === 'auditor') {
            $user->email = null;
            // Opsional: reset password ke default
            // $user->password = Hash::make('default123');
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui!');
    }

public function destroy(User $user)
{
    if ($user->id === Auth::id()) {
        return redirect()->back()->withErrors(['Tidak bisa menghapus akun sendiri.']);
    }

    $user->delete();

    return redirect()->route('admin.users.index')
        ->with('success', 'User berhasil dihapus.');
}
}
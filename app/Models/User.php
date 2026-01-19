<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Tambahkan ini jika pake UUID

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    // Tambahkan ini jika Laravel bingung mencari nama tabel
    protected $table = 'users'; 

    // PENTING: Pastikan semua kolom di gambar masuk ke fillable
    protected $fillable = [
        'name',
        'nik',
        'department',
        'password_hash', // Sesuaikan dengan nama di gambar
        'role',
    ];

    // Jika di gambar kolom password namanya password_hash, 
    // Laravel butuh tahu ini untuk autentikasi (opsional)
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
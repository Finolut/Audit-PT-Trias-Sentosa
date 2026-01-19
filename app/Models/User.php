<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Tambahkan ini jika pake UUID

class User extends Authenticatable
{
    use HasUuids;

    // Tambahkan baris ini untuk mematikan updated_at otomatis
    const UPDATED_AT = null; 

    // Atau jika kamu ingin mematikan keduanya (tapi di gambar ada created_at)
    // public $timestamps = false; 

    protected $table = 'users';

    protected $fillable = [
        'name',
        'nik',
        'department',
        'role',
        'password_hash',
    ];


    // Jika di gambar kolom password namanya password_hash, 
    // Laravel butuh tahu ini untuk autentikasi (opsional)
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Hapus baris HasUuids di atas ini jika masih ada

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    
    // TAMBAHKAN DUA BARIS INI:
    // Ini memberitahu Laravel bahwa ID kamu adalah angka, bukan UUID
    public $incrementing = true;
    protected $keyType = 'int';

    const UPDATED_AT = null; 

    protected $fillable = [
        'name',
        'nik',
        'email',
        'department',
        'role',
        'password', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        // Pastikan nama kolom di database kamu memang 'password'
        // Jika di database namanya 'password_hash', ganti jadi $this->password_hash
        return $this->password;
    }
}
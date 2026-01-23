<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Matikan ini jika ID di database kamu angka biasa (1, 2, 3)
    // Jika ID kamu benar-benar UUID (string panjang), baru aktifkan lagi.
    // use HasUuids; 

    protected $table = 'users';
    
    // Matikan updated_at karena di tabel kamu sepertinya tidak ada
    const UPDATED_AT = null; 

    protected $fillable = [
        'name',
        'nik',
        'email',
        'department',
        'role',
        'password', // Sesuai kolom database kamu
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * PENTING: Karena nama kolom di database kamu 'password_hash',
     * bukan 'password', kita harus kasih tahu Laravel di sini.
     */
    public function getAuthPassword()
    {
        return $this->password;
    }
}
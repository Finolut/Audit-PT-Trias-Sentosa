<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Pastikan ini di-import!

class User extends Authenticatable
{
    use Notifiable, HasUuids; // Aktifkan HasUuids di sini

    protected $table = 'users';

    // PENTING UNTUK UUID:
    protected $keyType = 'string';    // Beritahu Laravel ID-nya adalah string
    public $incrementing = false;     // Beritahu Laravel ID tidak bertambah otomatis (+1)

    const UPDATED_AT = null; 

    protected $fillable = [
        'id', // Masukkan ID ke fillable karena kamu pakai UUID manual/khusus
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
        return $this->password;
    }
}
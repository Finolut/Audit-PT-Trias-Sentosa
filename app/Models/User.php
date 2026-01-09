<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $fillable = ['name','nik','password_hash','role'];
    protected $hidden = ['password_hash'];

    // ⬇️ KRUSIAL: map password ke kolom custom
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}


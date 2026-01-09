<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // <--- WAJIB ADA

class Department extends Model {
    protected $table = 'departments';
    protected $guarded = [];
    public $incrementing = false; 
    protected $keyType = 'string';

    public function audits() {
        return $this->hasMany(Audit::class, 'department_id');
    }
}
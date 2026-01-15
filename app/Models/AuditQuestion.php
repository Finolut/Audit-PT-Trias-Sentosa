<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditQuestion extends Model
{
    use HasFactory;

    protected $table = 'audit_questions'; // Pastikan nama tabel sesuai database

    protected $fillable = [
        'audit_id',
        'clause_code',
        'question_text',
        'department_id'
    ];

    // Relasi ke Audit agar bisa mengambil session (auditor_name)
    public function audit()
    {
        return $this->belongsTo(Audit::class, 'audit_id');
    }
}
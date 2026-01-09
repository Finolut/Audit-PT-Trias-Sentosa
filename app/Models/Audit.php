<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // <--- WAJIB ADA

class Audit extends Model {
    protected $table = 'audits';
    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';

    public function session() {
        return $this->belongsTo(AuditSession::class, 'audit_session_id');
    }

    public function answerFinals() {
        return $this->hasMany(AnswerFinal::class, 'audit_id');
    }

    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function responders() {
        return $this->hasMany(AuditResponder::class, 'audit_session_id', 'audit_session_id');
    }
}
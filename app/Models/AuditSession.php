<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditSession extends Model
{
    protected $table = 'audit_sessions';
    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';

    // Relasi ke tabel audits
    public function audits()
    {
        return $this->hasMany(Audit::class, 'audit_session_id');
    }

    // Relasi ke para responder di sesi ini
    public function responders()
    {
        return $this->hasMany(AuditResponder::class, 'audit_session_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditResponder extends Model
{
    protected $table = 'audit_responders';
    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';

    // Relasi balik ke sesi audit
    public function session()
    {
        return $this->belongsTo(AuditSession::class, 'audit_session_id');
    }
}
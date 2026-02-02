<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuditSession extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'auditor_name',
        'auditor_email',
        'auditor_nik',
        'auditor_department',
        'resume_token',
        'resume_token_expires_at',
        'is_parent'
    ];

    protected $casts = [
        'resume_token_expires_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->resume_token)) {
                $model->resume_token = Str::random(12);
            }
            // Set default expiry: 7 hari dari sekarang
            if (empty($model->resume_token_expires_at)) {
                $model->resume_token_expires_at = now()->addDays(7);
            }
        });
    }

    

public function remainingDays(): int
{
    if (!$this->resume_token_expires_at) return 0;
    return max(0, $this->resume_token_expires_at->diffInDays(now(), false));
}

public function isTokenValid(): bool
{
    return $this->resume_token_expires_at === null || 
           $this->resume_token_expires_at->isFuture();
}
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Laravel 10+
use Illuminate\Support\Str;

class AuditSession extends Model
{
    use HasUuids; // ← Tambahkan ini

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'auditor_name',
        'auditor_email',
        'auditor_nik',
        'auditor_department',
        'resume_token'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->resume_token)) {
                $model->resume_token = Str::random(12);
            }
        });
    }
}
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MaturityLevel extends Model {
    protected $table = 'maturity_levels';
    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';
}
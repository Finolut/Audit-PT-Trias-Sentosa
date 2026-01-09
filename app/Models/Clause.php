<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Clause extends Model {
    protected $table = 'clauses';
    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';

    public function items() {
        return $this->hasMany(Item::class, 'clause_id');
    }
}
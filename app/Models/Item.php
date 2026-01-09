<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Item extends Model {
    protected $table = 'items';
    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';

    public function clause() {
        return $this->belongsTo(Clause::class, 'clause_id');
    }

    public function maturityLevel() {
        return $this->belongsTo(MaturityLevel::class, 'maturity_level_id');
    }

    public function answerFinals() {
        return $this->hasMany(AnswerFinal::class, 'item_id');
    }
}
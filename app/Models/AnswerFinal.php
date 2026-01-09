<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // <--- WAJIB ADA

class AnswerFinal extends Model {
    protected $table = 'answer_finals';
    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';

    public function item() {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
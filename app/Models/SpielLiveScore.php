<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpielLiveScore extends Model
{
    protected $table = 'spiel_live_scores';
    protected $primaryKey = 'spielid';  // ← spielid ist Primary Key
    public $incrementing = false;       // ← Nicht auto-increment
    protected $keyType = 'int';
    protected $fillable = ['spielid', 'punkteA', 'punkteB'];
    public $timestamps = false;
    //Beziehung zur Tabelle Spiel
    public function spiel()
    {
        return $this->belongsTo(Spiel::class, 'spielid', 'spielid');
    }
}

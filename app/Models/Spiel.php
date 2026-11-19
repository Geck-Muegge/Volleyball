<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class spiel extends Model
{
    //
    use HasFactory;

    // Wenn der Tabellenname nicht der Pluralform des Modells entspricht
    protected $table = 'spiel';
    protected $primaryKey = 'spielid'; // Das Feld, das als Primärschlüssel verwendet wird

    public $incrementing = false; // Setze auf false, wenn der Primärschlüssel kein Auto-Increment ist
    protected $keyType = 'int'; // Setze den Typ des Primärschlüssels (z.B. 'int' oder 'string')
    public $timestamps = false; // Deaktiviert die Verwendung von created_at und updated_at

    // Füge hier die Spalten hinzu, die du massenweise zuweisen möchtest
    protected $fillable = [ 'spielid','teamA', 'teamB', 'schiedsrichter', 'punkteA', 'punkteB','runde','feld','status'];

    // Definiere die Beziehung zu Spielgruppe

    public function teamAFK()
    {
        return $this->belongsTo(Team::class, 'teamA', 'teamName');
    }

    public function teamBFK()
    {
        return $this->belongsTo(Team::class, 'teamB','teamName');
    }

    // prüft, ob Spiel ein Finalspiel ist, basierend auf der Runde

    public function isFinalspiel()
    {
        return in_array($this->runde, ['VF1', 'VF2', 'VF3', 'VF4', 'HF1', 'HF2', 'FI', 'PL3']);
    }

    // prüft, ob Spiel ein Gruppenspiel ist, basierend auf der Runde
    public function isGruppenspiel()
    {
        return in_array($this->runde, ['A', 'B', 'C', 'D']);
    }

}

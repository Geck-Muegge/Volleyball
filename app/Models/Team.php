<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    //
    use HasFactory;

    // Wenn der Tabellenname nicht der Pluralform des Modells entspricht
    protected $table = 'teams';
    public $timestamps = false; // Deaktiviert die Verwendung von created_at und updated_at

    // Füge hier die Spalten hinzu, die du massenweise zuweisen möchtest
    protected $fillable = ['teamName', 'klasse', 'isParticipating', 'gruppenid', 'id','nummer'];

    // Definiere die Beziehung zu Spielgruppe
    public function spielgruppeFK()
    {
        return $this->belongsTo(Spielgruppe::class, 'gruppenid', 'gruppenBez');
    }

    public function spieleAsTeamA()
    {
        return $this->hasMany(Spiel::class, 'teamA');
    }

    public function spieleAsTeamB()
    {
        return $this->hasMany(Spiel::class, 'teamB');
    }
}
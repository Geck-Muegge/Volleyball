<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class spielgruppe extends Model
{
    //
    use HasFactory;

    // Wenn der Tabellenname nicht der Pluralform des Modells entspricht
    protected $table = 'spielgruppe';
    protected $primaryKey = 'gruppenBez';

    public $incrementing = false; // Setze auf false, wenn der Primärschlüssel kein Auto-Increment ist
    protected $keyType = 'string'; // Setze den Typ des Primärschlüssels (z.B. 'int' oder 'string')
    public $timestamps = false; // Deaktiviert die Verwendung von created_at und updated_at

    // Füge hier die Spalten hinzu, die du massenweise zuweisen möchtest
    protected $fillable = ['gruppenBez'];

    // Beziehung zu Teams
    public function teams()
    {
        return $this->hasMany(Team::class, 'gruppenid', 'gruppenBez'); // Hier wird 'gruppenbez' als Referenz verwendet
    }

}



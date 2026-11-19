<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class platzierung extends Model
{
    protected $table = 'platzierung'; // Der Name der Tabelle
    public $timestamps = false; // Deaktiviert die Verwendung von created_at und updated_at
    protected $fillable = ['runde', 'team', 'platzierung']; // Die Felder, die massenhaft zuweisbar sind
    // Überschreibe die Methode, um den zusammengesetzten Schlüssel zu verwenden
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\spielgruppe;
class SpielgruppeController extends Controller
{
    //
    public function index(Request $request)
    {
        $spielgruppen = Spielgruppe::all(); // Alle Teams abrufen

        // Wenn eine Gruppe ausgewählt ist, filtere die Spiele
        $selectedGroupId = $request->input('gruppenbez');
        $gruppen = $selectedGroupId 
            ? Spielgruppe::where('gruppenbez', $selectedGroupId)->get() 
            : Spielgruppe::all();
        return view('spielgruppe.index', compact('spielgruppen', 'gruppen', $selectedGroupId)); // Daten an die Ansicht übergeben
    }

    public function select()
    {
        // Alle Gruppen abrufen
        $spielgruppen = Spielgruppe::all();
        return view('spielgruppe.select', compact('spielgruppen'));
    }
}

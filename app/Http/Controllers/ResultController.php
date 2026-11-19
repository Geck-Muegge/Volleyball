<?php

namespace App\Http\Controllers;

use App\Models\SpielLiveScore;
use App\Models\Spiel;
use Illuminate\Http\Request;


class ResultController extends Controller
{
    public function approve(Request $request)
    {
        $spiel = Spiel::where('spielid', $request->spielid)->first();
        if ($spiel) {
            $spiel->status = 'beendet';
            $spiel->save();

            // Prüfe, ob Gruppe vollständig ist
            if ($this->pruefeAlleGruppenBeendet()) {
                // Gruppe vollständig → Viertelfinale generieren
                $finalspielController = new FinalspielController();
                $finalspielController->generiereViertelfinale();
            }

            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }

    public function reject(Request $request)
    {
        $spiel = Spiel::where('spielid', $request->spielid)->first();
        if ($spiel) {
            $spiel->status = 'open';
            $spiel->punkteA = 0;
            $spiel->punkteB = 0;
            $spiel->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }

    private function pruefeAlleGruppenBeendet()
    {
        foreach (['A', 'B', 'C', 'D'] as $gruppe) {
            $alleSpiele = Spiel::where('runde', $gruppe)->count();
            $beendeteSpiele = Spiel::where('runde', $gruppe)->where('status', 'beendet')->count();
            // Wenn eine Gruppe nicht vollständig ist, abbrechen
            if ($alleSpiele === 0 || $alleSpiele !== $beendeteSpiele) {
                return;
            }
        }
        return true; // Alle Gruppen sind vollständig
    }

}


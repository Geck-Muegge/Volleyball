<?php

namespace App\Observers;

use App\Models\Spiel;
use App\Models\SpielLiveScore;

class SpielObserver
{
    public function created(Spiel $spiel)
    {
        // Automatisch einen Live-Score Eintrag erstellen
        SpielLiveScore::create([
            'spielid' => $spiel->spielid,
            'punkteA' => 0,
            'punkteB' => 0
        ]);
    }

    public function deleted(Spiel $spiel)
    {
        // Automatisch den Live-Score löschen
        SpielLiveScore::where('spielid', $spiel->spielid)->delete();
    }
}

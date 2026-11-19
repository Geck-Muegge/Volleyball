<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\spiel;
use App\Models\Platzierung;

class FinalspielController extends Controller
{

    public function index(Request $request)
        {
            $finalrunden = ['tr1','tr2','tr3','tr4','af1', 'af2','af3', 'af4','vf1', 'vf2','vf3', 'vf4', 'hf1', 'hf2', 'ff1', 'ff2'];
            $trostrundeGeneriert = session ('trostrunde_generiert', false);
            $achtelfinaleGeneriert = session('achtelfinale_generiert', false); // Standardwert false
            $viertelfinaleGeneriert = session('viertelfinale_generiert', false); // Standardwert false
            $halbfinaleGeneriert = session('halbfinale_generiert', false); // Standardwert false

            $selectedGroupId = $request->input('gruppenid'); // Die ausgewählte Finalrunde abrufen
            // Spiele basierend auf dem requestId filtern
            // Wenn eine Gruppe ausgewählt wurde, filtere die Spiele nach der Runde, per regulärem Ausdruck
            $spiele = Spiel::with(['teamAFK', 'teamBFK'])->whereIn('runde', $finalrunden)->get();

            if ($selectedGroupId) {
                $finalrundenSpiele = Spiel::where('runde', 'REGEXP', '^'. preg_quote($selectedGroupId))->get();
            } else {
            // Wenn keine Gruppe ausgewählt ist, zeige alle Spiele an
                $finalrundenSpiele = $spiele;
            }
            return view('finalspiel.index', compact('finalrundenSpiele', 'selectedGroupId','achtelfinaleGeneriert', 'viertelfinaleGeneriert', 'halbfinaleGeneriert', 'trostrundeGeneriert'));
        }

    public function edit($spielid)
        {
            $spiel = Spiel::findOrFail($spielid);
            return view('finalspiel.edit', compact('spiel'));
        }

    public function editfeld($spielid)
        {
            $spiel = Spiel::findOrFail($spielid);
            return view('finalspiel.editfeld', compact('spiel'));
        }

    public function update(Request $request, $spielid)
        {
            // Suche nach der spielid-Spalte, nicht nach der DB-ID
            $spiel = Spiel::where('spielid', $spielid)->firstOrFail();

            $validated = $request->validate([
                'punkteA' => 'required|integer|min:0|max:50',
                'punkteB' => 'required|integer|min:0|max:50',
            ]);

            $spiel->update($validated);
            $spiel->status = 'beendet';
            $spiel->save();

            // Prüfe, ob die Finalspiele generiert werden muss
            if ($this->rundeExistiert('FI')) {
                if( $this->istRundeBeendet(['FI', 'P3'])) {
                    return redirect()->route('finalspiel.index') ->with('success', 'Turnier beendet!');
                    }
                return redirect()->route('finalspiel.index');
            }
            if ($this->rundeExistiert('HF1')) {
                if( $this->istRundeBeendet(['HF1', 'HF2'])) {
                    $this->generiereFinals();
                    return redirect()->route('finalspiel.index') ->with('success', 'Finale und Spiel um Platz 3 generiert!');
                    }
                return redirect()->route('finalspiel.index');
            }
            // Prüfe, ob das Halbfinale generiert werden muss
            if ($this->rundeExistiert('VF1') && $this->istRundeBeendet(['VF1', 'VF2', 'VF3', 'VF4'])) {
                $this->generiereHalbfinale();
                return redirect()->route('finalspiel.index')->with('success', 'Halbfinale generiert!');
            }
            return redirect()->route('finalspiel.index');
        }

    public function updatefeld(Request $request, $id)
    {
        $spiel = Spiel::findOrFail($id);
        $spiel->feld = $request->input('feld');
        $spiel->save();
        return redirect()->route('finalspiel.index')->with([ 'spiel' => $spiel]);
    }

    public function finalrundenmenue()
    {
        $trostrundeGeneriert = session ('trostrunde_generiert', false);
        $achtelfinaleGeneriert = session('achtelfinale_generiert', false); // Standardwert false
        $viertelfinaleGeneriert = session('viertelfinale_generiert', false); // Standardwert false
        $halbfinaleGeneriert = session('halbfinale_generiert', false); // Standardwert false
        return view('finalspiel.index', compact('achtelfinaleGeneriert', 'viertelfinaleGeneriert', 'halbfinaleGeneriert', 'trostrundeGeneriert'));
    }

    // Funktion zur Generierung des Viertelfinales basierend auf den Platzierungen der Gruppen, Modus 24 Teams
   public function generiereViertelfinale()
    {
        $spiel = new SpielController();

        // Platzierungen abrufen mit deiner bestehenden Methode
        $tabelleA = $spiel->getTabelleForGruppe('A');
        $tabelleB = $spiel->getTabelleForGruppe('B');
        $tabelleC = $spiel->getTabelleForGruppe('C');
        $tabelleD = $spiel->getTabelleForGruppe('D');

        // 1. und 2. Platz extrahieren
        $platz1A = $tabelleA['A'][0]['team'] ?? null;
        $platz2A = $tabelleA['A'][1]['team'] ?? null;
        $schiriA = $tabelleA['A'][2]['team'] ?? null; // Schiedsrichter aus Gruppe A

        $platz1B = $tabelleB['B'][0]['team'] ?? null;
        $platz2B = $tabelleB['B'][1]['team'] ?? null;
        $schiriB = $tabelleB['B'][2]['team'] ?? null; // Schiedsrichter aus Gruppe B


        $platz1C = $tabelleC['C'][0]['team'] ?? null;
        $platz2C = $tabelleC['C'][1]['team'] ?? null;
        $schiriC = $tabelleC['C'][2]['team'] ?? null; // Schiedsrichter aus Gruppe C

        $platz1D = $tabelleD['D'][0]['team'] ?? null;
        $platz2D = $tabelleD['D'][1]['team'] ?? null;
        $schiriD = $tabelleD['D'][2]['team'] ?? null; // Schiedsrichter aus Gruppe D

        // Viertelfinale mit Kreuzstaffelpaarung
        $viertelfinale = [
            ['spielid' => '1001', 'teamA' => $platz1A, 'teamB' => $platz2B, 'runde' => 'VF1', 'schiedsrichter' => $schiriA, 'feld' => 1],
            ['spielid' => '1002', 'teamA' => $platz1C, 'teamB' => $platz2D, 'runde' => 'VF2', 'schiedsrichter' => $schiriC, 'feld' => 2],
            ['spielid' => '1003', 'teamA' => $platz1B, 'teamB' => $platz2C, 'runde' => 'VF3', 'schiedsrichter' => $schiriB, 'feld' => 3],
            ['spielid' => '1004', 'teamA' => $platz1D, 'teamB' => $platz2A, 'runde' => 'VF4', 'schiedsrichter' => $schiriD, 'feld' => 4],
        ];

        foreach ($viertelfinale as $spiel) {
            $this->speichereEndrundenspiel(
                $spiel['spielid'],
                $spiel['teamA'],
                $spiel['teamB'],
                $spiel['runde'],
                $spiel['schiedsrichter'],
                $spiel['feld']
            );
        }
        return redirect()->route('finalspiel.index')->with('success', 'Viertelfinale generiert!');
    }

    // Funktion zur Generierung des Halbfinales basierend auf den Siegern des Viertelfinales, Modus 24 Teams
    public function generiereHalbfinale()
    {
        $spiel = new SpielController();
        // Viertelfinale-Ergebnisse abrufen
        $vf1 = Spiel::where('runde', 'VF1')->first();
        $vf2 = Spiel::where('runde', 'VF2')->first();
        $vf3 = Spiel::where('runde', 'VF3')->first();
        $vf4 = Spiel::where('runde', 'VF4')->first();

        // Sieger bestimmen
        $sieger_vf1 = ($vf1->punkteA > $vf1->punkteB) ? $vf1->teamA : $vf1->teamB;
        $sieger_vf2 = ($vf2->punkteA > $vf2->punkteB) ? $vf2->teamA : $vf2->teamB;
        $sieger_vf3 = ($vf3->punkteA > $vf3->punkteB) ? $vf3->teamA : $vf3->teamB;
        $sieger_vf4 = ($vf4->punkteA > $vf4->punkteB) ? $vf4->teamA : $vf4->teamB;

        // Halbfinale erstellen
        $halbfinale = [
            ['spielid' => '1005', 'teamA' => $sieger_vf1, 'teamB' => $sieger_vf2, 'runde' => 'HF1', 'schiedsrichter' => 'offen', 'feld' => 1],
            ['spielid' => '1006', 'teamA' => $sieger_vf3, 'teamB' => $sieger_vf4, 'runde' => 'HF2', 'schiedsrichter' => 'offen', 'feld' => 2]
        ];

        foreach ($halbfinale as $spiel) {
            $this->speichereEndrundenspiel(
                $spiel['spielid'],
                $spiel['teamA'],
                $spiel['teamB'],
                $spiel['runde'],
                $spiel['schiedsrichter'],
                $spiel['feld']
            );
        }
        return redirect()->route('finalspiel.index')->with('success', 'Halbfinale generiert!');
    }

    // Funktion zur Generierung des Finales und des Spiels um Platz 3 basierend auf den Siegern und Verlierern des Halbfinales, Modus 24 Teams
    //Modus 24 Teams
    public function generiereFinals()
    {
        // Halbfinale-Ergebnisse abrufen
        $hf1 = Spiel::where('spielid', '1005')->first();
        $hf2 = Spiel::where('spielid', '1006')->first();

        // Sieger und Verlierer bestimmen
        $sieger_hf1 = ($hf1->punkteA > $hf1->punkteB) ? $hf1->teamA : $hf1->teamB;
        $verlierer_hf1 = ($hf1->punkteA > $hf1->punkteB) ? $hf1->teamB : $hf1->teamA;

        $sieger_hf2 = ($hf2->punkteA > $hf2->punkteB) ? $hf2->teamA : $hf2->teamB;
        $verlierer_hf2 = ($hf2->punkteA > $hf2->punkteB) ? $hf2->teamB : $hf2->teamA;

        // Halbfinale erstellen
        $finale = [
            ['spielid' => '1007', 'teamA' => $sieger_hf1, 'teamB' => $sieger_hf2, 'runde' => 'FI', 'schiedsrichter' => 'offen', 'feld' => 1],
            ['spielid' => '1008', 'teamA' => $verlierer_hf1, 'teamB' => $verlierer_hf2, 'runde' => 'P3', 'schiedsrichter' => 'offen', 'feld' => 2]
        ];

        foreach ($finale as $spiel) {
            $this->speichereEndrundenspiel(
                $spiel['spielid'],
                $spiel['teamA'],
                $spiel['teamB'],
                $spiel['runde'],
                $spiel['schiedsrichter'],
                $spiel['feld']
            );
        }
        return redirect()->route('finalspiel.index')->with('success', 'Finale und Platz 3 generiert!');
    }


//Hilfsfunktionen

// prüfe, ob eine Spiel in der genanntenRunde existiert
private function rundeExistiert($runde): bool
    {
        $result = Spiel::where('runde', $runde)->exists();
        return $result;
    }

/**
 * Prüfe, ob alle Spiele einer Runde beendet sind
 */
private function istRundeBeendet(array $runden): bool
{
    foreach ($runden as $runde) {
        $totalGames = Spiel::where('runde', $runde)->count();
        $completedGames = Spiel::where('runde', $runde)
            ->where('status', 'beendet')
            ->count();

        if ($totalGames === 0 || $totalGames !== $completedGames) {
            return false;
        }
    }
    return true;
}

/**
 * Ermittle Sieger und Verlierer eines Spiels
 */
private function ermittleSiegerUndVerlierer(string $runde): ?array
{
    $spiel = Spiel::where('runde', $runde)->first();

    if (!$spiel || $spiel->status !== 'beendet' || $spiel->punkteA === null || $spiel->punkteB === null) {
        return null;
    }

    // Unentschieden - keine Paarung möglich
    if ($spiel->punkteA == $spiel->punkteB) {
        return null;
    }

    if ($spiel->punkteA > $spiel->punkteB) {
        return ['sieger' => $spiel->teamA, 'verlierer' => $spiel->teamB];
    }

    return ['sieger' => $spiel->teamB, 'verlierer' => $spiel->teamA];
}

/**
 * Speichere oder aktualisiere ein Endrundenspiel
 */
private function speichereEndrundenspiel(string $spielid,string $teamA,string $teamB,string $runde,string $schiedsrichter,int $feld): void
    {
        Spiel::updateOrCreate(
            ['spielid' => $spielid],
            [
                'teamA' => $teamA,
                'teamB' => $teamB,
                'runde' => $runde,
                'feld' => $feld,
                'schiedsrichter' => $schiedsrichter,
                'punkteA' => 0,
                'punkteB' => 0,
                'status' => 'offen',
            ]
        );
    }
}

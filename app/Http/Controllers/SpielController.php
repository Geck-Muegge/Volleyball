<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\spiel;
use App\Models\Team;
use App\Models\Spielgruppe;
use App\Models\Platzierung;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Log;

class SpielController extends Controller
{
    /*
    //Auswahl der Spiele nach Gruppe
    public function zeigeSpielJeGruppe(Request $request, $gruppenid)
    {
        // Spiele basierend auf der ausgewählten Gruppe abrufen
        $zulaessigeRunden = ['af1', 'af2','af3', 'af4','vf1','vf2', 'vf3','vf4','hf1','hf2', 'ff1','ff2'];
        $gruppenspiele = Spiel::whereHas('teamAFK', function($query) use ($gruppenid) {
            $query->where('gruppenid', $gruppenid);})->whereNotIn('runde', $zulaessigeRunden)->with(['teamAFK', 'teamBFK'])->orderBy('spielid')->get();

        return view('spiele.gruppe', compact('gruppenspiele', 'gruppenid'));
        }

    public function zeigeAlleSpiele(Request $request)
    {
        // Alle Gruppenspiele
        $gruppenspiele = Spiel::where('runde', 'REGEXP', '^gr')->where('punkteA',0)->where('punkteB', 0)-> limit(10)->get();


        return view('spiele.alle', compact('gruppenspiele'));
        }
*/
        // Anzeige der Spiele in der Übersicht
    public function index(Request $request)
    {
        $finalRunden = ['af1', 'af2','af3','af4','vf1', 'vf2','vf3','vf4','hf1', 'hf2','ff1', 'ff2','tr1','tr2','tr3','tr4'];
        $spielgruppen = Spielgruppe::orderBy('gruppenbez')->get();
        $selectedGroupId = $request->input('gruppenid');

        // Alle Gruppenspiele (A, B, C, D)
        $spiele = Spiel::whereIn('runde', ['A', 'B', 'C', 'D'])
            ->with(['teamAFK', 'teamBFK'])
            ->orderBy('spielid')
            ->get();

        if ($selectedGroupId) {
            $spiele = $spiele->filter(function($spiel) use ($selectedGroupId) {
                return $spiel->teamAFK && $spiel->teamAFK->gruppenid === $selectedGroupId;
            });
        }

        return view('spiele.index', compact('spiele', 'spielgruppen', 'selectedGroupId'));
    }

    // Anzeige des Edit-Formulars für ein Spiel
    public function edit($spielid)
        {
            $spiel = Spiel::findOrFail($spielid);
            return view('spiele.edit', compact('spiel'));
        }

    // Update-Funktion für die Punkte eines Spiels und den Status
    public function update(Request $request, $spielid)
        {
            $spiel = Spiel::findOrFail($spielid);

            $validated = $request->validate([
                'punkteA' => 'required|integer|min:0|max:50',
                'punkteB' => 'required|integer|min:0|max:50',
            ]);

            $spiel->update($validated);
            $spiel->status = 'beendet';
            $spiel->save();
            // Prüfe, ob Gruppe vollständig ist
            if ($this->pruefeAlleGruppenBeendet()) {
                // Gruppe vollständig → Viertelfinale generieren
                $finalspielController = new FinalspielController();
                $finalspielController->generiereViertelfinale();
                return redirect()->route('finalspiel.index')->with('success', 'Viertelfinale generiert!');
            }

            return redirect()->route('spiele.index');
        }

        /*
    // Prüft, ob alle Spiele einer Gruppe beendet sind und generiert ggf. das Viertelfinale
    private function pruefeGruppeVollstaendig($gruppe)
    {
        $alleSpiele = Spiel::where('runde', $gruppe)->get();
        $beendeteSpiele = Spiel::where('runde', $gruppe)->where('status', 'beendet')->count();
        // Wenn alle Spiele der Gruppe beendet sind
        if ($alleSpiele->count() === $beendeteSpiele && $alleSpiele->count() > 0) {
            $this->pruefeAlleGruppenBeendet();
        }
    }*/

    // Prüft, ob alle Gruppen (A, B, C, D) beendet sind und generiert ggf. das Viertelfinale
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

/*
    // Aktualisiert die Punkte eines Spiels und bestimmt den Gewinner
    public function update1(Request $request, $id)
    {
        $request->validate([
            'punkteA' => 'required|integer',
            'punkteB' => 'required|integer',
        ]);

        $spiel = Spiel::findOrFail($id);
        $spiel->punkteA = $request->input('punkteA');
        $spiel->punkteB = $request->input('punkteB');
        $spiel->save();

        // Bestimme den Gewinner
        $winner = null;
        if ($spiel->punkteA > $spiel->punkteB) {
            $winner = 'teamA';
        } elseif ($spiel->punkteB > $spiel->punkteA) {
            $winner = 'teamB';
        }
        return redirect()->route('spiele.index')->with([ 'winner' => $winner, 'spiel' => $spiel]);
    }

    //speichert die Platzierungen einer Gruppe in der Tabelle Platzierungen
    public function tabellenSpeichern($spiele, $runde)
    {
        $i = 1;

        foreach ($spiele as $spiel) {
            // Zum Beispiel: Erstelle einen Eintrag in der Platzierungstabelle
            $teamA = $spiel['team'];
            $rundeA=$runde;

            Platzierung::updateOrCreate(
                ['team' => $teamA, 'runde' => $rundeA],['platzierung' => $i ] ); // Speichere die Teams mit Ihrer Gruppenplatzierung
            $i=$i+1;
        }
    }

    //ruft auf Buttonklick die Funktion zur Speicherung der Platzierungen auf
    public function speichern(Request $request)
    {
        $gruppe = $request->input('gruppe');

        if (!$gruppe) {
            return redirect()->back()->with('error', 'Keine Gruppe ausgewählt, Tabellen abschließen nicht möglich.');
        }
        else {
            $ergebnis=$this->zeigeTabelleJeGruppe($request, $gruppe);
            $daten =$ergebnis['sortedTeamsByGroup'][$gruppe];

            $this-> tabellenSpeichern($daten, $gruppe);
            return redirect()->back()->with('success', 'Platzierungen wurden erfolgreich gespeichert.');
        }
    } */

    //zeigt die Teams je Gruppe an, entweder für alle Gruppen oder für eine bestimmte Gruppe
    public function zeigeTeamsJeGruppe(Request $request, $gruppenid = 'all')
    {
        $sortedTeamsByGroup = [];

        if ($gruppenid === 'all') {
            // Alle Gruppen anzeigen
            foreach (['A', 'B', 'C', 'D'] as $gruppe) {
                $sortedTeamsByGroup = array_merge($sortedTeamsByGroup, $this->getTabelleForGruppe($gruppe));
            }
        } else {
            // Einzelne Gruppe anzeigen
            $sortedTeamsByGroup = $this->getTabelleForGruppe($gruppenid);
        }

        return view('spiele.tabellengruppe', compact('sortedTeamsByGroup', 'gruppenid'));
    }

    // Hilfsfunktion, um die Tabelle für eine bestimmte Gruppe zu generieren
    public function getTabelleForGruppe($gruppenid)
    {
        $spiele = Spiel::with(['teamAFK', 'teamBFK'])
            ->whereIn('runde', ['A', 'B', 'C', 'D'])
            ->orderBy('spielid')
            ->get();

        $spiele = $spiele->filter(function($spiel) use ($gruppenid) {
            return $spiel->teamAFK->gruppenid === $gruppenid || $spiel->teamBFK->gruppenid === $gruppenid;
        });

    $tabellen = [];
    $teamController = new TeamController();
    $teamsInGruppe = $teamController->getTeamsByGroup($gruppenid);

    foreach ($teamsInGruppe as $team) {
        $tabellen[$gruppenid][$team->teamName] = [
            'team' => $team->teamName,
            'punkte' => 0,
            'spielpunkte' => 0,
            'gegnerpunkte' => 0,
        ];
    }

    foreach ($spiele as $spiel) {
        $teamAName = $spiel->teamAFK->teamName;
        $teamBName = $spiel->teamBFK->teamName;
        $gruppeA = $spiel->teamAFK->gruppenid;
        $gruppeB = $spiel->teamBFK->gruppenid;

        if ($spiel->punkteA > $spiel->punkteB) {
            if (isset($tabellen[$gruppeA][$teamAName])) {
                $tabellen[$gruppeA][$teamAName]['punkte'] += 1;
            }
        } elseif ($spiel->punkteB > $spiel->punkteA) {
            if (isset($tabellen[$gruppeB][$teamBName])) {
                $tabellen[$gruppeB][$teamBName]['punkte'] += 1;
            }
        }

        if (isset($tabellen[$gruppeA][$teamAName])) {
            $tabellen[$gruppeA][$teamAName]['spielpunkte'] += $spiel->punkteA;
            $tabellen[$gruppeA][$teamAName]['gegnerpunkte'] += $spiel->punkteB;
        }

        if (isset($tabellen[$gruppeB][$teamBName])) {
            $tabellen[$gruppeB][$teamBName]['spielpunkte'] += $spiel->punkteB;
            $tabellen[$gruppeB][$teamBName]['gegnerpunkte'] += $spiel->punkteA;
        }
    }

    // Sortieren der Tabelle nach Punkten und direktem Vergleich
    $sortedTeamsByGroup = [];

    if (isset($tabellen[$gruppenid])) {
        $sortedTeams = collect($tabellen[$gruppenid])->sort(function ($a, $b) use ($spiele) {
            // Zuerst nach Punkten sortieren (absteigend)
            if ($a['punkte'] !== $b['punkte']) {
                return $b['punkte'] <=> $a['punkte'];
            }

            // Bei Gleichstand: direkter Vergleich (nur wenn Spiele existieren)
            if ($spiele->count() > 0) {
                $direkteSpiele = $spiele->filter(function($spiel) use ($a, $b) {
                    return ($spiel->teamAFK->teamName === $a['team'] && $spiel->teamBFK->teamName === $b['team']) ||
                           ($spiel->teamAFK->teamName === $b['team'] && $spiel->teamBFK->teamName === $a['team']);
                });

                if ($direkteSpiele->count() > 0) {
                    $spiel = $direkteSpiele->first();

                    if ($spiel->teamAFK->teamName === $a['team']) {
                        if ($spiel->punkteA > $spiel->punkteB) {
                            return -1;
                        } elseif ($spiel->punkteA < $spiel->punkteB) {
                            return 1;
                        }
                    } else {
                        if ($spiel->punkteB > $spiel->punkteA) {
                            return -1;
                        } elseif ($spiel->punkteB < $spiel->punkteA) {
                            return 1;
                        }
                    }
                }
            }

            // Fallback: nach Spielpunktdifferenz
            $diffA = $a['spielpunkte'] - $a['gegnerpunkte'];
            $diffB = $b['spielpunkte'] - $b['gegnerpunkte'];
            return $diffB <=> $diffA;
        });

        $sortedTeamsByGroup[$gruppenid] = $sortedTeams->values()->all();
    }

        return $sortedTeamsByGroup;
    }

    /*
// Funktion zur Generierung der Gruppenspiele
public function generiereGruppenspiele1(Request $request) {

    // Gruppierung der Teams nach Gruppen
    $gruppen = Spielgruppe::all();
    $id=1;
    $feld=1;
    foreach ($gruppen as $gruppe) {
    // Aufbau Spielpaarungen nach striktem Muster
        $gruppenspiele = [];
        $gruppenspiele[] = FinalspielController::erstelleSpiel($id,TeamController::getTeam($request, $gruppe->gruppenBez,1)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,2)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,5)->teamName, 'gr'.$gruppe->gruppenBez,$feld);
        $gruppenspiele[] = FinalspielController::erstelleSpiel($id+4,TeamController::getTeam($request, $gruppe->gruppenBez,3)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,4)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,2)->teamName, 'gr'.$gruppe->gruppenBez,$feld);
        $gruppenspiele[] = FinalspielController::erstelleSpiel($id+8,TeamController::getTeam($request, $gruppe->gruppenBez,1)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,5)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,4)->teamName, 'gr'.$gruppe->gruppenBez,$feld);
        $gruppenspiele[] = FinalspielController::erstelleSpiel($id+12,TeamController::getTeam($request, $gruppe->gruppenBez,2)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,3)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,1)->teamName, 'gr'.$gruppe->gruppenBez,$feld);
        $gruppenspiele[] = FinalspielController::erstelleSpiel($id+16,TeamController::getTeam($request, $gruppe->gruppenBez,4)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,5)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,3)->teamName, 'gr'.$gruppe->gruppenBez,$feld);
        $gruppenspiele[] = FinalspielController::erstelleSpiel($id+20,TeamController::getTeam($request, $gruppe->gruppenBez,1)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,3)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,5)->teamName, 'gr'.$gruppe->gruppenBez,$feld);
        $gruppenspiele[] = FinalspielController::erstelleSpiel($id+24,TeamController::getTeam($request, $gruppe->gruppenBez,2)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,4)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,1)->teamName, 'gr'.$gruppe->gruppenBez,$feld);
        $gruppenspiele[] = FinalspielController::erstelleSpiel($id+28,TeamController::getTeam($request, $gruppe->gruppenBez,3)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,5)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,2)->teamName, 'gr'.$gruppe->gruppenBez,$feld);
        $gruppenspiele[] = FinalspielController::erstelleSpiel($id+32,TeamController::getTeam($request, $gruppe->gruppenBez,1)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,4)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,3)->teamName, 'gr'.$gruppe->gruppenBez,$feld);
        $gruppenspiele[] = FinalspielController::erstelleSpiel($id+36,TeamController::getTeam($request, $gruppe->gruppenBez,2)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,5)->teamName,TeamController::getTeam($request, $gruppe->gruppenBez,4)->teamName, 'gr'.$gruppe->gruppenBez,$feld);
        $id++;
        $feld++;
        // Spiele in die Datenbank einfügen
        foreach ($gruppenspiele as $spiel) {
            Spiel::create($spiel);
        }
    }
    return redirect()->back();
} */

public function generiereGruppenspiele(Request $request)
{
    // CSV-Datei einlesen
    $csvPath = storage_path('app/private/roundrobin3.csv');
    if (!file_exists($csvPath)) {
        return redirect()->back()->with('error', 'Datei Spielplankonfiguration nicht gefunden!');
    }
    $spielplan = [];
    $handle = fopen($csvPath, 'r');
    $header = fgetcsv($handle, 1000, ';'); // Kopfzeile überspringen

    while (($row = fgetcsv($handle, 1000, ';')) !== false) {
        if (count($row) >= 3) {
            $spielplan[] = [
                'team1_pos' => (int)trim($row[0]),
                'team2_pos' => (int)trim($row[1]),
                'sr_pos' => (int)trim($row[2]),
            ];
        }
    }
    fclose($handle);

    if (empty($spielplan)) {
        return redirect()->back()->with('error', 'Spielplan ist leer!');
    }

    // Auf jede Gruppe anwenden
    $gruppen = Spielgruppe::all();
    $feld = 1;

    // TeamController instanziieren
    $teamController = new TeamController();

    foreach ($gruppen as $gruppe) {
        $gruppenBez = $gruppe->gruppenBez; // A, B, C, D
        // Hole alle Teams der Gruppe sortiert nach Position
        $teamsByPosition =  $teamController->getTeamsByPositionMap($gruppenBez);
        $spielid = $feld; // Start-ID für diese Gruppe

        // Wende Spielplan auf diese Gruppe an
        foreach ($spielplan as $spiel) {
            $team1_pos = $spiel['team1_pos'];
            $team2_pos = $spiel['team2_pos'];
            $sr_pos = $spiel['sr_pos'];
            // Hole Teamnamen aus Position
            $teamA = $teamsByPosition[$team1_pos] ?? null;
            $teamB = $teamsByPosition[$team2_pos] ?? null;
            $schiedsrichter = $teamsByPosition[$sr_pos] ?? null;

            if (!$teamA || !$teamB || !$schiedsrichter) {
                continue; // Überspringe, wenn Teams nicht gefunden
            }


            Spiel::create([
                'spielid' => $spielid,
                'teamA' => $teamA,
                'teamB' => $teamB,
                'schiedsrichter' => $schiedsrichter,
                'runde' => $gruppenBez,
                'feld' => $feld,
                'punkteA' => 0,
                'punkteB' => 0,
                'status' => 'offen',
            ]);

            $spielid+=4;
        }

        $feld++;
    }

    return redirect()->back()->with('success', 'Gruppenspiele generiert!');
}


//filtert das Spiel, in dem teamA gegen teamB spielt
public function getSpiel($teamA, $teamB) {
    $gruppenspiele = ['A', 'B', 'C', 'D'];

    $spiel = Spiel::whereIn('runde', $gruppenspiele)->where(function ($query) use ($teamA) {
                $query->where('teamA', $teamA)
                    ->orWhere('teamB', $teamA);
            })
            ->where(function ($query) use ($teamB) {
                $query->where('teamA', $teamB)
                    ->orWhere('teamB', $teamB);
            })->orderBy('spielid')
            ->get();
    return $spiel;
}


}


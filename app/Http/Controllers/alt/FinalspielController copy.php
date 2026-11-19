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

    public function edit($id)
    {
        $spiel = Spiel::with(['teamAFK', 'teamBFK'])->findOrFail($id);
        return view('finalspiel.edit', compact('spiel'));
    }

    public function editfeld( $id)
    {
        $spiel = Spiel::with(['teamAFK', 'teamBFK'])->findOrFail($id);

        return view('finalspiel.editfeld', compact('spiel'));
    }

    public function update(Request $request, $id)
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
        return redirect()->route('finalspiel.index')->with(['success' => 'Spielpunkte erfolgreich aktualisiert.', 'winner' => $winner, 'spiel' => $spiel]);   
    }

    public function updatefeld(Request $request, $id)
    {
        $spiel = Spiel::findOrFail($id);
        $spiel->feld = $request->input('feld');
        $spiel->save();
        return redirect()->route('finalspiel.index')->with(['success' => 'Spielpunkte erfolgreich aktualisiert.', 'spiel' => $spiel]);   
    }

    public function finalrundenmenue()
    {
        $trostrundeGeneriert = session ('trostrunde_generiert', false);
        $achtelfinaleGeneriert = session('achtelfinale_generiert', false); // Standardwert false
        $viertelfinaleGeneriert = session('viertelfinale_generiert', false); // Standardwert false
        $halbfinaleGeneriert = session('halbfinale_generiert', false); // Standardwert false
        return view('finalspiel.index', compact('achtelfinaleGeneriert', 'viertelfinaleGeneriert', 'halbfinaleGeneriert', 'trostrundeGeneriert'));
    }

    //Auswahl der Spiele nach Gruppe
    public function zeigeSpielJeFinalrunde(Request $request, $runde)
    {
        // Spiele basierend auf der ausgewählten Finalrunde abrufen   
        $finalrundenSpiele = Spiel::where('runde', 'REGEXP', '^'.$runde[0])->get();       
        //Unterschiedliche Titelzeilen je nach Runde generieren
       if ($runde[0]=='t') {
        $titel='Trostrunde';
       }
       switch ($runde[0]) {
            case 't':
                $titel='Trostrunde';
            break;
            case 'a':
                $titel='Achtelfinale';
            break;
            case 'v':
                $titel='Viertelfinale';
            break;
            case 'h':
                $titel='Halbfinale';
            break;
            case 'f':
                $titel='Finale und Spiel um Platz 3';
            break;

            default:
                $titel = 'Finalrunde';
            break;
            }
        return view('finalspiel.spieleJeRunde', compact('finalrundenSpiele', 'runde', 'titel'));
        }

    public static function erstelleSpiel($id,$teamA, $teamB,$schiri,$runde,$feld=0) {
        $spiel =[
            'spielid' => $id,
            'teamA' => $teamA,
            'teamB' => $teamB,
            'schiedsrichter' => $schiri, 
            'punkteA'=>0,
            'punkteB'=>0,
            'runde' => $runde ,
            'feld' => $feld     
        ];
        return $spiel;
    }

    public function generiereTrostrunde()
    {
        // Alle Platzierungen abfragen, sortiert nach Platzierung
        $platzierungen = Platzierung::orderBy('platzierung')->get();

        // Gruppierung der Teams nach Gruppen
        $gruppen = $platzierungen->groupBy('runde');

        // Aufbau Spielpaarungen nach striktem Muster
        $trostrunde= [];
        
        //2. A - 3. B
        $trostrunde[] = self::erstelleSpiel(45,$gruppen['A'][3]->team,$gruppen['B'][4]->team,$gruppen['A'][0]->team, 'tr1');
        $trostrunde[] = self::erstelleSpiel(46,$gruppen['B'][3]->team,$gruppen['A'][4]->team,$gruppen['B'][0]->team, 'tr2');
        $trostrunde[] = self::erstelleSpiel(47,$gruppen['C'][3]->team,$gruppen['D'][4]->team,$gruppen['C'][0]->team, 'tr3');
        $trostrunde[] = self::erstelleSpiel(48,$gruppen['D'][3]->team,$gruppen['C'][4]->team,$gruppen['D'][0]->team, 'tr4');       
        // Spiele in die Datenbank einfügen
        foreach ($trostrunde as $spiel) {
            Spiel::create($spiel);
        }
        // Setze die Session-Variable  
        session(['trostrunde_generiert' => true]);    
        return redirect()->back()->with('success', 'Spiele wurden erfolgreich generiert.');
    }

    public function generiereAchtelfinale()
    {
        // Alle Platzierungen abfragen, sortiert nach Platzierung
        $platzierungen = Platzierung::orderBy('platzierung')->get();
        // Gruppierung der Teams nach Gruppen
        $gruppen = $platzierungen->groupBy('runde');

        // Aufbau Spielpaarungen nach striktem Muster
        $achtelfinale = [];
        
        //2. A - 3. B
        $achtelfinale[] = self::erstelleSpiel(41,$gruppen['A'][1]->team,$gruppen['B'][2]->team,$gruppen['A'][3]->team, 'af1');
        $achtelfinale[] = self::erstelleSpiel(42,$gruppen['B'][1]->team,$gruppen['A'][2]->team,$gruppen['B'][3]->team, 'af2');
        $achtelfinale[] = self::erstelleSpiel(43,$gruppen['C'][1]->team,$gruppen['D'][2]->team,$gruppen['C'][3]->team, 'af3');
        $achtelfinale[] = self::erstelleSpiel(44,$gruppen['D'][1]->team,$gruppen['C'][2]->team,$gruppen['D'][3]->team, 'af4');
        
        // Spiele in die Datenbank einfügen
        foreach ($achtelfinale as $finale) {
            Spiel::create($finale);
        }

        // Setze die Session-Variable
        session(['achtelfinale_generiert' => true]);
        return redirect()->back()->with('success', 'Spiele wurden erfolgreich generiert.');
    }


    public function generiereViertelfinale()
    {
        // Setze die Session-Variable zurück, wenn sie einmal angezeigt wurde
        $achtelfinaleGeneriert = session('achtelfinale_generiert', false); // Standardwert false
        if ($achtelfinaleGeneriert) {        
            // Alle Platzierungen abfragen, sortiert nach Platzierung
            $platzierungen = Platzierung::orderBy('platzierung')->get();
            // Gruppierung der Teams nach Gruppen
            $gruppen = $platzierungen->groupBy('runde');
            $spiele = Spiel::where('runde', 'REGEXP', '^af')->get();
            
            $winner=[];
            foreach ($spiele as $spiel) {
                if ($spiel['punkteA']>$spiel['punkteB']) {$winner[$spiel['runde']]=$spiel['teamA'];
                }
                else {$winner[$spiel['runde']]=$spiel['teamB'];}                  
            }
            // Alle Platzierungen abfragen, sortiert nach Platzierung       
            $viertelfinale = [];               
            //Achtelfinalspiel gemäß Plan
            $viertelfinale[] = self::erstelleSpiel(49, $gruppen['A'][0]->team,$winner['af4'],$gruppen['A'][3]->team, 'vf1');
            $viertelfinale[] = self::erstelleSpiel(50, $gruppen['B'][0]->team,$winner['af3'],$gruppen['B'][3]->team, 'vf2');
            $viertelfinale[] = self::erstelleSpiel(51, $gruppen['C'][0]->team,$winner['af2'],$gruppen['C'][3]->team, 'vf3');
            $viertelfinale[] = self::erstelleSpiel(52, $gruppen['D'][0]->team,$winner['af1'],$gruppen['D'][3]->team, 'vf4');
            
            // Spiele in die Datenbank einfügen
            foreach ($viertelfinale as $finale) {
                Spiel::create($finale);
            }
            session(['viertelfinale_generiert' => true]);
            
        }
        return redirect()->back()->with('success', 'Spiele wurden erfolgreich generiert.');      
    }

    public function generiereHalbfinale()
    {
        // Setze die Session-Variable zurück, wenn sie einmal angezeigt wurde
        $viertelfinaleGeneriert = session('viertelfinale_generiert', false); // Standardwert false
        if ($viertelfinaleGeneriert) {
            $spiele = Spiel::where('runde', 'REGEXP', '^vf')->get();       
            $winner=[];
            foreach ($spiele as $spiel) {
                if ($spiel['punkteA']>$spiel['punkteB']) 
                    {$winner[$spiel['runde']]=$spiel['teamA']; }
                else 
                    {$winner[$spiel['runde']]=$spiel['teamB'];}       
            }
            // Alle Platzierungen abfragen, sortiert nach Platzierung       
            $halbfinale = [];
            
            //Halbfinalspiele gemäß Plan
            $halbfinale[] = self::erstelleSpiel(53,$winner['vf1'],$winner['vf2'],'', 'hf1');
            $halbfinale[] = self::erstelleSpiel(54,$winner['vf3'],$winner['vf4'],'', 'hf2');
            
            // Spiele in die Datenbank einfügen
            foreach ($halbfinale as $finale) {
                Spiel::create($finale);
            }
            session(['halbfinale_generiert' => true]);
        }
        return redirect()->back()->with('success', 'Spiele wurden erfolgreich generiert.'); 
    }

    public function generiereFinals()
    {
        // Setze die Session-Variable zurück, wenn sie einmal angezeigt wurde
        $halbfinaleGeneriert = session('halbfinale_generiert', false); // Standardwert false
        if ($halbfinaleGeneriert) {
            $spiele = Spiel::where('runde', 'REGEXP', '^hf')->get();
            $winner=[];
            $loser=[];
            foreach ($spiele as $spiel) {
                if ($spiel['punkteA']>$spiel['punkteB']) {
                    $winner[$spiel['runde']]=$spiel['teamA'];
                    $loser[$spiel['runde']]=$spiel['teamB'];
                }
                else {
                    $winner[$spiel['runde']]=$spiel['teamB'];
                    $loser[$spiel['runde']]=$spiel['teamA'];}       
            }
                 
            $finale = [];          
            //finalspiel gemäß Plan
            $finale[] = self::erstelleSpiel(55,$winner['hf1'],$winner['hf2'],'', 'ff1');
            $finale[] = self::erstelleSpiel(56,$loser['hf1'],$loser['hf2'],'', 'ff2');
            
            // Spiele in die Datenbank einfügen
            foreach ($finale as $final) {
                Spiel::create($final);
            }
        }
        return redirect()->back()->with('success', 'Spiele wurden erfolgreich generiert.'); 
    }
}
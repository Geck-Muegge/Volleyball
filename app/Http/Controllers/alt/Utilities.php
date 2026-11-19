<?php

namespace App\Http\Controllers;

class Utilities extends Controller
{
    //


    function generateGroupMatches($teams) {
        $numTeams = count($teams);
        $matches = [];

        // Wenn die Anzahl der Teams ungerade ist, ein Team als spielfrei auswählen
        if ($numTeams % 2 != 0) {
            $freeTeam = array_pop($teams); // Nimm das letzte Team als spielfrei
        } else {
            $freeTeam = null; // Kein spielfreies Team
        }

        // Generiere die Spiele
        for ($i = 0; $i < $numTeams - 1; $i++) {
            for ($j = $i + 1; $j < $numTeams; $j++) {
                // Wenn ein Team spielfrei ist, setze es als Schiedsrichter
                if ($freeTeam) {
                    $matches[] = [
                        'teamA' => $teams[$i],
                        'teamB' => $teams[$j],
                        'schiedsrichter' => $freeTeam,
                    ];
                } else {
                    $matches[] = [
                        'teamA' => $teams[$i],
                        'teamB' => $teams[$j],
                        'schiedsrichter' => null,
                    ];
                }
            }
        }

        return $matches;
    }

}

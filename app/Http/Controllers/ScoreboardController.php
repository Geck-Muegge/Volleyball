<?php

namespace App\Http\Controllers;

use App\Models\Spiel;
use App\Models\SpielLiveScore;
use Illuminate\Http\Request;

class ScoreboardController extends Controller
{
    public function index($spielid)
    {
        $spiel = Spiel::where('spielid', $spielid)->firstOrFail();
        return view('mobile.scoreboard', compact('spiel'));
    }

    public function getCurrentGame($spielid)
    {
        try {
            $spiel = Spiel::where('spielid', $spielid)->first();

            if (!$spiel) {
                return response()->json(['error' => 'Spiel nicht gefunden'], 404);
            }

            // Lese aus spiel_live_scores
            $liveScore = SpielLiveScore::where('spielid', $spielid)->first();

            $punkteA = $liveScore ? (int)$liveScore->punkteA : (int)($spiel->punkteA ?? 0);
            $punkteB = $liveScore ? (int)$liveScore->punkteB : (int)($spiel->punkteB ?? 0);

            // Runde-Text
            $runde = $spiel->runde ?? null;
            $rundeText = '';

            if (in_array($runde, ['A', 'B', 'C', 'D'])) {
                $rundeText = 'Gruppenspiel ' . $runde;
            } elseif (in_array($runde, ['VF1', 'VF2', 'VF3', 'VF4'])) {
                $rundeText = 'Viertelfinale ' . substr($runde, 2);
            } elseif (in_array($runde, ['HF1', 'HF2'])) {
                $rundeText = 'Halbfinale ' . substr($runde, 2);
            } elseif ($runde === 'FI' || $runde === 'Finale') {
                $rundeText = 'Finale';
            } elseif ($runde === 'P3' || $runde === 'Spiel um Platz 3') {
                $rundeText = 'Spiel um Platz 3';
            }

            $teamA = $spiel->teamA ?? ($spiel->teamAFK->teamName ?? 'Team A');
            $teamB = $spiel->teamB ?? ($spiel->teamBFK->teamName ?? 'Team B');

            return response()->json([
                'spiel' => [
                    'spielid' => (int)$spiel->spielid,
                    'teamA' => $teamA,
                    'teamB' => $teamB,
                    'punkteA' => $punkteA,
                    'punkteB' => $punkteB,
                    'runde' => $rundeText,
                    'status' => $spiel->status ?? null,
                    'result_status' => $spiel->result_status ?? null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}

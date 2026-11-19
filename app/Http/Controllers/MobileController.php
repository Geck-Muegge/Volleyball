<?php

namespace App\Http\Controllers;

use App\Models\Spiel;
use App\Models\SpielLiveScore;
use Illuminate\Http\Request;

class MobileController extends Controller
{
    public function score($spielid)
    {
        $spiel = Spiel::where('spielid', $spielid)->firstOrFail();
        $liveScore = SpielLiveScore::where('spielid', $spielid)->first();
        return view('mobile.score', compact('spiel', 'liveScore'));
    }


    // Jeder Button-Klick: speichert in spiel_live_scores
    public function updateLiveScore(Request $request)
    {
        try {
            $validated = $request->validate([
                'spielid' => 'required|integer|exists:spiel,spielid',
                'punkteA' => 'required|integer|min:0|max:50',
                'punkteB' => 'required|integer|min:0|max:50',
            ]);

            // Finde oder erstelle
            $liveScore = SpielLiveScore::where('spielid', $validated['spielid'])->first();

            if ($liveScore) {
                $liveScore->punkteA = $validated['punkteA'];
                $liveScore->punkteB = $validated['punkteB'];
                $liveScore->save();
            } else {
                SpielLiveScore::create([
                    'spielid' => $validated['spielid'],
                    'punkteA' => $validated['punkteA'],
                    'punkteB' => $validated['punkteB']
                ]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // Nur Button "Spiel abschließen": speichert in spiel
    public function submitResult(Request $request)
    {
        $spielid = $request->spielid;
        $punkteA = $request->punkteA;
        $punkteB = $request->punkteB;

        // Hole Live-Score
        $liveScore = SpielLiveScore::where('spielid', $spielid)->first();

        if ($liveScore) {
            // Speichere in spiel Tabelle
            $spiel = Spiel::where('spielid', $spielid)->first();
            $spiel->punkteA = $punkteA;
            $spiel->punkteB = $punkteB;
            $spiel->status = 'pending';  // ← Status auf pending
            $spiel->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Spielgruppe;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $spielgruppen = Spielgruppe::orderBy('gruppenBez')->get();

        // Teams nach Gruppen gruppieren
        $teamsByGruppe = [];
        foreach ($spielgruppen as $gruppe) {
            $teamsByGruppe[$gruppe->gruppenBez] = Team::where('gruppenid', $gruppe->gruppenBez)
                ->orderBy('nummer')
                ->get();
        }

        // Unzugeordnete Teams
        $unassignedTeams = Team::whereNull('gruppenid')->orderBy('teamname')->get();

        return view('teams.index', compact('spielgruppen', 'teamsByGruppe', 'unassignedTeams'));
    }

    /**
     * Hole alle Teams einer Gruppe, sortiert nach Nummer
     */
    public function getTeamsByGruppe($gruppenBez)
    {
        return Team::where('gruppenid', $gruppenBez)
            ->orderBy('nummer')
            ->get();
    }

    /**
     * Erstelle ein Array mit Nummer => teamName
     */
    public function getTeamsByPositionMap($gruppenBez)
    {
        $teams = $this->getTeamsByGruppe($gruppenBez);

        $teamsByPosition = [];
        foreach ($teams as $team) {
            $teamsByPosition[$team->nummer] = $team->teamName;
        }

        return $teamsByPosition;
    }

    public function create()
    {
        $spielgruppen = Spielgruppe::all();
        return view('teams.create', compact('spielgruppen'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teamName' => ['required', 'string', 'max:100', 'unique:teams,teamName'],
            'klasse' => ['required', 'string', 'max:100'],
        ]);

        Team::create($validated);

        return redirect()->route('teams.index')->with('success', 'Team erfolgreich hinzugefügt.');
    }

    public function updatePosition(Request $request, string $teamName)
    {
        $validated = $request->validate([
            'gruppenid' => ['nullable', 'string', 'exists:spielgruppe,gruppenBez'],
            'nummer' => ['nullable', 'integer', 'min:1'],
        ]);

        $team = Team::where('teamname', $teamName)->firstOrFail();
        $team->gruppenid = $validated['gruppenid'] ?? null;
        $team->nummer = $validated['nummer'] ?? null;
        $team->save();

        return response()->json(['success' => true]);
    }

    public function getTeamsByGroup($gruppenid)
    {
        return Team::where('gruppenid', $gruppenid)->get();
    }

    public static function getTeam(Request $request, $gruppenid, $nummer)
    {
        return Team::where('gruppenid', $gruppenid)
                    ->where('nummer', $nummer)
                    ->first();
    }
}



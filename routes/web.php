<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\SpielController;
use App\Http\Controllers\FinalspielController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MobileController;
use App\Http\Controllers\ScoreboardController;
use App\Http\Controllers\ResultController;

// Login-Routes (ohne Auth-Middleware)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Öffentliche Team-Anmeldung (ohne Auth)
//Route::get('/anmeldung', [TeamController::class, 'create'])->name('teams.create.public');
//Route::post('/anmeldung', [TeamController::class, 'store'])->name('teams.store.public');

// Geschützte Routes (mit Auth-Middleware)
Route::middleware('auth')->group(function () {

    // Team-Routes
    Route::resource('teams', TeamController::class);
    Route::get('/teams/{gruppenid}', [TeamController::class, 'getTeam']);
    Route::post('/teams/{teamName}/position', [TeamController::class, 'updatePosition'])
        ->name('teams.updatePosition');

    // Spiel-Routes
    Route::get('/spiele/neu', [SpielController::class, 'generiereGruppenspiele'])->name('spiele.neu');
    Route::get('/spiele/index', [SpielController::class, 'index'])->name('spiele.index');

    Route::get('/spiele/edit{spielid}', [SpielController::class, 'edit'])->name('spiele.edit');
    Route::put('/spiele/update{spielid}', [SpielController::class, 'update'])->name('spiele.update');
    Route::put('/spiele/{spielid}', [SpielController::class, 'update'])->name('spiele.update');
    //Route::get('/spiele/alle', [SpielController::class, 'zeigeAlleSpiele'])->name('spiele.alle');
    Route::get('/spiele/{gruppenid}', [SpielController::class, 'zeigeSpielJeGruppe'])->name('spiele.gruppe');

    // Spielgruppe-Routes
    //Route::get('/spielgruppe', [SpielgruppeController::class, 'index'])->name('spielgruppe.index');
    //Route::get('/standings', [SpielController::class, 'standings'])->name('spiele.standings');
    //Route::get('/spiele/tabellengruppe/{gruppenid}', [SpielController::class, 'zeigeTabelleJeGruppe'])->name('spiele.tabellengruppe');
    Route::get('/spiele/tabellengruppe/{gruppenid?}', [SpielController::class, 'zeigeTeamsJeGruppe'])->name('spiele.tabellengruppe');

    //Route::post('/platzierungen/speichern', [SpielController::class, 'speichern'])->name('platzierungen.speichern');

    // FINALSPIEL-Routes
   // Route::get('/finalspiel/finalrundenmenue', [FinalspielController::class, 'finalrundenmenue'])->name('finalspiel.zeigeMenue');
    Route::get('/finalspiel/index', [FinalspielController::class, 'index'])->name('finalspiel.index');
    Route::get('/finalspiel/edit/{spielid}', [FinalspielController::class, 'edit'])->name('finalspiel.edit');
    Route::put('/finalspiel/{spielid}', [FinalspielController::class, 'update'])->name('finalspiel.update');
    Route::get('/finalspiel/editfeld/{spielid}', [FinalspielController::class, 'editfeld'])->name('finalspiel.editfeld');
    Route::put('/finalspiel/updatefeld/{spielid}', [FinalspielController::class, 'updatefeld'])->name('finalspiel.updatefeld');
    Route::get('/finalspiel/{runde}', [FinalspielController::class, 'zeigeSpielJeFinalrunde'])->name('finalspiel.spieleJeRunde');
    Route::post('/finalspiel/viertelfinale', [FinalspielController::class, 'generiereViertelfinale'])->name('finalspiel.viertelfinale');
    Route::post('/finalspiel/generiereHF', [FinalspielController::class, 'generiereHalbfinale'])->name('finalspiel.generiereHF');
    Route::post('/finalspiel/generiereFF', [FinalspielController::class, 'generiereFinals'])->name('finalspiel.generiereFF');
   //Route::get('/finalboard', [FinalboardController::class, 'index'])->name('finalboard.index');

   //Mobile Routes SCoreboard
   // Mobile Routes - besser organisiert
    Route::get('/mobile/score/{spielid}', [MobileController::class, 'score'])->name('mobile.score');
    Route::post('/mobile/update-live-score', [MobileController::class, 'updateLiveScore'])->name('mobile.update-live-score');
    Route::post('/mobile/submit-result', [MobileController::class, 'submitResult'])->name('mobile.submit-result');

    // Scoreboard
    Route::get('/scoreboard/{spielid}', [ScoreboardController::class, 'index'])->name('scoreboard');
    Route::get('/api/current-game/{spielid}', [ScoreboardController::class, 'getCurrentGame']);
    //Route::get('/api/score-stream/{spielid}', [ScoreboardController::class, 'scoreStream']);

    //Bestätigung der Ergebnisse
    Route::middleware(['auth'])->group(function () {
    //Route::get('/admin/pending-results', [ResultController::class, 'index'])->name('admin.pending-results');
    Route::post('/admin/pending-results/approve', [ResultController::class, 'approve'])->name('admin.approve-result');
    Route::post('/admin/pending-results/reject', [ResultController::class, 'reject'])->name('admin.reject-result');
    });
    Route::get('/api/pending-results', function () {
    return \App\Models\Spiel::where('status', 'pending')->get(); });

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});



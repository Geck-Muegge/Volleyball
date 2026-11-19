<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\SpielController;
use App\Http\Controllers\SpielgruppeController;
use App\Http\Controllers\FinalspielController;
use App\Http\Controllers\FinalboardController;
use App\Http\Controllers\Auth\AuthController;

// Login-Routes (ohne Auth-Middleware)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Öffentliche Team-Anmeldung (ohne Auth)
Route::get('/anmeldung', [TeamController::class, 'create'])->name('teams.create.public');
Route::post('/anmeldung', [TeamController::class, 'store'])->name('teams.store.public');

// Geschützte Routes (mit Auth-Middleware)
Route::middleware('auth')->group(function () {

    // Team-Routes
    Route::resource('teams', TeamController::class);
    Route::get('/teams/{gruppenid}', [TeamController::class, 'getTeam']);
    Route::post('/teams/{teamName}/position', [TeamController::class, 'updatePosition'])
        ->name('teams.updatePosition');

    // Spiel-Routes
    Route::get('/spiele/index', [SpielController::class, 'index'])->name('spiele.index');
    Route::get('/spiele/erzeugen', [SpielController::class, 'generiereGruppenspiele'])->name('spiele.erzeugen');
    Route::get('/spiele/edit{spielid}', [SpielController::class, 'edit'])->name('spiele.edit');
    Route::put('/spiele/update{spielid}', [SpielController::class, 'update'])->name('spiele.update');
    Route::get('/spiele/alle', [SpielController::class, 'zeigeAlleSpiele'])->name('spiele.alle');
    Route::get('/spiele/{gruppenid}', [SpielController::class, 'zeigeSpielJeGruppe'])->name('spiele.gruppe');

    // Spielgruppe-Routes
    Route::get('/spielgruppe', [SpielgruppeController::class, 'index'])->name('spielgruppe.index');
    //Route::get('/standings', [SpielController::class, 'standings'])->name('spiele.standings');
    //Route::get('/spiele/tabellengruppe/{gruppenid}', [SpielController::class, 'zeigeTabelleJeGruppe'])->name('spiele.tabellengruppe');
    Route::get('/spiele/tabellengruppe/{gruppenid?}', [SpielController::class, 'zeigeTabelleJeGruppe'])->name('spiele.tabellengruppe');

    Route::post('/platzierungen/speichern', [SpielController::class, 'speichern'])->name('platzierungen.speichern');

    // Finalspiel-Routes
    Route::get('/finalspiel/finalrundenmenue', [FinalspielController::class, 'finalrundenmenue'])->name('finalspiel.zeigeMenue');
    Route::get('/finalspiel/index', [FinalspielController::class, 'index'])->name('finalspiel.index');
    Route::get('/finalrunde/edit/{type}/{id}', [FinalrundeController::class, 'edit'])->name('finalrunde.edit');
    Route::put('/finalrunde/update/{type}/{id}', [FinalrundeController::class, 'update'])->name('finalrunde.update');

    Route::get('/finalspiel/edit{spielid}', [FinalspielController::class, 'edit'])->name('finalspiel.edit');
    Route::get('/finalspiel/editfeld/{spielid}', [FinalspielController::class, 'editfeld'])->name('finalspiel.editfeld');
    Route::put('/finalspiel/update{spielid}', [FinalspielController::class, 'update'])->name('finalspiel.update');
    Route::put('/finalspiel/updatefeld/{spielid}', [FinalspielController::class, 'updatefeld'])->name('finalspiel.updatefeld');
    Route::get('/finalspiel/{runde}', [FinalspielController::class, 'zeigeSpielJeFinalrunde'])->name('finalspiel.spieleJeRunde');
    Route::post('/finalspiel/generiereAF', [FinalspielController::class, 'generiereAchtelfinale'])->name('finalspiel.generiereAF');
    Route::post('/finalspiel/generiereVF', [FinalspielController::class, 'generiereViertelfinale'])->name('finalspiel.generiereVF');
    Route::post('/finalspiel/generiereHF', [FinalspielController::class, 'generiereHalbfinale'])->name('finalspiel.generiereHF');
    Route::post('/finalspiel/generiereFF', [FinalspielController::class, 'generiereFinals'])->name('finalspiel.generiereFF');
    Route::post('/finalspiel/generiereTR', [FinalspielController::class, 'generiereTrostrunde'])->name('finalspiel.generiereTrostrunde');
    Route::get('/finalboard', [FinalboardController::class, 'index'])->name('finalboard.index');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});



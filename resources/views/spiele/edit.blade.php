@extends('layouts.app')

@section('title', 'Spiel bearbeiten')

@section('content')
<div class="container-main" style="padding-top: 40px;">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> Es gab einen Fehler:
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $gruppenFarben = [
            'A' => '#FF6B6B',
            'B' => '#4ECDC4',
            'C' => '#45B7D1',
            'D' => '#FFA07A'
        ];
        $gruppenFarbe = $gruppenFarben[$spiel->teamAFK->gruppenid] ?? '#1D68AC';
    @endphp

    <div style="max-width: 700px; margin: 0 auto;">
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 40px;">
            <h1 style="font-size: 28px; font-weight: 700; color: {{ $gruppenFarbe }}; margin-bottom: 10px;">
                <i class="fas fa-volleyball-ball"></i> Spiel bearbeiten
            </h1>
            <p style="color: #999; font-size: 14px;">
                Gruppe {{ $spiel->teamAFK->gruppenid }} • Feld {{ $spiel->feld }}
            </p>
        </div>

        <!-- Spiel-Übersicht -->
        <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); margin-bottom: 30px; border-top: 4px solid {{ $gruppenFarbe }};">
            <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 20px; align-items: center; margin-bottom: 30px;">
                <!-- Team A -->
                <div style="text-align: right;">
                    <div style="font-size: 18px; font-weight: 700; color: #1D68AC; margin-bottom: 5px;">
                        {{ $spiel->teamAFK->teamName }}
                    </div>
                    <div style="font-size: 13px; color: #999;">
                        {{ $spiel->teamAFK->klasse }}
                    </div>
                </div>

                <!-- vs -->
                <div style="text-align: center; color: #ccc; font-weight: 600; font-size: 14px;">
                    vs
                </div>

                <!-- Team B -->
                <div style="text-align: left;">
                    <div style="font-size: 18px; font-weight: 700; color: #1D68AC; margin-bottom: 5px;">
                        {{ $spiel->teamBFK->teamName }}
                    </div>
                    <div style="font-size: 13px; color: #999;">
                        {{ $spiel->teamBFK->klasse }}
                    </div>
                </div>
            </div>

            <!-- Eingabe-Formular -->
            <form method="POST" action="{{ route('spiele.update', $spiel->spielid) }}">
                @csrf
                @method('PUT')

                <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 20px; align-items: flex-end;">
                    <!-- Punkte Team A -->
                    <div>
                        <label style="display: block; font-weight: 600; color: #333; margin-bottom: 10px; font-size: 14px;">
                            Punkte {{ $spiel->teamAFK->teamName }}
                        </label>
                        <input
                            type="number"
                            name="punkteA"
                            id="punkteA"
                            value="{{ $spiel->punkteA }}"
                            required
                            min="0"
                            max="50"
                            step="1"
                            style="width: 100%; padding: 14px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 18px; font-weight: 700; text-align: center; color: {{ $gruppenFarbe }}; transition: border-color 0.3s;"
                            onchange="updateScore()"
                            onfocus="this.style.borderColor='{{ $gruppenFarbe }}'"
                            onblur="this.style.borderColor='#e0e0e0'"
                        >
                    </div>

                    <!-- Trennstrich -->
                    <div style="text-align: center; color: #ccc; font-weight: 600; font-size: 24px; margin-bottom: 5px;">
                        :
                    </div>

                    <!-- Punkte Team B -->
                    <div>
                        <label style="display: block; font-weight: 600; color: #333; margin-bottom: 10px; font-size: 14px;">
                            Punkte {{ $spiel->teamBFK->teamName }}
                        </label>
                        <input
                            type="number"
                            name="punkteB"
                            id="punkteB"
                            value="{{ $spiel->punkteB }}"
                            required
                            min="0"
                            max="50"
                            step="1"
                            style="width: 100%; padding: 14px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 18px; font-weight: 700; text-align: center; color: {{ $gruppenFarbe }}; transition: border-color 0.3s;"
                            onchange="updateScore()"
                            onfocus="this.style.borderColor='{{ $gruppenFarbe }}'"
                            onblur="this.style.borderColor='#e0e0e0'"
                        >
                    </div>
                </div>

                <!-- Ergebnis-Vorschau -->
                <div id="result-preview" style="background: #f9f9f9; border-radius: 8px; padding: 15px; margin-top: 20px; text-align: center; display: none;">
                    <div style="font-size: 12px; color: #999; margin-bottom: 8px;">Ergebnis:</div>
                    <div id="result-text" style="font-size: 16px; font-weight: 700; color: {{ $gruppenFarbe }};"></div>
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 12px; margin-top: 30px;">
                    <button
                        type="submit"
                        style="flex: 1; background: {{ $gruppenFarbe }}; color: white; padding: 14px; border: none; border-radius: 8px; font-weight: 600; font-size: 16px; cursor: pointer; transition: opacity 0.3s;"
                        onmouseover="this.style.opacity='0.9'"
                        onmouseout="this.style.opacity='1'"
                    >
                        <i class="fas fa-save"></i> Speichern
                    </button>
                    <a
                        href="{{ route('spiele.index') }}"
                        style="flex: 1; background: #e0e0e0; color: #333; padding: 14px; border-radius: 8px; font-weight: 600; font-size: 16px; text-decoration: none; text-align: center; transition: opacity 0.3s;"
                        onmouseover="this.style.opacity='0.8'"
                        onmouseout="this.style.opacity='1'"
                    >
                        <i class="fas fa-times"></i> Abbrechen
                    </a>
                </div>
            </form>
        </div>

        <!-- Info-Box -->
        <div style="background: #f0f4f8; border-left: 4px solid #1D68AC; border-radius: 8px; padding: 16px;">
            <p style="margin: 0; color: #666; font-size: 13px;">
                <i class="fas fa-info-circle" style="color: #1D68AC; margin-right: 8px;"></i>
                <strong>Hinweis:</strong> Geben Sie die Punkte für beide Teams ein und speichern Sie das Ergebnis.
            </p>
        </div>
    </div>
</div>

<script>
    function updateScore() {
        const punkteA = document.getElementById('punkteA').value;
        const punkteB = document.getElementById('punkteB').value;
        const preview = document.getElementById('result-preview');
        const resultText = document.getElementById('result-text');

        if (punkteA !== '' && punkteB !== '') {
            let result = '';
            if (punkteA > punkteB) {
                result = `{{ $spiel->teamAFK->teamName }} gewinnt ${punkteA}:${punkteB}`;
            } else if (punkteB > punkteA) {
                result = `{{ $spiel->teamBFK->teamName }} gewinnt ${punkteB}:${punkteA}`;
            } else {
                result = `Unentschieden ${punkteA}:${punkteB}`;
            }
            resultText.textContent = result;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }

    // Beim Laden der Seite prüfen
    window.addEventListener('load', updateScore);
</script>

<style>
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    @media (max-width: 768px) {
        div[style*="grid-template-columns: 1fr auto 1fr"] {
            grid-template-columns: 1fr !important;
            gap: 15px !important;
        }

        h1 {
            font-size: 22px !important;
        }
    }
</style>
@endsection

@extends('layouts.app')

@section('title', 'Gruppenspiele')

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
    @endphp

    <!-- Tab Navigation -->
    <div style="display: flex; gap: 10px; margin-bottom: 30px; border-bottom: 3px solid #e0e0e0; flex-wrap: wrap;">
        <a href="{{ route('spiele.index') }}" style="padding: 14px 24px; color: {{ !$selectedGroupId ? 'white' : '#999' }}; background: {{ !$selectedGroupId ? '#1D68AC' : 'transparent' }}; text-decoration: none; font-weight: 600; font-size: 16px; border-radius: 8px 8px 0 0; transition: all 0.3s; cursor: pointer;">
            <i class="fas fa-th"></i> Alle Gruppen
        </a>
        @foreach ($spielgruppen as $gruppe)
            <a href="{{ route('spiele.index', ['gruppenid' => $gruppe->gruppenBez]) }}" style="padding: 14px 24px; color: {{ $selectedGroupId === $gruppe->gruppenBez ? 'white' : '#999' }}; background: {{ $selectedGroupId === $gruppe->gruppenBez ? $gruppenFarben[$gruppe->gruppenBez] : 'transparent' }}; text-decoration: none; font-weight: 600; font-size: 16px; border-radius: 8px 8px 0 0; transition: all 0.3s;">
                Gruppe {{ $gruppe->gruppenBez }}
            </a>
        @endforeach
    </div>

    <!-- Games Section -->
    <div style="display: flex; justify-content: center;">
        @if ($spiele->count() > 0)
            @if (!$selectedGroupId)
                <!-- All Groups View - 4 Columns -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; width: 100%;">
                    @php
                        $spieleByGruppe = $spiele->groupBy('runde');
                    @endphp

                    @foreach (['A', 'B', 'C', 'D'] as $gruppe)
                        <div>
                            <h3 style="font-size: 18px; font-weight: 700; color: {{ $gruppenFarben[$gruppe] }}; margin-bottom: 15px; text-align: center;">
                                Gruppe {{ $gruppe }}
                            </h3>
                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                @forelse ($spieleByGruppe->get($gruppe, []) as $spiel)
                                    @php
                                        $isPlayed = ($spiel->punkteA !== null && $spiel->punkteB !== null) && ($spiel->punkteA > 0 || $spiel->punkteB > 0);
                                        $bgColor = $isPlayed ? '#f5f5f5' : 'white';
                                        $opacity = $isPlayed ? '0.85' : '1';
                                    @endphp

                                    <div style="background: {{ $bgColor }}; border-radius: 10px; padding: 15px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); transition: box-shadow 0.3s; border-left: 4px solid {{ $gruppenFarben[$gruppe] }}; opacity: {{ $opacity }};">
                                        <!-- Header -->
                                        <div style="font-size: 13px; color: #666; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                                            <span style="font-weight: 600;"><i class="fas fa-volleyball-ball"></i> Feld {{ $spiel->feld }}</span>
                                            <span style="font-weight: 600;">{{ $spiel->schiedsrichter ?? '-' }}</span>
                                        </div>

                                        <!-- Teams and Score -->
                                        <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 10px; align-items: center;">
                                            <!-- Team A -->
                                            <div style="text-align: right;">
                                                <div style="font-size: 15px; font-weight: 700; color: #1D68AC; line-height: 1.2; {{ $spiel->punkteA > $spiel->punkteB ? 'text-decoration: underline; text-decoration-thickness: 3px;' : '' }}">
                                                    {{ $spiel->teamAFK->teamName ?? 'Team A' }}
                                                </div>
                                                <div style="font-size: 11px; color: #999;">
                                                    {{ $spiel->teamAFK->klasse ?? '' }}
                                                </div>
                                            </div>

                                            <!-- Score -->
                                            <div style="text-align: center; padding: 0 8px;">
                                                <div style="display: flex; gap: 6px; align-items: center; justify-content: center;">
                                                    <div style="font-size: 20px; font-weight: 700; color: {{ $spiel->punkteA > $spiel->punkteB ? $gruppenFarben[$gruppe] : ($spiel->punkteA == $spiel->punkteB ? $gruppenFarben[$gruppe] : '#ccc') }};">
                                                        {{ $spiel->punkteA ?? '-' }}
                                                    </div>
                                                    <div style="font-size: 12px; color: #ccc;">:</div>
                                                    <div style="font-size: 20px; font-weight: 700; color: {{ $spiel->punkteB > $spiel->punkteA ? $gruppenFarben[$gruppe] : ($spiel->punkteA == $spiel->punkteB ? $gruppenFarben[$gruppe] : '#ccc') }};">
                                                        {{ $spiel->punkteB ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Team B -->
                                            <div style="text-align: left;">
                                                <div style="font-size: 15px; font-weight: 700; color: #1D68AC; line-height: 1.2; {{ $spiel->punkteB > $spiel->punkteA ? 'text-decoration: underline; text-decoration-thickness: 3px;' : '' }}">
                                                    {{ $spiel->teamBFK->teamName ?? 'Team B' }}
                                                </div>
                                                <div style="font-size: 11px; color: #999;">
                                                    {{ $spiel->teamBFK->klasse ?? '' }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Button -->
                                        <button type="button" class="btn btn-sm" style="display: block; width: 100%; background: {{ $gruppenFarben[$gruppe] }}; color: white; padding: 8px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 12px; margin-top: 10px; transition: background 0.3s; border: none; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#editResultModal" data-spiel-id="{{ $spiel->spielid }}" data-team-a="{{ $spiel->teamAFK->teamName }}" data-team-b="{{ $spiel->teamBFK->teamName }}" data-punkte-a="{{ $spiel->punkteA }}" data-punkte-b="{{ $spiel->punkteB }}" data-farbe="{{ $gruppenFarben[$gruppe] }}">
                                            <i class="fas fa-edit"></i> Ergebnis
                                        </button>
                                    </div>
                                @empty
                                    <div style="text-align: center; color: #999; padding: 20px; font-size: 12px;">
                                        Keine Spiele
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Single Group View - Full Width List -->
                @php $gruppenFarbe = $gruppenFarben[$selectedGroupId] ?? '#1D68AC'; @endphp
                <div style="display: flex; flex-direction: column; gap: 15px; max-width: 700px; width: 100%;">
                    @foreach ($spiele as $spiel)
                        @php
                            $isPlayed = ($spiel->punkteA !== null && $spiel->punkteB !== null) && ($spiel->punkteA > 0 || $spiel->punkteB > 0);
                            $bgColor = $isPlayed ? '#f5f5f5' : 'white';
                            $opacity = $isPlayed ? '0.85' : '1';
                        @endphp

                        <div style="background: {{ $bgColor }}; border-radius: 10px; padding: 18px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); transition: box-shadow 0.3s; border-left: 4px solid {{ $gruppenFarbe }}; opacity: {{ $opacity }};">
                            <!-- Header -->
                            <div style="font-size: 14px; color: #666; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: 600;"><i class="fas fa-volleyball-ball"></i> Feld {{ $spiel->feld }}</span>
                                <span style="font-weight: 600;">
                                    Schiedsgericht: {{ $spiel->schiedsrichter ?? '-' }}
                                </span>
                            </div>

                            <!-- Teams and Score -->
                            <div style="display: grid; grid-template-columns: 1fr auto 1fr auto; gap: 15px; align-items: center;">
                                <!-- Team A -->
                                <div style="text-align: right;">
                                    <div style="font-size: 17px; font-weight: 700; color: #1D68AC; line-height: 1.3; {{ $spiel->punkteA > $spiel->punkteB ? 'text-decoration: underline; text-decoration-thickness: 3px;' : '' }}">
                                        {{ $spiel->teamAFK->teamName ?? 'Team A' }}
                                    </div>
                                    <div style="font-size: 12px; color: #999;">
                                        {{ $spiel->teamAFK->klasse ?? '' }}
                                    </div>
                                </div>

                                <!-- Score -->
                                <div style="text-align: center; padding: 0 12px;">
                                    <div style="display: flex; gap: 8px; align-items: center; justify-content: center;">
                                        <div style="font-size: 28px; font-weight: 700; color: {{ $spiel->punkteA > $spiel->punkteB ? $gruppenFarbe : ($spiel->punkteA == $spiel->punkteB ? $gruppenFarbe : '#ccc') }};">
                                            {{ $spiel->punkteA ?? '-' }}
                                        </div>
                                        <div style="font-size: 14px; color: #ccc;">:</div>
                                        <div style="font-size: 28px; font-weight: 700; color: {{ $spiel->punkteB > $spiel->punkteA ? $gruppenFarbe : ($spiel->punkteA == $spiel->punkteB ? $gruppenFarbe : '#ccc') }};">
                                            {{ $spiel->punkteB ?? '-' }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Team B -->
                                <div style="text-align: left;">
                                    <div style="font-size: 17px; font-weight: 700; color: #1D68AC; line-height: 1.3; {{ $spiel->punkteB > $spiel->punkteA ? 'text-decoration: underline; text-decoration-thickness: 3px;' : '' }}">
                                        {{ $spiel->teamBFK->teamName ?? 'Team B' }}
                                    </div>
                                    <div style="font-size: 12px; color: #999;">
                                        {{ $spiel->teamBFK->klasse ?? '' }}
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <button type="button" class="btn btn-sm" style="background: {{ $gruppenFarbe }}; color: white; padding: 10px 14px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 13px; transition: background 0.3s; white-space: nowrap; border: none; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#editResultModal" data-spiel-id="{{ $spiel->spielid }}" data-team-a="{{ $spiel->teamAFK->teamName }}" data-team-b="{{ $spiel->teamBFK->teamName }}" data-punkte-a="{{ $spiel->punkteA }}" data-punkte-b="{{ $spiel->punkteB }}" data-farbe="{{ $gruppenFarbe }}">
                                    <i class="fas fa-edit"></i> Ergebnis
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div style="background: white; border-radius: 12px; padding: 60px 30px; text-align: center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <i class="fas fa-inbox" style="font-size: 48px; color: #ccc; margin-bottom: 20px; display: block;"></i>
                <h3 style="color: #666; margin-bottom: 10px;">Keine Spiele vorhanden</h3>
                <p style="color: #999; margin-bottom: 20px;">
                    Es gibt noch keine Gruppenspiele. Bitte generieren Sie diese zuerst.
                </p>
                @auth
                    <a href="{{ route('spiele.neu') }}" style="background: #1D68AC; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block; transition: background 0.3s;">
                        <i class="fas fa-plus"></i> Spiele generieren
                    </a>
                @endauth
            </div>
        @endif
    </div>
</div>

<!-- Result Modal -->
<div class="modal fade" id="editResultModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ergebnis eingeben</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="resultForm" method="POST" action="">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 20px; align-items: flex-end;">
                        <!-- Punkte Team A -->
                        <div>
                            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 10px; font-size: 14px;">
                                Punkte <span id="teamALabel">Team A</span>
                            </label>
                            <input
                                type="number"
                                name="punkteA"
                                id="punkteA"
                                required
                                min="0"
                                max="50"
                                step="1"
                                style="width: 100%; padding: 14px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 18px; font-weight: 700; text-align: center; color: #FF6B6B; transition: border-color 0.3s;"
                                onchange="updateScore()"
                                onfocus="this.style.borderColor='#FF6B6B'"
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
                                Punkte <span id="teamBLabel">Team B</span>
                            </label>
                            <input
                                type="number"
                                name="punkteB"
                                id="punkteB"
                                required
                                min="0"
                                max="50"
                                step="1"
                                style="width: 100%; padding: 14px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 18px; font-weight: 700; text-align: center; color: #FF6B6B; transition: border-color 0.3s;"
                                onchange="updateScore()"
                                onfocus="this.style.borderColor='#FF6B6B'"
                                onblur="this.style.borderColor='#e0e0e0'"
                            >
                        </div>
                    </div>

                    <!-- Ergebnis-Vorschau -->
                    <div id="result-preview" style="background: #f9f9f9; border-radius: 8px; padding: 15px; margin-top: 20px; text-align: center; display: none;">
                        <div style="font-size: 12px; color: #999; margin-bottom: 8px;">Ergebnis:</div>
                        <div id="result-text" style="font-size: 16px; font-weight: 700; color: #FF6B6B;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-primary">Speichern</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    @media (max-width: 1200px) {
        div[style*="grid-template-columns: repeat(4"] {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }

    @media (max-width: 768px) {
        div[style*="grid-template-columns: repeat(4"] {
            grid-template-columns: 1fr !important;
        }

        div[style*="display: flex; gap: 10px; margin-bottom: 30px"] {
            overflow-x: auto;
            padding-bottom: 10px;
        }

        div[style*="display: grid; grid-template-columns: 1fr auto 1fr auto"] {
            grid-template-columns: 1fr auto !important;
        }
    }
</style>

<script>
    function updateScore() {
        const punkteA = document.getElementById('punkteA').value;
        const punkteB = document.getElementById('punkteB').value;
        const teamA = document.getElementById('teamALabel').textContent;
        const teamB = document.getElementById('teamBLabel').textContent;
        const preview = document.getElementById('result-preview');
        const resultText = document.getElementById('result-text');

        if (punkteA !== '' && punkteB !== '') {
            let result = '';
            if (punkteA > punkteB) {
                result = `${teamA} gewinnt ${punkteA}:${punkteB}`;
            } else if (punkteB > punkteA) {
                result = `${teamB} gewinnt ${punkteB}:${punkteA}`;
            } else {
                result = `Unentschieden ${punkteA}:${punkteB}`;
            }
            resultText.textContent = result;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const resultModal = document.getElementById('resultModal');

        resultModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const spielId = button.getAttribute('data-spiel-id');
            const teamA = button.getAttribute('data-team-a');
            const teamB = button.getAttribute('data-team-b');
            const punkteA = button.getAttribute('data-punkte-a') || '';
            const punkteB = button.getAttribute('data-punkte-b') || '';
            const farbe = button.getAttribute('data-farbe');

            document.getElementById('teamALabel').textContent = teamA;
            document.getElementById('teamBLabel').textContent = teamB;
            document.getElementById('punkteA').value = punkteA;
            document.getElementById('punkteB').value = punkteB;
            document.getElementById('resultForm').action = `/spiele/${spielId}`;

            // Farbe anpassen
            document.getElementById('punkteA').style.color = farbe;
            document.getElementById('punkteB').style.color = farbe;
            document.getElementById('punkteA').style.borderColor = farbe;
            document.getElementById('punkteB').style.borderColor = farbe;
            document.getElementById('result-text').style.color = farbe;

            updateScore();
        });
    });
</script>
@endsection

@include('components.pending-results-modal')

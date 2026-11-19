@extends('layouts.app')

@section('title', 'Finalrunde')

@section('content')
<div class="container-main" style="padding-top: 40px;">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $farben = [
            'vf' => '#FF6B6B',
            'hf' => '#4ECDC4',
            'finale' => '#45B7D1',
            'platz3' => '#FFA07A'
        ];

        // Lade echte Spiele aus der DB
        $viertelfinaleSpiele = \App\Models\Spiel::whereIn('runde', ['VF1', 'VF2', 'VF3', 'VF4'])->get();
        $halbfinaleSpiele = \App\Models\Spiel::whereIn('runde', ['HF1', 'HF2'])->get();
        $finaleSpiele = \App\Models\Spiel::where('runde', 'FI')->get();
        $platz3Spiele = \App\Models\Spiel::where('runde', 'P3')->get();

        // Bestimme, ob Platzhalter oder echte Spiele angezeigt werden
        $showViertelfinale = $viertelfinaleSpiele->count() > 0;
        $showHalbfinale = $halbfinaleSpiele->count() > 0;
        $showFinale = $finaleSpiele->count() > 0;
        $showPlatz3 = $platz3Spiele->count() > 0;
    @endphp

    <!-- Bracket Container -->
    <div style="max-width: 1400px; margin: 0 auto; padding: 0 20px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1.2fr; gap: 30px; position: relative;">

            <!-- VIERTELFINALE -->
            <div>
                <h3 style="font-size: 22px; font-weight: 700; color: {{ $farben['vf'] }}; margin-bottom: 20px; text-align: center;">
                    Viertelfinale
                </h3>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    @if ($showViertelfinale)
                        <!-- Echte Viertelfinale aus DB -->
                        @foreach ($viertelfinaleSpiele->sortBy('runde') as $spiel)
                            @php
                                $isPlayed = ($spiel->punkteA !== null && $spiel->punkteB !== null) && ($spiel->punkteA > 0 || $spiel->punkteB > 0);
                                $bgColor = $isPlayed ? '#f5f5f5' : 'white';
                                $opacity = $isPlayed ? '0.85' : '1';
                            @endphp

                            <div style="background: {{ $bgColor }}; border-radius: 10px; padding: 15px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); border-left: 4px solid {{ $farben['vf'] }}; opacity: {{ $opacity }};">
                                <!-- Header -->
                                <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">
                                    <i class="fas fa-trophy"></i> {{ $spiel->runde }}
                                </div>

                                <!-- Teams and Score -->
                                <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 10px; align-items: center;">
                                    <!-- Team A -->
                                    <div style="text-align: right;">
                                        <div style="font-size: 14px; font-weight: 700; color: #1D68AC; line-height: 1.2; {{ $spiel->punkteA > $spiel->punkteB ? 'text-decoration: underline; text-decoration-thickness: 3px;' : '' }}">
                                            {{ $spiel->teamA }}
                                        </div>
                                    </div>

                                    <!-- Score -->
                                    <div style="text-align: center; padding: 0 8px;">
                                        <div style="display: flex; gap: 6px; align-items: center; justify-content: center;">
                                            <div style="font-size: 18px; font-weight: 700; color: {{ $spiel->punkteA > $spiel->punkteB ? $farben['vf'] : ($spiel->punkteA == $spiel->punkteB ? $farben['vf'] : '#ccc') }};">
                                                {{ $spiel->punkteA ?? '-' }}
                                            </div>
                                            <div style="font-size: 11px; color: #ccc;">:</div>
                                            <div style="font-size: 18px; font-weight: 700; color: {{ $spiel->punkteB > $spiel->punkteA ? $farben['vf'] : ($spiel->punkteA == $spiel->punkteB ? $farben['vf'] : '#ccc') }};">
                                                {{ $spiel->punkteB ?? '-' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Team B -->
                                    <div style="text-align: left;">
                                        <div style="font-size: 14px; font-weight: 700; color: #1D68AC; line-height: 1.2; {{ $spiel->punkteB > $spiel->punkteA ? 'text-decoration: underline; text-decoration-thickness: 3px;' : '' }}">
                                            {{ $spiel->teamB }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <a href="{{ route('finalspiel.edit', ['spielid' => $spiel->spielid]) }}" style="display: block; width: 100%; background: {{ $farben['vf'] }}; color: white; padding: 8px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 12px; margin-top: 10px; transition: background 0.3s; text-align: center;">
                                    <i class="fas fa-edit"></i> Ergebnis
                                </a>
                            </div>
                        @endforeach
                    @else
                        <!-- Platzhalter -->
                        @php
                            $viertelfinale = [
                                ['id' => 1, 'teamA' => '1. Gruppe A', 'teamB' => '2. Gruppe B'],
                                ['id' => 2, 'teamA' => '1. Gruppe B', 'teamB' => '2. Gruppe C'],
                                ['id' => 3, 'teamA' => '1. Gruppe C', 'teamB' => '2. Gruppe D'],
                                ['id' => 4, 'teamA' => '1. Gruppe D', 'teamB' => '2. Gruppe A'],
                            ];
                        @endphp

                        @foreach ($viertelfinale as $spiel)
                            <div style="background: white; border-radius: 10px; padding: 15px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); border-left: 4px solid {{ $farben['vf'] }}; opacity: 0.6;">
                                <!-- Header -->
                                <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">
                                    <i class="fas fa-trophy"></i> VF {{ $spiel['id'] }}
                                </div>

                                <!-- Teams and Score -->
                                <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 10px; align-items: center;">
                                    <!-- Team A -->
                                    <div style="text-align: right;">
                                        <div style="font-size: 14px; font-weight: 700; color: #999; line-height: 1.2;">
                                            {{ $spiel['teamA'] }}
                                        </div>
                                    </div>

                                    <!-- Score -->
                                    <div style="text-align: center; padding: 0 8px;">
                                        <div style="display: flex; gap: 6px; align-items: center; justify-content: center;">
                                            <div style="font-size: 18px; font-weight: 700; color: #ccc;">-</div>
                                            <div style="font-size: 11px; color: #ccc;">:</div>
                                            <div style="font-size: 18px; font-weight: 700; color: #ccc;">-</div>
                                        </div>
                                    </div>

                                    <!-- Team B -->
                                    <div style="text-align: left;">
                                        <div style="font-size: 14px; font-weight: 700; color: #999; line-height: 1.2;">
                                            {{ $spiel['teamB'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- HALBFINALE -->
            <div style="margin-top: 100px;">
                <h3 style="font-size: 22px; font-weight: 700; color: {{ $farben['hf'] }}; margin-bottom: 20px; text-align: center;">
                    Halbfinale
                </h3>
                <div style="display: flex; flex-direction: column; gap: 80px;">
                    @if ($showHalbfinale)
                        <!-- Echte Halbfinale aus DB -->
                        @foreach ($halbfinaleSpiele->sortBy('runde') as $spiel)
                            @php
                                $isPlayed = ($spiel->punkteA !== null && $spiel->punkteB !== null) && ($spiel->punkteA > 0 || $spiel->punkteB > 0);
                                $bgColor = $isPlayed ? '#f5f5f5' : 'white';
                                $opacity = $isPlayed ? '0.85' : '1';
                            @endphp

                            <div style="background: {{ $bgColor }}; border-radius: 10px; padding: 15px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); border-left: 4px solid {{ $farben['hf'] }}; opacity: {{ $opacity }};">
                                <!-- Header -->
                                <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">
                                    <i class="fas fa-trophy"></i> {{ $spiel->runde }}
                                </div>

                                <!-- Teams and Score -->
                                <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 10px; align-items: center;">
                                    <!-- Team A -->
                                    <div style="text-align: right;">
                                        <div style="font-size: 14px; font-weight: 700; color: #1D68AC; line-height: 1.2; {{ $spiel->punkteA > $spiel->punkteB ? 'text-decoration: underline; text-decoration-thickness: 3px;' : '' }}">
                                            {{ $spiel->teamA }}
                                        </div>
                                    </div>

                                    <!-- Score -->
                                    <div style="text-align: center; padding: 0 8px;">
                                        <div style="display: flex; gap: 6px; align-items: center; justify-content: center;">
                                            <div style="font-size: 18px; font-weight: 700; color: {{ $spiel->punkteA > $spiel->punkteB ? $farben['hf'] : ($spiel->punkteA == $spiel->punkteB ? $farben['hf'] : '#ccc') }};">
                                                {{ $spiel->punkteA ?? '-' }}
                                            </div>
                                            <div style="font-size: 11px; color: #ccc;">:</div>
                                            <div style="font-size: 18px; font-weight: 700; color: {{ $spiel->punkteB > $spiel->punkteA ? $farben['hf'] : ($spiel->punkteA == $spiel->punkteB ? $farben['hf'] : '#ccc') }};">
                                                {{ $spiel->punkteB ?? '-' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Team B -->
                                    <div style="text-align: left;">
                                        <div style="font-size: 14px; font-weight: 700; color: #1D68AC; line-height: 1.2; {{ $spiel->punkteB > $spiel->punkteA ? 'text-decoration: underline; text-decoration-thickness: 3px;' : '' }}">
                                            {{ $spiel->teamB }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <a href="{{ route('finalspiel.edit', ['spielid' => $spiel->spielid]) }}" style="display: block; width: 100%; background: {{ $farben['hf'] }}; color: white; padding: 8px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 12px; margin-top: 10px; transition: background 0.3s; text-align: center;">
                                    <i class="fas fa-edit"></i> Ergebnis
                                </a>
                            </div>
                        @endforeach
                    @else
                        <!-- Platzhalter -->
                        @php
                            $halbfinale = [
                                ['id' => 1, 'teamA' => 'Sieger VF 1', 'teamB' => 'Sieger VF 2'],
                                ['id' => 2, 'teamA' => 'Sieger VF 3', 'teamB' => 'Sieger VF 4'],
                            ];
                        @endphp

                        @foreach ($halbfinale as $spiel)
                            <div style="background: white; border-radius: 10px; padding: 15px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); border-left: 4px solid {{ $farben['hf'] }}; opacity: 0.6;">
                                <!-- Header -->
                                <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">
                                    <i class="fas fa-trophy"></i> HF {{ $spiel['id'] }}
                                </div>

                                <!-- Teams and Score -->
                                <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 10px; align-items: center;">
                                    <!-- Team A -->
                                    <div style="text-align: right;">
                                        <div style="font-size: 14px; font-weight: 700; color: #999; line-height: 1.2;">
                                            {{ $spiel['teamA'] }}
                                        </div>
                                    </div>

                                    <!-- Score -->
                                    <div style="text-align: center; padding: 0 8px;">
                                        <div style="display: flex; gap: 6px; align-items: center; justify-content: center;">
                                            <div style="font-size: 18px; font-weight: 700; color: #ccc;">-</div>
                                            <div style="font-size: 11px; color: #ccc;">:</div>
                                            <div style="font-size: 18px; font-weight: 700; color: #ccc;">-</div>
                                        </div>
                                    </div>

                                    <!-- Team B -->
                                    <div style="text-align: left;">
                                        <div style="font-size: 14px; font-weight: 700; color: #999; line-height: 1.2;">
                                            {{ $spiel['teamB'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- FINALE & PLATZ 3 -->
            <div style="margin-top: 100px;">
                <!-- FINALE -->
                <div style="margin-bottom: 80px;">
                    <h3 style="font-size: 22px; font-weight: 700; color: {{ $farben['finale'] }}; margin-bottom: 20px; text-align: center;">
                        Finale
                    </h3>
                    @if ($showFinale)
                        <!-- Echtes Finale aus DB -->
                        @php $finale = $finaleSpiele->first(); @endphp
                        @if ($finale)
                            @php
                                $isPlayed = ($finale->punkteA !== null && $finale->punkteB !== null) && ($finale->punkteA > 0 || $finale->punkteB > 0);
                                $bgColor = $isPlayed ? '#f5f5f5' : 'white';
                                $opacity = $isPlayed ? '0.85' : '1';
                            @endphp

                            <div style="margin-top: 20px;">
                                <div style="background: {{ $bgColor }}; border-radius: 10px; padding: 15px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); border-left: 4px solid {{ $farben['finale'] }}; opacity: {{ $opacity }};">
                                    <!-- Header -->
                                    <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">
                                        <i class="fas fa-trophy"></i> Finale
                                    </div>

                                    <!-- Teams and Score -->
                                    <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 10px; align-items: center;">
                                        <!-- Team A -->
                                        <div style="text-align: right;">
                                            <div style="font-size: 14px; font-weight: 700; color: #1D68AC; line-height: 1.2; {{ $finale->punkteA > $finale->punkteB ? 'text-decoration: underline; text-decoration-thickness: 3px;' : '' }}">
                                                {{ $finale->teamA }}
                                            </div>
                                        </div>

                                        <!-- Score -->
                                        <div style="text-align: center; padding: 0 8px;">
                                            <div style="display: flex; gap: 6px; align-items: center; justify-content: center;">
                                                <div style="font-size: 18px; font-weight: 700; color: {{ $finale->punkteA > $finale->punkteB ? $farben['finale'] : ($finale->punkteA == $finale->punkteB ? $farben['finale'] : '#ccc') }};">
                                                    {{ $finale->punkteA ?? '-' }}
                                                </div>
                                                <div style="font-size: 11px; color: #ccc;">:</div>
                                                <div style="font-size: 18px; font-weight: 700; color: {{ $finale->punkteB > $finale->punkteA ? $farben['finale'] : ($finale->punkteA == $finale->punkteB ? $farben['finale'] : '#ccc') }};">
                                                    {{ $finale->punkteB ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Team B -->
                                        <div style="text-align: left;">
                                            <div style="font-size: 14px; font-weight: 700; color: #1D68AC; line-height: 1.2; {{ $finale->punkteB > $finale->punkteA ? 'text-decoration: underline; text-decoration-thickness: 3px;' : '' }}">
                                                {{ $finale->teamB }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    <a href="{{ route('finalspiel.edit', ['spielid' => $finale->spielid]) }}" style="display: block; width: 100%; background: {{ $farben['finale'] }}; color: white; padding: 8px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 12px; margin-top: 10px; transition: background 0.3s; text-align: center;">
                                        <i class="fas fa-edit"></i> Ergebnis
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- Platzhalter -->
                        @php $finale = ['teamA' => 'Sieger HF 1', 'teamB' => 'Sieger HF 2']; @endphp
                        <div style="margin-top: 20px;">
                            <div style="background: white; border-radius: 10px; padding: 15px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); border-left: 4px solid {{ $farben['finale'] }}; opacity: 0.6;">
                                <!-- Header -->
                                <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">
                                    <i class="fas fa-trophy"></i> Finale
                                </div>

                                <!-- Teams and Score -->
                                <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 10px; align-items: center;">
                                    <!-- Team A -->
                                    <div style="text-align: right;">
                                        <div style="font-size: 14px; font-weight: 700; color: #999; line-height: 1.2;">
                                            {{ $finale['teamA'] }}
                                        </div>
                                    </div>

                                    <!-- Score -->
                                    <div style="text-align: center; padding: 0 8px;">
                                        <div style="display: flex; gap: 6px; align-items: center; justify-content: center;">
                                            <div style="font-size: 18px; font-weight: 700; color: #ccc;">-</div>
                                            <div style="font-size: 11px; color: #ccc;">:</div>
                                            <div style="font-size: 18px; font-weight: 700; color: #ccc;">-</div>
                                        </div>
                                    </div>

                                    <!-- Team B -->
                                    <div style="text-align: left;">
                                        <div style="font-size: 14px; font-weight: 700; color: #999; line-height: 1.2;">
                                            {{ $finale['teamB'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- PLATZ 3 -->
                <div>


                    @if ($showPlatz3)
                        <!-- Echtes Spiel um Platz 3 aus DB -->
                        @php $platz3 = $platz3Spiele->first(); @endphp
                        @if ($platz3)
                            @php
                                $isPlayed = ($platz3->punkteA !== null && $platz3->punkteB !== null) && ($platz3->punkteA > 0 || $platz3->punkteB > 0);
                                $bgColor = $isPlayed ? '#f5f5f5' : 'white';
                                $opacity = $isPlayed ? '0.85' : '1';
                            @endphp

                            <div style="background: {{ $bgColor }}; border-radius: 10px; padding: 15px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); border-left: 4px solid {{ $farben['platz3'] }}; opacity: {{ $opacity }};">
                                <!-- Header -->
                                <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">
                                    <i class="fas fa-medal"></i> Spiel um Platz 3
                                </div>

                                <!-- Teams and Score -->
                                <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 10px; align-items: center;">
                                    <!-- Team A -->
                                    <div style="text-align: right;">
                                        <div style="font-size: 14px; font-weight: 700; color: #1D68AC; line-height: 1.2; {{ $platz3->punkteA > $platz3->punkteB ? 'text-decoration: underline; text-decoration-thickness: 3px;' : '' }}">
                                            {{ $platz3->teamA }}
                                        </div>
                                    </div>

                                    <!-- Score -->
                                    <div style="text-align: center; padding: 0 8px;">
                                        <div style="display: flex; gap: 6px; align-items: center; justify-content: center;">
                                            <div style="font-size: 18px; font-weight: 700; color: {{ $platz3->punkteA > $platz3->punkteB ? $farben['platz3'] : ($platz3->punkteA == $platz3->punkteB ? $farben['platz3'] : '#ccc') }};">
                                                {{ $platz3->punkteA ?? '-' }}
                                            </div>
                                            <div style="font-size: 11px; color: #ccc;">:</div>
                                            <div style="font-size: 18px; font-weight: 700; color: {{ $platz3->punkteB > $platz3->punkteA ? $farben['platz3'] : ($platz3->punkteA == $platz3->punkteB ? $farben['platz3'] : '#ccc') }};">
                                                {{ $platz3->punkteB ?? '-' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Team B -->
                                    <div style="text-align: left;">
                                        <div style="font-size: 14px; font-weight: 700; color: #1D68AC; line-height: 1.2; {{ $platz3->punkteB > $platz3->punkteA ? 'text-decoration: underline; text-decoration-thickness: 3px;' : '' }}">
                                            {{ $platz3->teamB }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <a href="{{ route('finalspiel.edit', ['spielid' => $platz3->spielid]) }}" style="display: block; width: 100%; background: {{ $farben['platz3'] }}; color: white; padding: 8px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 12px; margin-top: 10px; transition: background 0.3s; text-align: center;">
                                    <i class="fas fa-edit"></i> Ergebnis
                                </a>
                            </div>
                            <h3 style="font-size: 22px; font-weight: 700; color: {{ $farben['platz3'] }}; margin-bottom: 20px; margin-top: 20px; text-align: center;">
                                Spiel um Platz 3
                            </h3>
                        @endif

                    @else
                        <!-- Platzhalter -->
                        @php $platz3 = ['teamA' => 'Verlierer HF 1', 'teamB' => 'Verlierer HF 2']; @endphp
                        <div style="background: white; border-radius: 10px; padding: 15px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); border-left: 4px solid {{ $farben['platz3'] }}; opacity: 0.6;">
                            <!-- Header -->
                            <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">
                                <i class="fas fa-medal"></i> Spiel um Platz 3
                            </div>

                            <!-- Teams and Score -->
                            <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 10px; align-items: center;">
                                <!-- Team A -->
                                <div style="text-align: right;">
                                    <div style="font-size: 14px; font-weight: 700; color: #999; line-height: 1.2;">
                                        {{ $platz3['teamA'] }}
                                    </div>
                                </div>

                                <!-- Score -->
                                <div style="text-align: center; padding: 0 8px;">
                                    <div style="display: flex; gap: 6px; align-items: center; justify-content: center;">
                                        <div style="font-size: 18px; font-weight: 700; color: #ccc;">-</div>
                                        <div style="font-size: 11px; color: #ccc;">:</div>
                                        <div style="font-size: 18px; font-weight: 700; color: #ccc;">-</div>
                                    </div>
                                </div>

                                <!-- Team B -->
                                <div style="text-align: left;">
                                    <div style="font-size: 14px; font-weight: 700; color: #999; line-height: 1.2;">
                                        {{ $platz3['teamB'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    a:hover {
        opacity: 0.9 !important;
    }

    @media (max-width: 1200px) {
        div[style*="grid-template-columns: 1fr 1fr 1.2fr"] {
            grid-template-columns: 1fr !important;
        }
    }

    @media (max-width: 768px) {
        h1 {
            font-size: 24px !important;
        }
    }
</style>
@endsection

@include('components.pending-results-modal')

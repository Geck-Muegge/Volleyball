<!-- resources/views/finalrunde.blade.php -->
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

    <h1 style="font-size: 32px; font-weight: 700; color: #1D68AC; margin-bottom: 40px; text-align: center;">
        <i class="fas fa-trophy"></i> Finalrunde
    </h1>

    <div style="max-width: 1400px; margin: 0 auto; padding: 0 20px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1.2fr; gap: 30px; position: relative;">

            <!-- VIERTELFINALE -->
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #FF6B6B; margin-bottom: 20px; text-align: center; padding-bottom: 10px; border-bottom: 2px solid #FF6B6B;">
                    Viertelfinale
                </h3>
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    @php
                        $viertelfinale = [
                            ['id' => 1, 'team1' => null, 'team2' => null, 'score1' => null, 'score2' => null],
                            ['id' => 2, 'team1' => null, 'team2' => null, 'score1' => null, 'score2' => null],
                            ['id' => 3, 'team1' => null, 'team2' => null, 'score1' => null, 'score2' => null],
                            ['id' => 4, 'team1' => null, 'team2' => null, 'score1' => null, 'score2' => null],
                        ];
                    @endphp

                    @foreach ($viertelfinale as $match)
                        <x-match-card
                            :match="$match"
                            variant="vf"
                            label="VF {{ $match['id'] }}"
                            dataAttribute="data-vf"
                            :dataValue="$match['id']"
                        />
                    @endforeach
                </div>
            </div>

            <!-- HALBFINALE -->
            <div style="margin-top: 100px;">
                <h3 style="font-size: 16px; font-weight: 700; color: #4ECDC4; margin-bottom: 20px; text-align: center; padding-bottom: 10px; border-bottom: 2px solid #4ECDC4;">
                    Halbfinale
                </h3>
                <div style="display: flex; flex-direction: column; gap: 80px;">
                    @php
                        $halbfinale = [
                            ['id' => 1, 'team1' => null, 'team2' => null, 'score1' => null, 'score2' => null],
                            ['id' => 2, 'team1' => null, 'team2' => null, 'score1' => null, 'score2' => null],
                        ];
                    @endphp

                    @foreach ($halbfinale as $match)
                        <x-match-card
                            :match="$match"
                            variant="hf"
                            label="HF {{ $match['id'] }}"
                            dataAttribute="data-hf"
                            :dataValue="$match['id']"
                        />
                    @endforeach
                </div>
            </div>

            <!-- FINALE & PLATZ 3 -->
            <div>
                <!-- FINALE -->
                <div style="margin-bottom: 80px;">
                    <h3 style="font-size: 16px; font-weight: 700; color: #45B7D1; margin-bottom: 20px; text-align: center; padding-bottom: 10px; border-bottom: 2px solid #45B7D1;">
                        Finale
                    </h3>
                    <div style="margin-top: 100px;">
                        <x-match-card
                            :match="['team1' => null, 'team2' => null, 'score1' => null, 'score2' => null]"
                            variant="finale"
                            label="Finale"
                        />
                    </div>
                </div>

                <!-- PLATZ 3 -->
                <div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #FFA07A; margin-bottom: 20px; text-align: center; padding-bottom: 10px; border-bottom: 2px solid #FFA07A;">
                        Platz 3
                    </h3>
                    <div>
                        <x-match-card
                            :match="['team1' => null, 'team2' => null, 'score1' => null, 'score2' => null]"
                            variant="platz3"
                            label="Spiel um Platz 3"
                        />
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
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

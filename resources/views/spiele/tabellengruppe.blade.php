@extends('layouts.app')

@section('title', 'Tabellenstände')

@section('content')
<div class="container-main" style="padding-top: 40px;">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
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
        <a href="{{ route('spiele.tabellengruppe', ['gruppenid' => 'all']) }}" style="padding: 14px 24px; color: {{ request('gruppenid') === 'all' || !request('gruppenid') ? 'white' : '#999' }}; background: {{ request('gruppenid') === 'all' || !request('gruppenid') ? '#1D68AC' : 'transparent' }}; text-decoration: none; font-weight: 600; font-size: 16px; border-radius: 8px 8px 0 0; transition: all 0.3s; cursor: pointer;">
            <i class="fas fa-th"></i> Alle Gruppen
        </a>
        @foreach (['A', 'B', 'C', 'D'] as $gruppe)
            <a href="{{ route('spiele.tabellengruppe', ['gruppenid' => $gruppe]) }}" style="padding: 14px 24px; color: {{ request('gruppenid') === $gruppe ? 'white' : '#999' }}; background: {{ request('gruppenid') === $gruppe ? $gruppenFarben[$gruppe] : 'transparent' }}; text-decoration: none; font-weight: 600; font-size: 16px; border-radius: 8px 8px 0 0; transition: all 0.3s;">
                Gruppe {{ $gruppe }}
            </a>
        @endforeach
    </div>

    <!-- Content -->
    <div style="display: flex; justify-content: center;">
        @if (!request('gruppenid') || request('gruppenid') === 'all')
            <!-- All Groups View - 4 Columns -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; width: 100%;">
                @foreach (['A', 'B', 'C', 'D'] as $gruppe)
                    @if (isset($sortedTeamsByGroup[$gruppe]))
                        <div>
                            <h3 style="font-size: 18px; font-weight: 700; color: {{ $gruppenFarben[$gruppe] }}; margin-bottom: 20px; text-align: center;">
                                Gruppe {{ $gruppe }}
                            </h3>

                            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); overflow: hidden; border-top: 4px solid {{ $gruppenFarben[$gruppe] }};">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr style="background: {{ $gruppenFarben[$gruppe] }}; color: white;">
                                            <th style="padding: 10px; text-align: center; font-weight: 600; font-size: 11px; width: 50px;">Platz</th>
                                            <th style="padding: 10px; text-align: left; font-weight: 600; font-size: 11px;">Team</th>
                                            <th style="padding: 10px; text-align: center; font-weight: 600; font-size: 11px; width: 60px;">Punkte</th>
                                            <th style="padding: 10px; text-align: center; font-weight: 600; font-size: 11px; width: 70px;">Spielpunkte</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                        @foreach ($sortedTeamsByGroup[$gruppe] as $team)
                                            @php
                                                $diff = $team['spielpunkte'] - $team['gegnerpunkte'];
                                                $bgColor = $i === 1 ? 'rgba(' . hexdec(substr($gruppenFarben[$gruppe], 1, 2)) . ', ' . hexdec(substr($gruppenFarben[$gruppe], 3, 2)) . ', ' . hexdec(substr($gruppenFarben[$gruppe], 5, 2)) . ', 0.1)' : ($i % 2 === 0 ? '#ffffff' : '#f9f9f9');
                                            @endphp
                                            <tr style="background: {{ $bgColor }}; border-bottom: 1px solid #e0e0e0;">
                                                <td style="padding: 10px; text-align: center; font-weight: 700; font-size: 12px; color: {{ $gruppenFarben[$gruppe] }};">
                                                    {{ $i++ }}
                                                </td>
                                                <td style="padding: 10px; text-align: left; font-weight: 600; font-size: 12px; color: #333;">
                                                    {{ $team['team'] }}
                                                </td>
                                                <td style="padding: 10px; text-align: center; font-weight: 700; font-size: 12px; color: {{ $gruppenFarben[$gruppe] }};">
                                                    {{ $team['punkte'] }}
                                                </td>
                                                <td style="padding: 10px; text-align: center; font-size: 11px; color: #666;">
                                                    <span style="font-weight: 600;">{{ $team['spielpunkte'] }}</span>:<span style="font-weight: 600;">{{ $team['gegnerpunkte'] }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <!-- Single Group View - Full Width -->
            @php $gruppe = request('gruppenid'); @endphp
            @if (isset($sortedTeamsByGroup[$gruppe]))
                <div style="width: 100%; max-width: 700px;">
                    <h2 style="font-size: 28px; font-weight: 700; color: {{ $gruppenFarben[$gruppe] }}; margin-bottom: 30px; text-align: center;">
                        Tabelle Gruppe {{ $gruppe }}
                    </h2>

                    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); overflow: hidden; border-top: 4px solid {{ $gruppenFarben[$gruppe] }};">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: {{ $gruppenFarben[$gruppe] }}; color: white;">
                                    <th style="padding: 16px; text-align: center; font-weight: 600; font-size: 14px; width: 60px;">Platz</th>
                                    <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px;">Team</th>
                                    <th style="padding: 16px; text-align: center; font-weight: 600; font-size: 14px; width: 80px;">Punkte</th>
                                    <th style="padding: 16px; text-align: center; font-weight: 600; font-size: 14px; width: 120px;">Spielpunkte</th>
                                    <th style="padding: 16px; text-align: center; font-weight: 600; font-size: 14px; width: 80px;">Diff.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($sortedTeamsByGroup[$gruppe] as $team)
                                    @php
                                        $diff = $team['spielpunkte'] - $team['gegnerpunkte'];
                                        $bgColor = $i === 1 ? 'rgba(' . hexdec(substr($gruppenFarben[$gruppe], 1, 2)) . ', ' . hexdec(substr($gruppenFarben[$gruppe], 3, 2)) . ', ' . hexdec(substr($gruppenFarben[$gruppe], 5, 2)) . ', 0.1)' : ($i % 2 === 0 ? '#ffffff' : '#f9f9f9');
                                    @endphp
                                    <tr style="background: {{ $bgColor }}; border-bottom: 1px solid #e0e0e0; transition: background 0.3s;">
                                        <td style="padding: 16px; text-align: center; font-weight: 700; font-size: 16px; color: {{ $gruppenFarben[$gruppe] }};">
                                            {{ $i++ }}
                                        </td>
                                        <td style="padding: 16px; text-align: left; font-weight: 600; font-size: 15px; color: #333;">
                                            {{ $team['team'] }}
                                        </td>
                                        <td style="padding: 16px; text-align: center; font-weight: 700; font-size: 18px; color: {{ $gruppenFarben[$gruppe] }};">
                                            {{ $team['punkte'] }}
                                        </td>
                                        <td style="padding: 16px; text-align: center; font-size: 14px; color: #666;">
                                            <span style="font-weight: 600;">{{ $team['spielpunkte'] }}</span>
                                            <span style="color: #999;">:</span>
                                            <span style="font-weight: 600;">{{ $team['gegnerpunkte'] }}</span>
                                        </td>
                                        <td style="padding: 16px; text-align: center; font-weight: 600; font-size: 14px; color: {{ $diff > 0 ? '#4CAF50' : ($diff < 0 ? '#f44336' : '#999') }};">
                                            {{ $diff > 0 ? '+' : '' }}{{ $diff }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

<style>
    tbody tr:hover {
        background: #f5f5f5 !important;
    }

    @media (max-width: 1400px) {
        div[style*="grid-template-columns: repeat(4"] {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }

    @media (max-width: 768px) {
        div[style*="grid-template-columns: repeat(4"] {
            grid-template-columns: 1fr !important;
        }

        table {
            font-size: 12px !important;
        }

        th, td {
            padding: 10px !important;
        }

        h2 {
            font-size: 20px !important;
        }
    }
</style>
@endsection


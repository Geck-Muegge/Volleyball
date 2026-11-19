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

    <!-- SVG Bracket Container -->
    <div style="display: flex; justify-content: center; margin-bottom: 40px; overflow-x: auto; padding: 20px;">
        <svg width="1200" height="600" style="min-width: 1200px;">
            <!-- Verbindungslinien -->
            <line x1="150" y1="75" x2="250" y2="75" stroke="#e0e0e0" stroke-width="2"/>
            <line x1="150" y1="175" x2="250" y2="175" stroke="#e0e0e0" stroke-width="2"/>
            <line x1="150" y1="275" x2="250" y2="275" stroke="#e0e0e0" stroke-width="2"/>
            <line x1="150" y1="375" x2="250" y2="375" stroke="#e0e0e0" stroke-width="2"/>
            <line x1="150" y1="475" x2="250" y2="475" stroke="#e0e0e0" stroke-width="2"/>
            <line x1="150" y1="525" x2="250" y2="525" stroke="#e0e0e0" stroke-width="2"/>

            <!-- Viertelfinale zu Halbfinale -->
            <path d="M 350 75 Q 400 125 450 150" stroke="#e0e0e0" stroke-width="2" fill="none"/>
            <path d="M 350 175 Q 400 125 450 150" stroke="#e0e0e0" stroke-width="2" fill="none"/>
            <path d="M 350 275 Q 400 325 450 350" stroke="#e0e0e0" stroke-width="2" fill="none"/>
            <path d="M 350 375 Q 400 325 450 350" stroke="#e0e0e0" stroke-width="2" fill="none"/>

            <!-- Halbfinale zu Finale -->
            <path d="M 550 150 Q 600 200 650 250" stroke="#e0e0e0" stroke-width="2" fill="none"/>
            <path d="M 550 350 Q 600 300 650 250" stroke="#e0e0e0" stroke-width="2" fill="none"/>

            <!-- Spiel um Platz 3 -->
            <path d="M 550 150 Q 600 200 650 450" stroke="#e0e0e0" stroke-width="2" fill="none"/>
            <path d="M 550 350 Q 600 400 650 450" stroke="#e0e0e0" stroke-width="2" fill="none"/>

            <!-- Finale zu Gewinner -->
            <path d="M 750 250 L 850 250" stroke="#e0e0e0" stroke-width="2" fill="none"/>
            <path d="M 750 450 L 850 450" stroke="#e0e0e0" stroke-width="2" fill="none"/>
        </svg>
    </div>

    <!-- Bracket Content -->
    <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px; max-width: 1400px; margin: 0 auto;">

        <!-- VIERTELFINALE -->
        <div>
            <h3 style="font-size: 16px; font-weight: 700; color: #1D68AC; margin-bottom: 15px; text-align: center;">
                Viertelfinale
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @for ($i = 1; $i <= 4; $i++)
                    <div style="background: white; border-radius: 10px; padding: 12px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); border-left: 4px solid #FF6B6B;">
                        <div style="font-size: 12px; color: #999; margin-bottom: 8px; font-weight: 600;">
                            VF {{ $i }}
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <div style="font-size: 13px; font-weight: 600; color: #333;">Team A</div>
                            <div style="font-size: 13px; font-weight: 600; color: #333;">Team B</div>
                        </div>
                        <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #e0e0e0; text-align: center;">
                            <a href="#" style="font-size: 11px; color: #1D68AC; text-decoration: none; font-weight: 600;">
                                <i class="fas fa-edit"></i> Ergebnis
                            </a>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- HALBFINALE -->
        <div>
            <h3 style="font-size: 16px; font-weight: 700; color: #4ECDC4; margin-bottom: 15px; text-align: center;">
                Halbfinale
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 40px;">
                @for ($i = 1; $i <= 2; $i++)
                    <div style="background: white; border-radius: 10px; padding: 12px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); border-left: 4px solid #4ECDC4;">
                        <div style="font-size: 12px; color: #999; margin-bottom: 8px; font-weight: 600;">
                            HF {{ $i }}
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <div style="font-size: 13px; font-weight: 600; color: #333;">Sieger VF {{ $i * 2 - 1 }}</div>
                            <div style="font-size: 13px; font-weight: 600; color: #333;">Sieger VF {{ $i * 2 }}</div>
                        </div>
                        <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #e0e0e0; text-align: center;">
                            <a href="#" style="font-size: 11px; color: #1D68AC; text-decoration: none; font-weight: 600;">
                                <i class="fas fa-edit"></i> Ergebnis
                            </a>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- FINALE & PLATZ 3 -->
        <div>
            <h3 style="font-size: 16px; font-weight: 700; color: #45B7D1; margin-bottom: 15px; text-align: center;">
                Finale
            </h3>
            <div style="background: white; border-radius: 10px; padding: 12px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); border-left: 4px solid #45B7D1; margin-bottom: 40px;">
                <div style="font-size: 12px; color: #999; margin-bottom: 8px; font-weight: 600;">
                    FINALE
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <div style="font-size: 13px; font-weight: 600; color: #333;">Sieger HF 1</div>
                    <div style="font-size: 13px; font-weight: 600; color: #333;">Sieger HF 2</div>
                </div>
                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #e0e0e0; text-align: center;">
                    <a href="#" style="font-size: 11px; color: #1D68AC; text-decoration: none; font-weight: 600;">
                        <i class="fas fa-edit"></i> Ergebnis
                    </a>
                </div>
            </div>

            <h3 style="font-size: 16px; font-weight: 700; color: #FFA07A; margin-bottom: 15px; text-align: center;">
                Platz 3
            </h3>
            <div style="background: white; border-radius: 10px; padding: 12px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); border-left: 4px solid #FFA07A;">
                <div style="font-size: 12px; color: #999; margin-bottom: 8px; font-weight: 600;">
                    PLATZ 3
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <div style="font-size: 13px; font-weight: 600; color: #333;">Verlierer HF 1</div>
                    <div style="font-size: 13px; font-weight: 600; color: #333;">Verlierer HF 2</div>
                </div>
                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #e0e0e0; text-align: center;">
                    <a href="#" style="font-size: 11px; color: #1D68AC; text-decoration: none; font-weight: 600;">
                        <i class="fas fa-edit"></i> Ergebnis
                    </a>
                </div>
            </div>
        </div>

        <!-- GEWINNER -->
        <div style="grid-column: 4 / 6;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1D68AC; margin-bottom: 15px; text-align: center;">
                Endergebnis
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 40px;">
                <!-- Gold -->
                <div style="background: linear-gradient(135deg, #FFD700 0%, #FFC700 100%); border-radius: 10px; padding: 16px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); text-align: center;">
                    <div style="font-size: 12px; color: rgba(0, 0, 0, 0.6); margin-bottom: 8px; font-weight: 600;">
                        <i class="fas fa-medal"></i> 1. Platz
                    </div>
                    <div style="font-size: 16px; font-weight: 700; color: #333;">
                        Sieger Finale
                    </div>
                </div>

                <!-- Silber -->
                <div style="background: linear-gradient(135deg, #C0C0C0 0%, #A8A8A8 100%); border-radius: 10px; padding: 16px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); text-align: center;">
                    <div style="font-size: 12px; color: rgba(0, 0, 0, 0.6); margin-bottom: 8px; font-weight: 600;">
                        <i class="fas fa-medal"></i> 2. Platz
                    </div>
                    <div style="font-size: 16px; font-weight: 700; color: #333;">
                        Verlierer Finale
                    </div>
                </div>

                <!-- Bronze -->
                <div style="background: linear-gradient(135deg, #CD7F32 0%, #B87333 100%); border-radius: 10px; padding: 16px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); text-align: center;">
                    <div style="font-size: 12px; color: rgba(0, 0, 0, 0.6); margin-bottom: 8px; font-weight: 600;">
                        <i class="fas fa-medal"></i> 3. Platz
                    </div>
                    <div style="font-size: 16px; font-weight: 700; color: #333;">
                        Sieger Platz 3
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 1400px) {
        div[style*="grid-template-columns: repeat(5"] {
            grid-template-columns: repeat(3, 1fr) !important;
        }
    }

    @media (max-width: 1024px) {
        div[style*="grid-template-columns: repeat(5"] {
            grid-template-columns: repeat(2, 1fr) !important;
        }

        svg {
            display: none !important;
        }
    }

    @media (max-width: 768px) {
        div[style*="grid-template-columns: repeat(5"] {
            grid-template-columns: 1fr !important;
        }

        h1 {
            font-size: 24px !important;
        }
    }
</style>
@endsection

@extends('layouts.app')

@section('title', 'Spielstand erfassen')

@section('content')
<div style="height: 100vh; display: flex; flex-direction: column; background: linear-gradient(135deg, #1D68AC 0%, #0F4C8B 100%); padding: 20px; font-family: Arial, sans-serif;">

    @php
        // Farben aus deiner App
        $farben = [
            'A' => '#FF6B6B',
            'B' => '#4ECDC4',
            'C' => '#45B7D1',
            'D' => '#FFA07A'
        ];

        // Bestimme Runde und Farbe
        $runde = $spiel->runde ?? null;
        $rundeText = '';
        $rundeFarbe = '#1D68AC';

        if (in_array($runde, ['A', 'B', 'C', 'D'])) {
            $rundeText = 'Gruppenspiel ' . $runde;
            $rundeFarbe = $farben[$runde] ?? '#1D68AC';
        } elseif (in_array($runde, ['VF1', 'VF2', 'VF3', 'VF4'])) {
            $rundeText = 'Viertelfinale ' . substr($runde, 2);
            $rundeFarbe = '#FF6B6B';
        } elseif (in_array($runde, ['HF1', 'HF2'])) {
            $rundeText = 'Halbfinale ' . substr($runde, 2);
            $rundeFarbe = '#4ECDC4';
        } elseif ($runde === 'FI' || $runde === 'Finale') {
            $rundeText = 'Finale';
            $rundeFarbe = '#45B7D1';
        } elseif ($runde === 'P3' || $runde === 'Spiel um Platz 3') {
            $rundeText = 'Spiel um Platz 3';
            $rundeFarbe = '#FFA07A';
        }
    @endphp

    <!-- Header -->
    <div style="text-align: center; color: white; margin-bottom: 40px; padding-top: 20px;">
        <h2 style="font-size: 28px; font-weight: 700; margin: 0 0 10px 0; color: {{ $rundeFarbe }};">
            {{ $rundeText }}
        </h2>
        <p style="font-size: 18px; opacity: 0.9; margin: 0; font-weight: 600;">
            Spiel-ID: {{ $spiel->spielid }}
        </p>
    </div>

    <!-- Teams & Score Container -->
    <div style="background: white; border-radius: 20px; padding: 40px 30px; margin-bottom: 30px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3); flex: 1; display: flex; flex-direction: column; justify-content: center;">

        <!-- Team A -->
        <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 30px; align-items: center; margin-bottom: 60px;">
            <div style="text-align: center;">
                <div style="font-size: 36px; font-weight: 700; color: #1D68AC; margin-bottom: 30px; line-height: 1.3;">
                    {{ $spiel->teamA ?? $spiel->teamAFK->teamName ?? 'Team A' }}
                </div>
                <div style="display: flex; gap: 15px; justify-content: center; align-items: center;">
                    <button onclick="decrementA()" style="width: 70px; height: 70px; background: #FF6B6B; color: white; border: none; border-radius: 50%; font-size: 36px; cursor: pointer; font-weight: 700; transition: all 0.2s; box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">−</button>
                    <div id="scoreA" style="width: 120px; height: 120px; background: linear-gradient(135deg, #f0f0f0 0%, #e8e8e8 100%); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 72px; font-weight: 700; color: {{ $rundeFarbe }}; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);">
                        {{ $liveScore->punkteA ?? 0 }}
                    </div>
                    <button onclick="incrementA()" style="width: 70px; height: 70px; background: #4ECDC4; color: white; border: none; border-radius: 50%; font-size: 36px; cursor: pointer; font-weight: 700; transition: all 0.2s; box-shadow: 0 4px 12px rgba(78, 205, 196, 0.3);" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">+</button>
                </div>
            </div>

            <div style="text-align: center; color: #ccc; font-size: 48px; font-weight: 700;">vs</div>

            <!-- Team B -->
            <div style="text-align: center;">
                <div style="font-size: 36px; font-weight: 700; color: #1D68AC; margin-bottom: 30px; line-height: 1.3;">
                    {{ $spiel->teamB ?? $spiel->teamBFK->teamName ?? 'Team B' }}
                </div>
                <div style="display: flex; gap: 15px; justify-content: center; align-items: center;">
                    <button onclick="decrementB()" style="width: 70px; height: 70px; background: #FF6B6B; color: white; border: none; border-radius: 50%; font-size: 36px; cursor: pointer; font-weight: 700; transition: all 0.2s; box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">−</button>
                    <div id="scoreB" style="width: 120px; height: 120px; background: linear-gradient(135deg, #f0f0f0 0%, #e8e8e8 100%); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 72px; font-weight: 700; color: {{ $rundeFarbe }}; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);">
                        {{ $liveScore->punkteB ?? 0 }}
                    </div>
                    <button onclick="incrementB()" style="width: 70px; height: 70px; background: #4ECDC4; color: white; border: none; border-radius: 50%; font-size: 36px; cursor: pointer; font-weight: 700; transition: all 0.2s; box-shadow: 0 4px 12px rgba(78, 205, 196, 0.3);" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">+</button>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button onclick="submitResult()" style="width: 100%; background: linear-gradient(135deg, #1D68AC 0%, #0F4C8B 100%); color: white; padding: 22px; border: none; border-radius: 15px; font-size: 22px; font-weight: 700; cursor: pointer; transition: all 0.3s; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(0, 0, 0, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 20px rgba(0, 0, 0, 0.2)'">
            <i class="fas fa-check-circle"></i> Spiel abschließen
        </button>
    </div>

    <!-- Status Message -->
    <div id="statusMessage" style="text-align: center; color: white; font-size: 16px; display: none; padding: 15px; background: rgba(0, 0, 0, 0.2); border-radius: 10px; margin-bottom: 20px;"></div>

</div>

<script>
    let punkteA = {{ isset($liveScore) ? $liveScore->punkteA : ($spiel->punkteA ?? 0) }};
    let punkteB = {{ isset($liveScore) ? $liveScore->punkteB : ($spiel->punkteB ?? 0) }};
    const spielid = {{ $spiel->spielid }};
    const rundeFarbe = '{{ $rundeFarbe ?? "#1D68AC" }}';

    function incrementA() {
        if (punkteA < 50) {
            punkteA++;
            updateDisplay();
            updateLiveScore();
        }
    }

    function decrementA() {
        if (punkteA > 0) {
            punkteA--;
            updateDisplay();
            updateLiveScore();
        }
    }

    function incrementB() {
        if (punkteB < 50) {
            punkteB++;
            updateDisplay();
            updateLiveScore();
        }
    }

    function decrementB() {
        if (punkteB > 0) {
            punkteB--;
            updateDisplay();
            updateLiveScore();
        }
    }

    function updateDisplay() {
        document.getElementById('scoreA').textContent = punkteA;
        document.getElementById('scoreB').textContent = punkteB;
    }

    function updateLiveScore() {
        fetch('{{ route("mobile.update-live-score") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                spielid: spielid,
                punkteA: punkteA,
                punkteB: punkteB
            })
        })
        .catch(error => console.error('Live-Update Fehler:', error));
    }

    function submitResult() {
        const btn = event.target;
        btn.disabled = true;
        btn.textContent = '⏳ Wird übertragen...';

        fetch('{{ route("mobile.submit-result") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                spielid: spielid,
                punkteA: punkteA,
                punkteB: punkteB
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('✓ Ergebnis eingereicht!', 'success');
                setTimeout(() => {
                    window.location.href = '{{ route("mobile.score", $spiel->spielid) }}';
                }, 2000);
            }
        })
        .catch(error => {
            showMessage('✗ Fehler beim Übertragen', 'error');
            btn.disabled = false;
            btn.textContent = '✓ Spiel abschließen';
        });
    }

    function showMessage(msg, type) {
        const el = document.getElementById('statusMessage');
        el.textContent = msg;
        el.style.color = type === 'success' ? '#4ECDC4' : '#FF6B6B';
        el.style.display = 'block';
    }
</script>

<style>
    button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        div[style*="grid-template-columns: 1fr auto 1fr"] {
            grid-template-columns: 1fr !important;
            gap: 20px !important;
        }

        h2 {
            font-size: 24px !important;
        }

        div[style*="font-size: 36px"] {
            font-size: 28px !important;
        }

        div[style*="font-size: 72px"] {
            font-size: 56px !important;
            width: 100px !important;
            height: 100px !important;
        }

        button[style*="width: 70px"] {
            width: 60px !important;
            height: 60px !important;
            font-size: 28px !important;
        }
    }
</style>
@endsection
